<?php

namespace App\Application\Role\UseCases;

use App\Domain\Role\Repositories\RoleRepositoryInterface;
use App\Domain\Role\Entities\Role;

class FindRoleByNameUseCase
{
    private RoleRepositoryInterface $roleRepository;

    public function __construct(RoleRepositoryInterface $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }

    public function execute(string $name): ?Role
    {
        return $this->roleRepository->findByName($name);
    }
}
