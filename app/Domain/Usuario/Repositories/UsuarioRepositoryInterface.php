<?php

namespace App\Domain\Usuario\Repositories;

use App\Domain\Usuario\DTOs\CreateUserDTO;
use App\Domain\Usuario\DTOs\UpdateUserDTO;
use App\Domain\Usuario\Entities\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface UserRepositoryInterface
{
    public function findById(int $id): ?User;
    public function findByEmail(string $email): ?User;
    public function create(CreateUserDTO $dto): User;
    public function update(int $id, UpdateUserDTO $dto): User;
    public function delete(int $id): bool;
    public function paginate(int $perPage = 15): LengthAwarePaginator;
    public function findByRole(string $role): array;
    public function syncPermissions(int $userId, array $permissions): User;
    public function bulkAction(array $userIds, string $action): array;

}



