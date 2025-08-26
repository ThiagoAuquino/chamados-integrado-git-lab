<?php

namespace App\Domain\Permission\UseCases;

use App\Domain\Permission\DTOs\CreatePermissionDTO;
use App\Domain\Permission\Repositories\PermissionRepositoryInterface;
use App\Domain\Permission\Entities\Permission;

class CreatePermissionUseCase
{
    public function __construct(
        private PermissionRepositoryInterface $repository
    ) {}

    public function execute(CreatePermissionDTO $dto): Permission
    {
        return $this->repository->create($dto);
    }
}
