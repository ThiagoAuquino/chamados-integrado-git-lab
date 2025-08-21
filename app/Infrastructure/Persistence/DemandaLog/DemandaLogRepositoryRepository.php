<?php

namespace App\Infrastructure\Persistence\DemandaLog;

use App\Domain\DemandaLog\Repositories\DemandaLogRepositoryRepositoryInterface;
use App\Models\DemandaLog;
use Illuminate\Support\Collection;

class DemandaLogRepositoryRepository implements DemandaLogRepositoryRepositoryInterface
{
    protected $demandaLogModel;

    public function __construct(App\Models\DemandaLog $demandaLog)
    {
        $this->demandaLogModel = $demandaLog;
    }

    public function all(): Collection
    {
        return $this->demandaLogModel->all();
    }

    public function find(int $id): ?object
    {
        return $this->demandaLogModel->find($id);
    }

    public function create(array $data): object
    {
        return $this->demandaLogModel->create($data);
    }

    public function update(int $id, array $data): bool
    {
        $item = $this->find($id);
        if (!$item) {
            return false;
        }
        return $item->update($data);
    }

    public function delete(int $id): bool
    {
        $item = $this->find($id);
        if (!$item) {
            return false;
        }
        return $item->delete();
    }
}