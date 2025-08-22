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
        public ?int $responsavel_id = null,
        public string $status = 'em_branco',
        public string $prioridade = 'verde',
        public int $order = 0,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            produto: $data['produto'],
            chamado: $data['chamado'] ?? null,
            descricao: $data['descricao'],
            tipo: $data['tipo'],
            data_previsao: $data['data_previsao'],
            cliente: $data['cliente'],
            responsavel_id: $data['responsavel_id'] ?? null,
            status: $data['status'] ?? 'em_branco',
            prioridade: $data['prioridade'] ?? 'verde',
            order: $data['order'] ?? 0,
        );
    }

    public function toArray(): array
    {
        return [
            'produto'        => $this->produto,
            'chamado'        => $this->chamado,
            'descricao'      => $this->descricao,
            'tipo'           => $this->tipo,
            'data_previsao'  => $this->data_previsao,
            'cliente'        => $this->cliente,
            'responsavel_id' => $this->responsavel_id,
            'status'         => $this->status,
            'prioridade'     => $this->prioridade,
            'order'          => $this->order,
        ];
    }
}
