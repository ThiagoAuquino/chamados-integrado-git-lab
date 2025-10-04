<?php

namespace App\Domain\DemandaLog\Repositories;

use App\Domain\DemandaLog\DTOs\CreateDemandaLogDTO;
use App\Domain\DemandaLog\Entities\DemandaLog as DemandaLogEntity;

interface DemandaLogRepositoryInterface
{
    public function create(CreateDemandaLogDTO $dto): DemandaLogEntity;
    public function findByDemanda(int $demandaId): array;
    public function findByUser(int $userId): array;
}