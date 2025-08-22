<?php

namespace App\Domain\Auth\Services;

use App\Domain\Permission\Repositories\PermissionRepositoryInterface;
use App\Domain\Usuario\Repositories\UserRepositoryInterface;
use App\Models\Users\User;
use Illuminate\Support\Facades\DB;

class AuthService implements AuthServiceInterface
{
    public function __construct(
        private PermissionRepositoryInterface $permissionRepository,
        private UserRepositoryInterface $userRepository
    ) {}

    public function hasPermission(int $userId, string $permissionName): bool
    {
        $user = User::with(['roles', 'deniedPermissions'])->find($userId);

        // Se a permissão está explicitamente negada, nega mesmo se admin
        if ($user->deniedPermissions->contains('name', $permissionName)) {
            return false;
        }

        // Se for admin, concede acesso (salvo a negação acima)
        if ($user->roles->contains('name', 'admin')) {
            return true;
        }

        // Caso contrário, verifica se a permissão está em user_roles/role_permissions
        return DB::table('user_permissions')
            ->join('permissions', 'user_permissions.permission_id', '=', 'permissions.id')
            ->where('user_permissions.user_id', $userId)
            ->where('permissions.name', $permissionName)
            ->exists()
            ||
            DB::table('user_roles')
            ->join('role_permissions', 'user_roles.role_id', '=', 'role_permissions.role_id')
            ->join('permissions', 'role_permissions.permission_id', '=', 'permissions.id')
            ->where('user_roles.user_id', $userId)
            ->where('permissions.name', $permissionName)
            ->exists();
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
