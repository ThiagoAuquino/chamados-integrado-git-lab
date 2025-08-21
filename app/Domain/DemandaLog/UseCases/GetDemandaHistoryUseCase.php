<?php

namespace App\Domain\DemandaLog\UseCases;

use App\Domain\DemandaLog\Repositories\DemandaLogRepositoryInterface;

class GetDemandaHistoryUseCase
{
    public function __construct(
        private DemandaLogRepositoryInterface $logRepository
    ) {}

    public function execute(int $demandaId): array
    {
        return $this->logRepository->findByDemanda($demandaId);
    }
}