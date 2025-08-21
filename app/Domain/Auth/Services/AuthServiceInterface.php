<?php

namespace App\Domain\Auth\Services;

interface AuthServiceInterface
{
    public function hasPermission(int $userId, string $permission): bool;
    public function hasRole(int $userId, string $role): bool;
    public function getUserPermissions(int $userId): array;
    public function getUserRoles(int $userId): array;
}