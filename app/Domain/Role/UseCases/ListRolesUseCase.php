<?php

namespace App\Application\Role\UseCases;

use App\Domain\Role\Repositories\RoleRepositoryInterface;
use App\Domain\Role\Entities\Role;

class ListRolesUseCase
{
    private RoleRepositoryInterface $repository;

    public function __construct(RoleRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @return Role[]
     */
    public function execute(): array
    {
        return $this->repository->all();
    }
}
