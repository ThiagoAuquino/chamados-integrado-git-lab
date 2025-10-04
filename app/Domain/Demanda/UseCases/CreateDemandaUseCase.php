<?php

namespace App\Domain\Demanda\UseCases;

use App\Domain\Demanda\DTOs\CreateDemandaDTO;
use App\Domain\Demanda\Repositories\DemandaRepositoryInterface;
use App\Models\Demanda\DemandaStatus;
use App\Models\Demanda\DemandaTipo;
use Illuminate\Support\Facades\Log;

class CreateDemandaUseCase
{
    public function __construct(private DemandaRepositoryInterface $repository) {}

    public function execute(CreateDemandaDTO $dto)
    {
        // Buscar ID do tipo
        // Log::info(json_encode($dto));

        $data = [
            'produto'        => $dto->produto,
            'chamado'        => $dto->chamado,
            'descricao'      => $dto->descricao,
            'tipo_id'        => $dto->tipo_id,
            'data_previsao'  => $dto->data_previsao,
            'cliente'        => $dto->cliente,
            'responsavel_id' => $dto->responsavel_id,
            'status_id'      => $dto->status_id,
            'priority'       => $dto->priority,
        ];

        return $this->repository->create($data);
    }
}
