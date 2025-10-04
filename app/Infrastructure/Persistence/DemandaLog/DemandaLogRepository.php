<?php

namespace App\Infrastructure\Persistence\DemandaLog;

use App\Domain\DemandaLog\Entities\DemandaLog as DemandaLogEntity;
use App\Domain\DemandaLog\Repositories\DemandaLogRepositoryInterface;
use App\Domain\DemandaLog\DTOs\CreateDemandaLogDTO;
use App\Models\DemandaLog as DemandaLogModel;
use Illuminate\Support\Collection;

class DemandaLogRepository implements DemandaLogRepositoryInterface
{
    protected DemandaLogModel $demandaLogModel;

    public function __construct(DemandaLogModel $demandaLog)
    {
        $this->demandaLogModel = $demandaLog;
    }

    public function all(): Collection
    {
        return $this->demandaLogModel->all()->map(fn ($item) => $this->mapToEntity($item));
    }

    public function find(int $id): ?DemandaLogEntity
    {
        $model = $this->demandaLogModel->find($id);
        return $model ? $this->mapToEntity($model) : null;
    }

    public function create(CreateDemandaLogDTO $dto): DemandaLogEntity
    {
        $model = $this->demandaLogModel->create($dto->toArray());
        return $this->mapToEntity($model);
    }

    public function update(int $id, array $data): bool
    {
        $model = $this->demandaLogModel->find($id);
        if (!$model) {
            return false;
        }

        return $model->update($data);
    }

    public function delete(int $id): bool
    {
        $model = $this->demandaLogModel->find($id);
        if (!$model) {
            return false;
        }

        return $model->delete();
    }

    public function findByDemanda(int $demandaId): array
    {
        return $this->demandaLogModel
            ->where('demanda_id', $demandaId)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(fn ($item) => $this->mapToEntity($item))
            ->toArray();
    }

    public function findByUser(int $userId): array
    {
        return $this->demandaLogModel
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(fn ($item) => $this->mapToEntity($item))
            ->toArray();
    }

    /**
     * Transforma um model Eloquent em uma entidade de domínio
     */
    protected function mapToEntity(DemandaLogModel $model): DemandaLogEntity
    {
        return new DemandaLogEntity(
            id: $model->id,
            demanda_id: $model->demanda_id,
            user_id: $model->user_id,
            action: $model->action,
            field_changed: $model->field_changed,
            old_value: $model->old_value,
            new_value: $model->new_value,
            description: $model->description,
            created_at: optional($model->created_at)->toDateTimeString() ?? now()->toDateTimeString(),
        );
    }
}
