<?php

namespace App\Application\Role\UseCases;

use App\Domain\Role\Repositories\RoleRepositoryInterface;
use App\Domain\Role\Entities\Role;

class FindRoleByIdUseCase
{
    private RoleRepositoryInterface $roleRepository;

    public function __construct(RoleRepositoryInterface $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }

    public function execute(int $id): ?Role
    {
        return $this->roleRepository->findById($id);
    }
}
