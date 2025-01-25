<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

final class SsoValidationService
{
    private string $verifyUrl;
    private int $timeout;
    private int $retryAttempts;
    private int $cacheTtl;
    
    public function __construct()
    {
        $this->verifyUrl = config('services.sso_verify_url');
        $this->timeout = config('services.sso_timeout');
        $this->retryAttempts = config('services.sso_retry_attempts');
        $this->cacheTtl = config('services.sso_cache_ttl');
    }
    
    public function validateToken(string $token, bool $forceRefresh = false): ?array
    {
        $cacheKey = 'sso_validation_' . md5($token);

        if (!$forceRefresh) {
            $cached = Cache::get($cacheKey);
            
            if ($cached !== null) {
                if ($cached === 'invalid') {
                    return null;
                }
                return $cached;
            }
        }
        
        $attempts = 0;
        
        while ($attempts < $this->retryAttempts) {
            try {
                $response = Http::withHeaders([
                    'Accept' => 'application/json',
                    'X-Request-Id' => request()->id ?? uniqid(),
                ])->timeout($this->timeout)->get($this->verifyUrl, [
                    'token' => $token
                ]);

                if ($response->ok()) {
                    $data = $response->json();
                    Cache::put($cacheKey, $data, $this->cacheTtl);
                    return $data;
                }
                
                if ($response->status() === 401) {
                    Cache::put($cacheKey, 'invalid', $this->cacheTtl);
                    return null;
                }

                $attempts++;
                usleep(100000 * $attempts);
                
            } catch (\Exception) {
                $attempts++;
            }
        }
        
        $cached = Cache::get($cacheKey);
        if ($cached !== null && $cached !== 'invalid') {
            return $cached;
        }
        
        return null;
    }
    
    public function clearCache(string $token): void
    {
        $cacheKey = 'sso_validation_' . md5($token);
        Cache::forget($cacheKey);
    }
    
    public function refreshToken(string $token): ?array
    {
        return $this->validateToken($token, true);
    }
}
