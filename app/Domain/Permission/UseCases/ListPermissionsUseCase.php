<?php

namespace App\Domain\Permission\UseCases;

use App\Domain\Permission\Repositories\PermissionRepositoryInterface;

class ListPermissionsUseCase
{
    public function __construct(
        private PermissionRepositoryInterface $repository
    ) {}

    public function execute(): array
    {
        return $this->repository->all();
    }
}
