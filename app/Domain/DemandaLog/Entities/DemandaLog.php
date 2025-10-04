<?php

namespace App\Domain\DemandaLog\Entities;

class DemandaLog
{
    public function __construct(
        public readonly ?int $id,
        public int $demanda_id,
        public int $user_id,
        public string $action,
        public ?string $field_changed,
        public ?string $old_value,
        public ?string $new_value,
        public ?string $description,
        public string $created_at,
    ) {}

    // Getters
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDemandaId(): int
    {
        return $this->demanda_id;
    }

    public function getUserId(): int
    {
        return $this->user_id;
    }

    public function getAction(): string
    {
        return $this->action;
    }

    public function getFieldChanged(): ?string
    {
        return $this->field_changed;
    }

    public function getOldValue(): ?string
    {
        return $this->old_value;
    }

    public function getNewValue(): ?string
    {
        return $this->new_value;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getCreatedAt(): string
    {
        return $this->created_at;
    }

    // Setters (caso precise modificar valores após criação)
    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

}
