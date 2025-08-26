<?php

namespace App\Domain\Role\Entities;

use App\Models\Role as RoleModel;

class Role
{
    public int $id;
    public string $name;
    public ?string $description;

    public function __construct(int $id, string $name, ?string $description = null)
    {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
    }

    public static function fromModel(RoleModel $model): self
    {
        return new self(
            $model->id,
            $model->name,
            $model->description
        );
    }
}
