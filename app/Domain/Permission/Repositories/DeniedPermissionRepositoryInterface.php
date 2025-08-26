<?php

namespace App\Domain\Permission\Repositories;

use App\Domain\Permission\Entities\DeniedPermission;

interface DeniedPermissionRepositoryInterface
{
    public function assign(int $userId, int $permissionId): DeniedPermission;
    public function revoke(int $userId, int $permissionId): bool;
    public function exists(int $userId, int $permissionId): bool;
}
