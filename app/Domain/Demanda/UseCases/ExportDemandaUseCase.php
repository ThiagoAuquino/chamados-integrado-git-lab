<?php

namespace App\Domain\Demanda\UseCases;

use App\Domain\Demanda\Repositories\DemandaRepositoryInterface;

class ExportDemandaUseCase
{
    public function __construct(private DemandaRepositoryInterface $repository) {}

    public function execute(array $filters): array
    {
        return $this->repository->export($filters);
    }
}
