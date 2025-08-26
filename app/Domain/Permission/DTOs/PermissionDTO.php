<?php

namespace App\Domain\Permission\DTOs;

class PermissionDTO
{
    public function __construct(
        public int $id,
        public string $name,
        public string $display_name,
        public ?string $description,
        public string $category,
        public string $created_at,
        public string $updated_at,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            $data['id'],
            $data['name'],
            $data['display_name'],
            $data['description'],
            $data['category'],
            $data['created_at'],
            $data['updated_at'],
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'display_name' => $this->display_name,
            'description' => $this->description,
            'category' => $this->category,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
