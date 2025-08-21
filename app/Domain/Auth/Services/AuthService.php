<?php

namespace App\Domain\Auth\Services;

use App\Domain\Permission\Repositories\PermissionRepositoryInterface;
use App\Domain\Usuario\Repositories\UserRepositoryInterface;

class AuthService implements AuthServiceInterface
{
    public function __construct(
        private PermissionRepositoryInterface $permissionRepository,
        private UserRepositoryInterface $userRepository
    ) {}

    public function hasPermission(int $userId, string $permission): bool
    {
        $userPermissions = $this->getUserPermissions($userId);
        return in_array($permission, $userPermissions);
    }

    public function hasRole(int $userId, string $role): bool
    {
        $userRoles = $this->getUserRoles($userId);
        return in_array($role, $userRoles);
    }

    public function getUserPermissions(int $userId): array
    {
        // Busca permissões diretas do usuário + permissões dos roles
        $userPermissions = $this->permissionRepository->findByUser($userId);
        
        $user = $this->userRepository->findById($userId);
        if (!$user) {
            return [];
        }

        $rolePermissions = [];
        foreach ($user->roles as $role) {
            $rolePermissions = array_merge(
                $rolePermissions,
                $this->permissionRepository->findByRole($role->id)
            );
        }

        // Merge e remove duplicatas
        $allPermissions = array_merge($userPermissions, $rolePermissions);
        return array_unique(array_column($allPermissions, 'name'));
    }

    public function getUserRoles(int $userId): array
    {
        $user = $this->userRepository->findById($userId);
        return $user?->roles ?? [];
    }
    
}