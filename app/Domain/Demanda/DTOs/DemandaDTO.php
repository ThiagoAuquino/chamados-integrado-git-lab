<?php

namespace App\Domain\Demanda\DTOs;

class CreateDemandaDTO
{
    public function __construct(
        public string $produto,
        public ?string $chamado,
        public string $descricao,
        public string $tipo,
        public string $data_previsao,
        public string $cliente,
        public ?int $responsavel_id,
        public string $status = 'em_branco',
        public string $prioridade = 'verde',
    ) {}
}
