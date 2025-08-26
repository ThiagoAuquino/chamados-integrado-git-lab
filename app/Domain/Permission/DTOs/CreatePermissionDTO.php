<?php

namespace App\Domain\Permission\DTOs;

class CreatePermissionDTO
{
    public function __construct(
        public string $name,
        public string $display_name,
        public ?string $description = null,
        public string $category = 'general',
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            $data['name'],
            $data['display_name'],
            $data['description'] ?? null,
            $data['category'] ?? 'general',
        );
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'display_name' => $this->display_name,
            'description' => $this->description,
            'category' => $this->category,
        ];
    }
}
