<?php

namespace App\Domain\Demanda\UseCases;

use App\Domain\Demanda\Repositories\DemandaRepositoryInterface;
use Illuminate\Support\Collection;

class ListDemandaUseCase
{
    public function __construct(
        private DemandaRepositoryInterface $demandaRepository
    ) {}

    public function execute(): Collection
    {
        return $this->demandaRepository->all();
    }
}
