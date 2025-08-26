<?php

namespace App\Domain\Permission\Entities;

class Permission
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

    public static function fromModel(\App\Models\Permission $model): self
    {
        return new self(
            id: $model->id,
            name: $model->name,
            display_name: $model->display_name,
            description: $model->description,
            category: $model->category,
            created_at: $model->created_at?->toDateTimeString(),
            updated_at: $model->updated_at?->toDateTimeString(),
        );
    }
}
