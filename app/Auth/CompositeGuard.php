<?php

namespace App\Auth;

use Illuminate\Auth\GuardHelpers;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;

class CompositeGuard implements Guard
{
    use GuardHelpers;

    protected Request $request;
    protected AccessTokenGuard $accessGuard;
    protected Guard $sanctumGuard;

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->accessGuard = new AccessTokenGuard($request);
        $this->sanctumGuard = app('auth')->guard('sanctum');
    }

    public function user()
    {
        if (!is_null($this->user)) {
            return $this->user;
        }

        $user = $this->accessGuard->user();
        
        if ($user) {
            $this->user = $user;
            if (isset($user->roles)) {
                $this->user->roles = $user->roles;
            }
            return $this->user;
        }

        $user = $this->sanctumGuard->user();
        
        if ($user) {
            $this->user = $user;
            if (method_exists($user, 'getRoleNames')) {
                $this->user->roles = $user->getRoleNames()->toArray();
            } elseif (method_exists($user, 'roles')) {
                $this->user->roles = $user->roles->pluck('name')->toArray();
            }
            return $this->user;
        }

        return null;
    }

    public function validate(array $credentials = [])
    {
        if ($this->accessGuard->validate($credentials)) {
            return true;
        }
        
        if ($this->sanctumGuard->validate($credentials)) {
            return true;
        }

        return false;
    }

    public function setRequest(Request $request)
    {
        $this->request = $request;
        return $this;
    }
}
