<?php

namespace App\Domain\Demanda\UseCases;

use App\Domain\Demanda\Repositories\DemandaRepositoryInterface;

class GetAllDemandasUseCase
{
    public function __construct(private DemandaRepositoryInterface $repository) {}

    public function execute()
    {
        return $this->repository->all();
    }
}
