<?php

namespace App\Domain\Permission\UseCases;

use App\Domain\Permission\Repositories\PermissionRepositoryInterface;
use App\Domain\Permission\Entities\Permission;

class GetPermissionByIdUseCase
{
    public function __construct(
        private PermissionRepositoryInterface $repository
    ) {}

    public function execute(int $id): ?Permission
    {
        return $this->repository->findById($id);
    }
}
