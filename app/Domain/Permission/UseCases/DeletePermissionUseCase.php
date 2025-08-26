<?php

namespace App\Domain\Permission\UseCases;

use App\Domain\Permission\Repositories\PermissionRepositoryInterface;

class DeletePermissionUseCase
{
    public function __construct(
        private PermissionRepositoryInterface $repository
    ) {}

    public function execute(int $id): bool
    {
        return $this->repository->delete($id);
    }
}
