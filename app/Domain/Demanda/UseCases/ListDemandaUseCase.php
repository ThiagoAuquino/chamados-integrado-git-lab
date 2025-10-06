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

    public function getStats(): array
    {
        return $this->demandaRepository->getStats();
    }
    public function getPending(): mixed
    {
        return $this->demandaRepository->getPending();
    }
    public function getOverdue(): mixed
    {
        return $this->demandaRepository->getOverdue();
    }
    public function getByUser(int $userId): mixed
    {
        return $this->demandaRepository->getByUser($userId);
    }
}
