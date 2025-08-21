<?php

namespace App\Domain\Role\Repositories;

use Illuminate\Support\Collection;

use App\Domain\Role\Entities\Role;
use App\Domain\Role\DTOs\CreateRoleDTO;
use App\Domain\Role\DTOs\UpdateRoleDTO;

interface RoleRepositoryInterface
{
    public function findById(int $id): ?Role;
    public function findByName(string $name): ?Role;
    public function create(CreateRoleDTO $dto): Role;
    public function update(int $id, UpdateRoleDTO $dto): Role;
    public function delete(int $id): bool;
    public function all(): array;
}