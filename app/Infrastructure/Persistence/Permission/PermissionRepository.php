<?php

namespace App\Infrastructure\Persistence\Permission;

use App\Domain\Permission\Repositories\PermissionRepositoryInterface;
use App\Domain\Permission\DTOs\CreatePermissionDTO;
use App\Domain\Permission\Entities\Permission as PermissionEntity;
use App\Models\Permission;
use Illuminate\Support\Collection;

class PermissionRepository implements PermissionRepositoryInterface
{
    protected $permissionModel;

    public function __construct(Permission $permission)
    {
        $this->permissionModel = $permission;
    }

    public function all(): array
    {
        return $this->permissionModel
            ->all()
            ->map(fn($model) => PermissionEntity::fromModel($model))
            ->toArray();
    }

    public function findById(int $id): ?PermissionEntity
    {
        $model = $this->permissionModel->find($id);
        
        return $model ? PermissionEntity::fromModel($model) : null;
    }

    public function findByName(string $name): ?PermissionEntity
    {
        $model = $this->permissionModel->where('name', $name)->first();

        return $model ? PermissionEntity::fromModel($model) : null;
    }

    public function create(CreatePermissionDTO $dto): PermissionEntity
    {
        $model = $this->permissionModel->create($dto->toArray());

        return PermissionEntity::fromModel($model);
    }

    public function update(int $id, array $data): bool
    {
        $model = $this->permissionModel->find($id);

        if (!$model) {
            return false;
        }

        return $model->update($data);
    }

    public function delete(int $id): bool
    {
        $model = $this->permissionModel->find($id);

        if (!$model) {
            return false;
        }

        return (bool) $model->delete();
    }

    public function findByUser(int $userId): array
    {
        $permissions = $this->permissionModel
            ->whereHas('users', fn($q) => $q->where('users.id', $userId))
            ->get();

        return $permissions->map(fn($model) => PermissionEntity::fromModel($model))->toArray();
    }

    public function findByRole(int $roleId): array
    {
        $permissions = $this->permissionModel
            ->whereHas('roles', fn($q) => $q->where('roles.id', $roleId))
            ->get();

        return $permissions->map(fn($model) => PermissionEntity::fromModel($model))->toArray();
    }
}
