<?php

namespace App\Domain\Demanda\UseCases;

use App\Domain\Demanda\Repositories\DemandaRepositoryInterface;

class ChangeStatusDemandaUseCase
{
    public function __construct(
        private DemandaRepositoryInterface $demandaRepository
    ) {}

    public function execute(int $id, string $novoStatus): bool
    {
        return $this->demandaRepository->update($id, ['status' => $novoStatus]);
    }
}
