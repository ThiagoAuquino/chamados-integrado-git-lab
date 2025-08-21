<?php

namespace App\Domain\Demanda\UseCases;

use App\Domain\Demanda\DTOs\CreateDemandaDTO;
use App\Domain\Demanda\Entities\Demanda;
use App\Domain\Demanda\Repositories\DemandaRepositoryInterface;

class CreateDemandaUseCase
{
    public function __construct(
        private DemandaRepositoryInterface $demandaRepository
    ) {}

    public function execute(CreateDemandaDTO $dto): Demanda
    {
        // Aqui você pode validar ou aplicar regras de negócio

        $data = [
            'produto' => $dto->produto,
            'chamado' => $dto->chamado,
            'descricao' => $dto->descricao,
            'tipo' => $dto->tipo,
            'data_previsao' => $dto->data_previsao,
            'cliente' => $dto->cliente,
            'responsavel_id' => $dto->responsavel_id,
            'status' => $dto->status,
            'prioridade' => $dto->prioridade,
        ];

        return $this->demandaRepository->create($data);
    }
}
