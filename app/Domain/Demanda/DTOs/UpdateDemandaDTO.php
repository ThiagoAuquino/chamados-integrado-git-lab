<?php

namespace App\Domain\Demanda\DTOs;

class UpdateDemandaDTO
{
    public function __construct(
        public ?string $produto = null,
        public ?string $descricao = null,
        public ?string $tipo = null,
        public ?string $data_previsao = null,
        public ?string $cliente = null,
        public ?int $responsavel_id = null,
        public ?string $status = null,
        public ?string $prioridade = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            $data['produto'] ?? null,
            $data['descricao'] ?? null,
            $data['tipo'] ?? null,
            $data['data_previsao'] ?? null,
            $data['cliente'] ?? null,
            $data['responsavel_id'] ?? null,
            $data['status'] ?? null,
            $data['prioridade'] ?? null,
        );
    }

    public function toArray(): array
    {
        return [
            'produto'         => $this->produto,
            'descricao'       => $this->descricao,
            'tipo'            => $this->tipo,
            'data_previsao'   => $this->data_previsao,
            'cliente'         => $this->cliente,
            'responsavel_id'  => $this->responsavel_id,
            'status'          => $this->status,
            'prioridade'      => $this->prioridade,
        ];
    }
}
