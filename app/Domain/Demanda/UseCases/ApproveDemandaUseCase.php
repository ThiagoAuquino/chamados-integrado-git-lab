<?php

namespace App\Domain\Demanda\UseCases;

use App\Domain\Demanda\Repositories\DemandaRepositoryInterface;

class ApproveDemandaUseCase
{
    public function __construct(
        private DemandaRepositoryInterface $demandaRepository
    ) {}

    public function execute(int $id): bool
    {
        // Regra simples: para aprovar, status vai para 'aprovado'
        return $this->demandaRepository->update($id, ['status' => 'aprovado']);
    }
}
