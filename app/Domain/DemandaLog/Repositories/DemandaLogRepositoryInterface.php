<?php

namespace App\Domain\DemandaLog\Repositories;

use App\Domain\DemandaLog\DTOs\CreateDemandaLogDTO;

interface DemandaLogRepositoryInterface
{
    public function create(CreateDemandaLogDTO $dto): DemandaLog;
    public function findByDemanda(int $demandaId): array;
    public function findByUser(int $userId): array;
}