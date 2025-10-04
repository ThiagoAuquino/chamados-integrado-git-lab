<?php

namespace App\Domain\Demanda\DTOs;

class CreateDemandaDTO
{
    public function __construct(
        public string $produto,
        public ?string $chamado,
        public string $descricao,
        public int $tipo_id, // Alterado para usar ID da tabela 'demanda_tipo'
        public string $data_previsao,
        public string $cliente,
        public ?int $responsavel_id = null,
        public int $status_id, // Alterado para usar ID da tabela 'demandas_status'
        public string $priority = 'verde',
        public int $order = 0,
        public ?string $observacao = null, // Observação única para criação inicial
        public ?string $job = null,
        public ?string $created_at = null, // Usado para o 'created_at'
    ) {
        // Validar status_id e tipo_id aqui
    }

    public static function fromArray(array $data): self
    {
        return new self(
            produto: $data['produto'],
            chamado: $data['chamado'] ?? null,
            descricao: $data['descricao'],
            tipo_id: $data['tipo_id'], // Alterado para tipo_id
            data_previsao: $data['data_previsao'],
            cliente: $data['cliente'],
            responsavel_id: $data['responsavel_id'] ?? null,
            status_id: $data['status_id'], // Alterado para status_id
            priority: $data['priority'] ?? 'verde',
            order: $data['order'] ?? 0,
            observacao: $data['observacao'] ?? null,
            job: $data['job'] ?? null,
            created_at: $data['created_at'] ?? null, // Usado para o 'created_at'
        );
    }

    public function toArray(): array
    {
        return [
            'produto'        => $this->produto,
            'chamado'        => $this->chamado,
            'descricao'      => $this->descricao,
            'tipo_id'        => $this->tipo_id, // Alterado para tipo_id
            'data_previsao'  => $this->data_previsao,
            'cliente'        => $this->cliente,
            'responsavel_id' => $this->responsavel_id,
            'status_id'      => $this->status_id, // Alterado para status_id
            'priority'       => $this->priority,
            'order'          => $this->order,
            'observacao'     => $this->observacao,
            'job'            => $this->job,
            'created_at'     => $this->created_at,
        ];
    }
}
