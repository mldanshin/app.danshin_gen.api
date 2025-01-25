<?php

namespace App\Auth;

use Illuminate\Support\Facades\Auth;

trait HasSsoRoles
{
    protected function getUserRoles(): array
    {
        $user = Auth::user();
        return $user->roles ?? [];
    }

    protected function hasRole(string $role): bool
    {
        $roles = $this->getUserRoles();
        return in_array($role, $roles);
    }

    protected function hasAnyRole(array $roles): bool
    {
        $userRoles = $this->getUserRoles();
        return !empty(array_intersect($roles, $userRoles));
    }

    protected function hasAllRoles(array $roles): bool
    {
        $userRoles = $this->getUserRoles();
        return empty(array_diff($roles, $userRoles));
    }

    protected function getSsoUserData(): ?array
    {
        $user = Auth::user();
        return $user->sso_data ?? null;
    }
}
