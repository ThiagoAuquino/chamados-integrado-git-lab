<?php

namespace App\Domain\Permission\UseCases;

use App\Domain\Permission\DTOs\PermissionDTO;
use App\Domain\Permission\Repositories\PermissionRepositoryInterface;

class UpdatePermissionUseCase
{
    public function __construct(
        private PermissionRepositoryInterface $repository
    ) {}

    public function execute(int $id, PermissionDTO $dto): bool
    {
        return $this->repository->update($id, $dto->toArray());
    }
}
