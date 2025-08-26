<?php

namespace App\Domain\Demanda\UseCases;

use App\Domain\Demanda\Entities\Demanda;
use App\Domain\Demanda\Repositories\DemandaRepositoryInterface;


class UpdatePriorityUseCase
{
    public function __construct(private DemandaRepositoryInterface $repository) {}

    public function execute(int $id, string $priority, int $order, int $userId): Demanda
    {
        return $this->repository->updatePriority($id, $priority, $order, $userId);
    }
}
