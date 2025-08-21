<?php

namespace App\Domain\Demanda\UseCases;

use App\Domain\Demanda\Repositories\DemandaRepositoryInterface;
use App\Domain\Demanda\Entities\Demanda;

class ShowDemandaUseCase
{
    public function __construct(
        private DemandaRepositoryInterface $demandaRepository
    ) {}

    public function execute(int $id): ?Demanda
    {
        return $this->demandaRepository->find($id);
    }
}
