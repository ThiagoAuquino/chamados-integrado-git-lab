<?php

namespace App\Domain\Role\Repositories;

use App\Domain\Role\Entities\Role;
use App\Domain\Role\DTOs\CreateRoleDTO;
use App\Domain\Role\DTOs\UpdateRoleDTO;

interface RoleRepositoryInterface
{
    public function all(): array;

    public function findById(int $id): ?Role;

    public function findByName(string $name): ?Role;

    public function create(CreateRoleDTO $dto): Role;

    public function update(int $id, UpdateRoleDTO $dto): bool;

    public function delete(int $id): bool;
}
