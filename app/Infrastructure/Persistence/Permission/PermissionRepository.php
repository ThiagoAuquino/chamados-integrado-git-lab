<?php

namespace App\Infrastructure\Persistence\Permission;

use App\Domain\Permission\Repositories\PermissionRepositoryInterface;
use App\Models\Permission;
use Illuminate\Support\Collection;

class PermissionRepository implements PermissionRepositoryInterface
{
    protected $permissionModel;

    public function __construct(App\Models\Permission $permission)
    {
        $this->permissionModel = $permission;
    }

    public function all(): Collection
    {
        return $this->permissionModel->all();
    }

    public function find(int $id): ?object
    {
        return $this->permissionModel->find($id);
    }

    public function create(array $data): object
    {
        return $this->permissionModel->create($data);
    }

    public function update(int $id, array $data): bool
    {
        $item = $this->find($id);
        if (!$item) {
            return false;
        }
        return $item->update($data);
    }

    public function delete(int $id): bool
    {
        $item = $this->find($id);
        if (!$item) {
            return false;
        }
        return $item->delete();
    }
}