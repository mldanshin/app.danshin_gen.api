<?php

namespace App\Auth;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Auth\GuardHelpers;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;

class AccessTokenGuard implements Guard
{
    use GuardHelpers;

    protected Request $request;
    protected string $jwtSecret;

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->jwtSecret = config("services.jwt_secret");
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

        if (!$this->verifyAccessToken($token)) {
            return null;
        }

        $userData = $this->getUserDataFromToken($token);

        if (!$userData) {
            return null;
        }

        $this->user = $this->createVirtualUser($userData);

        return $this->user;
    }

    public function validate(array $credentials = []) 
    { 
        return false; 
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
        $user->id = $userData['uuid'];
        $user->email = $userData['email'];
        $user->name = $userData['email'];

        $user->roles = $userData['roles'] ?? [];

        $user->token_cached_at = now();
        
        return $user;
    }

    private function verifyAccessToken(string $token): ?object
    {
        try {
            $decoded = JWT::decode($token, new Key($this->jwtSecret, 'HS256'));
            return $decoded;
        } catch (\Exception $e) {
            return null;
        }
    }

    private function getUserDataFromToken(string $token): ?array
    {
        $decoded = $this->verifyAccessToken($token);
        
        if (!$decoded) {
            return null;
        }

        $decodedArray = (array) $decoded;

        return [
            'sub' => $decodedArray['sub'] ?? null,
            'uuid' => $decodedArray['uuid'] ?? null,
            'roles' => $decodedArray['roles'] ?? [],
            'email' => $decodedArray['sub'] ?? null,
        ];
    }
}
