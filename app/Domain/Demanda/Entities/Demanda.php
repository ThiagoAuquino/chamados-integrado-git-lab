<?php

namespace App\Domain\Demanda\Entities;

class Demanda
{
    public function __construct(
        public readonly ?int $id,
        public string $produto,
        public ?string $chamado,
        public string $descricao,
        public string $tipo,
        public string $data_previsao,
        public string $cliente,
        public ?int $responsavel_id,
        public string $status,
        public string $prioridade,
        public string $created_at,
        public string $updated_at,
    ) {}
}
