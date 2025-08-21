<?php

namespace App\Domain\Demanda\UseCases;

use App\Domain\Demanda\DTOs\UpdateDemandaDTO;
use App\Domain\Demanda\Repositories\DemandaRepositoryInterface;

class UpdateDemandaUseCase
{
    public function __construct(
        private DemandaRepositoryInterface $demandaRepository
    ) {}

    public function execute(int $id, UpdateDemandaDTO $dto): bool
    {
        $data = array_filter([
            'produto' => $dto->produto,
            'descricao' => $dto->descricao,
            'tipo' => $dto->tipo,
            'data_previsao' => $dto->data_previsao,
            'cliente' => $dto->cliente,
            'responsavel_id' => $dto->responsavel_id,
            'status' => $dto->status,
            'prioridade' => $dto->prioridade,
        ], fn($value) => !is_null($value)); // Remove campos nulos

        return $this->demandaRepository->update($id, $data);
    }
}
