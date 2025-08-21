<?php

namespace App\Domain\Permission\Repositories;

use App\Domain\Permission\Entities\Permission;
use App\Domain\Permission\DTOs\CreatePermissionDTO;

interface PermissionRepositoryInterface
{
    public function findById(int $id): ?Permission;
    public function findByName(string $name): ?Permission;
    public function create(CreatePermissionDTO $dto): Permission;
    public function all(): array;
    public function findByUser(int $userId): array;
    public function findByRole(int $roleId): array;
}
