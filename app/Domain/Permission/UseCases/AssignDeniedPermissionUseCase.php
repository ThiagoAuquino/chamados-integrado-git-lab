<?php

namespace App\Application\Permission\UseCases;

use App\Domain\Permission\Entities\DeniedPermission;
use App\Domain\Permission\Repositories\DeniedPermissionRepositoryInterface;

class AssignDeniedPermissionUseCase
{
    public function __construct(
        private DeniedPermissionRepositoryInterface $repository
    ) {}

    public function execute(int $userId, int $permissionId): DeniedPermission
    {
        return $this->repository->assign($userId, $permissionId);
    }
}
