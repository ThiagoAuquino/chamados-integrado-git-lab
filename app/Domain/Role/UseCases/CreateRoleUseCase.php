<?php

namespace App\Application\Role\UseCases;

use App\Domain\Role\Repositories\RoleRepositoryInterface;
use App\Domain\Role\DTOs\CreateRoleDTO;
use App\Domain\Role\Entities\Role;

class CreateRoleUseCase
{
    private RoleRepositoryInterface $repository;

    public function __construct(RoleRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function execute(CreateRoleDTO $dto): Role
    {
        return $this->repository->create($dto);
    }
}
