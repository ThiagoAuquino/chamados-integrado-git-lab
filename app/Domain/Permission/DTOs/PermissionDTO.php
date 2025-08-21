<?php

namespace App\Domain\Permission\DTOs;

class PermissionDTO
{
    public function __construct(
        // Adicione os atributos aqui
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            // mapeie os dados aqui
        );
    }

    public function toArray(): array
    {
        return [
            // converta os dados aqui
        ];
    }
}