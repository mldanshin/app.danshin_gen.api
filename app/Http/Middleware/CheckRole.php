<?php

namespace App\Http\Middleware;

use App\Models\Eloquent\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\User as UserAuth;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  ...$roles
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $user = Auth::guard('api')->user();
        
        if (!$user) {
            return response()->json([
                'error' => 'Unauthorized'
            ], 401);
        }
        
        $userRoles = $this->getUserRoles($request, $user);
        
        $hasRole = false;
        foreach ($roles as $role) {
            if (in_array($role, $userRoles)) {
                $hasRole = true;
                break;
            }
        }
        
        if (!$hasRole) {
            return response()->json([
                'error' => 'Forbidden',
                'message' => 'You do not have the required role',
                'required_roles' => $roles,
                'your_roles' => $userRoles
            ], 403);
        }
        
        return $next($request);
    }

    protected function getUserRoles(Request $request, User|UserAuth $user): array
    {
        if (isset($user->roles) && is_array($user->roles)) {
            return $user->roles;
        }
        
        if (isset($user->sso_data) && is_array($user->sso_data)) {
            if (isset($user->sso_data['roles'])) {
                return $user->sso_data['roles'];
            }
        }

        $token = $request->bearerToken();
        if ($token) {
            $personalToken = \Laravel\Sanctum\PersonalAccessToken::findToken($token);
            if ($personalToken && $personalToken->abilities) {
                return $personalToken->abilities;
            }
        }
        
        if (isset($user->role) && !empty($user->role)) {
            return [$user->role];
        }
        
        if (isset($user->roles) && !empty($user->roles)) {
            if (is_array($user->roles)) {
                return $user->roles;
            }
            if (is_string($user->roles)) {
                $decoded = json_decode($user->roles, true);
                if (is_array($decoded)) {
                    return $decoded;
                }
                return [$user->roles];
            }
        }
        
        if (method_exists($user, 'getRoleNames')) {
            return $user->getRoleNames()->toArray();
        }
        
        if (method_exists($user, 'roles')) {
            $roles = $user->roles;
            if ($roles instanceof \Illuminate\Support\Collection) {
                return $roles->pluck('name')->toArray();
            }
        }
        
        return [];
    }
}
