<?php

namespace App\Auth;

use Illuminate\Auth\GuardHelpers;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class SsoTokenGuard implements Guard
{
    use GuardHelpers;

    protected Request $request;
    protected ?array $cachedUserData = null;
    protected int $cacheTtl;

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->cacheTtl = config('services.sso_cache_ttl');
    }

    public function user()
    {
        if (!is_null($this->user)) {
            return $this->user;
        }

        $token = $this->getTokenFromHeader();

        if (!$token) {
            return null;
        }

        $cacheKey = 'sso_token_' . md5($token);
        
        if (Cache::has($cacheKey)) {
            $userData = Cache::get($cacheKey);
            
            if ($userData === 'invalid') {
                return null;
            }
            
            $this->cachedUserData = $userData;
            $this->user = $this->createVirtualUser($userData);
            return $this->user;
        }

        try {
            $response = Http::withHeaders([
                'Accept' => 'application/json'
            ])->timeout(5)->post(config('services.sso_verify_url'), [
                'token' => $token
            ]);

            if ($response->ok()) {
                $userData = $response->json();
                
                Cache::put($cacheKey, $userData, $this->cacheTtl);
                
                $this->cachedUserData = $userData;
                $this->user = $this->createVirtualUser($userData);
                return $this->user;
            }
            
            Cache::put($cacheKey, 'invalid', $this->cacheTtl);
            
        } catch (\Exception $e) {
            if (Cache::has($cacheKey)) {
                $cached = Cache::get($cacheKey);
                if ($cached !== 'invalid') {
                    $this->cachedUserData = $cached;
                    $this->user = $this->createVirtualUser($cached);
                    return $this->user;
                }
            }
            
            return null;
        }

        return null;
    }

    private function getTokenFromHeader()
    {
        $header = $this->request->header('Authorization');
        if ($header && str_starts_with($header, 'Bearer ')) {
            return substr($header, 7);
        }
        return null;
    }

    private function createVirtualUser(array $userData)
    {
        $user = new \Illuminate\Foundation\Auth\User();
        $user->id = $userData['uuid'] ?? 'sso-user';
        $user->email = $userData['email'] ?? null;
        $user->name = $userData['email'] ?? 'SSO User';

        $user->roles = $userData['roles'] ?? [];

        $user->sso_data = $userData;
        $user->token_cached_at = now();
        
        return $user;
    }
    
    public function validate(array $credentials = []) 
    { 
        return false; 
    }
}
