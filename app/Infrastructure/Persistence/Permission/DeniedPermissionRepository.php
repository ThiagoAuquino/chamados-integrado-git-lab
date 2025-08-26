<?php

namespace App\Infrastructure\Persistence\Permission;

use App\Domain\Permission\Entities\DeniedPermission;
use App\Domain\Permission\Repositories\DeniedPermissionRepositoryInterface;
use Illuminate\Support\Facades\DB;

class DeniedPermissionRepository implements DeniedPermissionRepositoryInterface
{
    protected string $table = 'denied_permissions';

    public function assign(int $userId, int $permissionId): DeniedPermission
    {
        DB::table($this->table)->updateOrInsert(
            ['user_id' => $userId, 'permission_id' => $permissionId],
            ['created_at' => now(), 'updated_at' => now()]
        );

        return new DeniedPermission($userId, $permissionId);
    }

    public function revoke(int $userId, int $permissionId): bool
    {
        return DB::table($this->table)
            ->where('user_id', $userId)
            ->where('permission_id', $permissionId)
            ->delete() > 0;
    }

    public function exists(int $userId, int $permissionId): bool
    {
        return DB::table($this->table)
            ->where('user_id', $userId)
            ->where('permission_id', $permissionId)
            ->exists();
    }
}
