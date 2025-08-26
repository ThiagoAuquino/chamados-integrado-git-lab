<?php

namespace App\Domain\Demanda\UseCases;

use App\Domain\Demanda\Repositories\DemandaRepositoryInterface;

class GetDemandaUseCase
{
    public function __construct(private DemandaRepositoryInterface $repository) {}

    public function execute(int $id)
    {
        return $this->repository->find($id);
    }
}
