<?php

namespace App\Domain\DemandaLog\UseCases;

use App\Domain\DemandaLog\Repositories\DemandaLogRepositoryInterface;
use App\Domain\DemandaLog\DTOs\CreateDemandaLogDTO;
use App\Domain\DemandaLog\Entities\DemandaLog as DemandaLogEntity;
use Illuminate\Support\Facades\Log;

class CreateLogUseCase
{
    public function __construct(
        private DemandaLogRepositoryInterface $logRepository
    ) {}

    public function execute(CreateDemandaLogDTO $dto): DemandaLogEntity
    {

        return $this->logRepository->create($dto);
    }
}
