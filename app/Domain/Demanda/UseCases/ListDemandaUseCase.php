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
        return $this->repository->getStats();
    }
    public function getPending(): mixed
    {
        return $this->repository->getPending();
    }
    public function getOverdue(): mixed
    {
        return $this->repository->getOverdue();
    }
    public function getByUser(int $userId): mixed
    {
        return $this->repository->getByUser($userId);
    }
}
