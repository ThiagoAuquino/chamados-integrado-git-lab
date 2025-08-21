<?php

namespace App\Domain\Usuario\UseCases;

use App\Domain\Usuario\DTOs\UpdateUserDTO;
use App\Domain\Usuario\Repositories\UserRepositoryInterface;

class UpdateUserUseCase
{
    public function __construct(private UserRepositoryInterface $repository) {}

    public function execute(int $id, UpdateUserDTO $dto)
    {
        return $this->repository->update($id, $dto);
    }

    public function assignPermissions(int $id, array $permissions)
    {
        return $this->repository->syncPermissions($id, $permissions);
    }
}
