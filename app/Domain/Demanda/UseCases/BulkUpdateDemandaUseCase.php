<?php

namespace App\Domain\Demanda\UseCases;

use App\Domain\Demanda\Repositories\DemandaRepositoryInterface;

class BulkUpdateDemandaUseCase
{
    public function __construct(private DemandaRepositoryInterface $repository) {}

    public function execute(array $ids, string $action, mixed $value, int $userId): array
    {
        return $this->repository->bulkUpdate($ids, $action, $value, $userId);
    }
}
