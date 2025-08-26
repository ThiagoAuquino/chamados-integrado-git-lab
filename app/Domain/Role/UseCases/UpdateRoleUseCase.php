<?php

namespace App\Application\Role\UseCases;

use App\Domain\Role\Repositories\RoleRepositoryInterface;
use App\Domain\Role\DTOs\UpdateRoleDTO;

class UpdateRoleUseCase
{
    private RoleRepositoryInterface $repository;

    public function __construct(RoleRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function execute(int $id, UpdateRoleDTO $dto): bool
    {
        return $this->repository->update($id, $dto);
    }
}
