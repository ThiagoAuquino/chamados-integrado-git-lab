<?php

namespace App\Domain\Permission\Repositories;

use App\Domain\Permission\Entities\Permission;
use App\Domain\Permission\DTOs\CreatePermissionDTO;
use App\Domain\Permission\DTOs\PermissionDTO;

interface PermissionRepositoryInterface
{
    public function findById(int $id): ?Permission;
    public function findByName(string $name): ?Permission;
    public function create(CreatePermissionDTO $dto): Permission;
    public function update(int $id, array $data): bool;
    public function delete(int $id): bool;
    public function all(): array;
    public function findByUser(int $userId): array;
    public function findByRole(int $roleId): array;
}
