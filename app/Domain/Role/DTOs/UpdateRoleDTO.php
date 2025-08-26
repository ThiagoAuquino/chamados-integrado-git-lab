<?php

namespace App\Domain\Role\DTOs;

class UpdateRoleDTO
{
    public ?string $name;
    public ?string $description;

    public function __construct(?string $name = null, ?string $description = null)
    {
        $this->name = $name;
        $this->description = $description;
    }

    public function toArray(): array
    {
        $data = [];
        if ($this->name !== null) {
            $data['name'] = $this->name;
        }
        if ($this->description !== null) {
            $data['description'] = $this->description;
        }
        return $data;
    }
}