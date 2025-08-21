<?php

namespace App\Infrastructure\Persistence\Role;

use App\Domain\Role\Repositories\RoleRepositoryRepositoryInterface;
use App\Models\Role;
use Illuminate\Support\Collection;

class RoleRepositoryRepository implements RoleRepositoryRepositoryInterface
{
    protected $roleModel;

    public function __construct(App\Models\Role $role)
    {
        $this->roleModel = $role;
    }

    public function all(): Collection
    {
        return $this->roleModel->all();
    }

    public function find(int $id): ?object
    {
        return $this->roleModel->find($id);
    }

    public function create(array $data): object
    {
        return $this->roleModel->create($data);
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