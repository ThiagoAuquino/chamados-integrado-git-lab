<?php

namespace App\Domain\DemandaLog\DTOs;

class DemandaLogDTO
{
    public function __construct(
        public int $id,
        public int $demanda_id,
        public int $user_id,
        public string $action,
        public ?string $field_changed,
        public ?string $old_value,
        public ?string $new_value,
        public ?string $description,
        public string $created_at
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'],
            demanda_id: $data['demanda_id'],
            user_id: $data['user_id'],
            action: $data['action'],
            field_changed: $data['field_changed'] ?? null,
            old_value: $data['old_value'] ?? null,
            new_value: $data['new_value'] ?? null,
            description: $data['description'] ?? null,
            created_at: $data['created_at'],
        );
    }

    public function toArray(): array
    {
        return [
            'id'            => $this->id,
            'demanda_id'    => $this->demanda_id,
            'user_id'       => $this->user_id,
            'action'        => $this->action,
            'field_changed' => $this->field_changed,
            'old_value'     => $this->old_value,
            'new_value'     => $this->new_value,
            'description'   => $this->description,
            'created_at'    => $this->created_at,
        ];
    }
}
