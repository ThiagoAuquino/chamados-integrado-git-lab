<?php

namespace App\Domain\Role\DTOs;

class CreateRoleDTO
{
    public string $name;
    public ?string $description;

    public function __construct(string $name, ?string $description = null)
    {
        $this->name = $name;
        $this->description = $description;
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'description' => $this->description,
        ];
    }
}


