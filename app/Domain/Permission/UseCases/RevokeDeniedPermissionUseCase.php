<?php

namespace App\Application\Permission\UseCases;

use App\Domain\Permission\Repositories\DeniedPermissionRepositoryInterface;

class RevokeDeniedPermissionUseCase
{
    public function __construct(
        private DeniedPermissionRepositoryInterface $repository
    ) {}

    public function execute(int $userId, int $permissionId): bool
    {
        return $this->repository->revoke($userId, $permissionId);
    }
}
