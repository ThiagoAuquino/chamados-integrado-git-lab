<?php

namespace App\Infrastructure\Persistence;

use App\Domain\Demanda\Repositories\DemandaRepositoryInterface;
use App\Domain\DemandaLog\Repositories\DemandaLogRepositoryInterface;

use App\Domain\Demanda\Entities\Demanda as DemandaEntity;
use App\Models\Demanda\Demanda;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class DemandaRepository implements DemandaRepositoryInterface
{
    protected Demanda $model;

    public function __construct(Demanda $model, private DemandaLogRepositoryInterface $logRepository)
    {
        $this->model = $model;
    }

    public function all(): Collection
    {
        // Retorna coleção de entidades Demanda, convertendo do modelo Eloquent
        return $this->model->all()->map(function ($item) {
            return new DemandaEntity(
                $item->id,
                $item->produto,
                $item->chamado,
                $item->descricao,
                $item->tipo,
                $item->data_previsao,
                $item->cliente,
                $item->responsavel_id,
                $item->status,
                $item->prioridade,
                $item->created_at->toDateTimeString(),
                $item->updated_at->toDateTimeString(),
            );
        });
    }

    public function find(int $id): ?DemandaEntity
    {
        $item = $this->model->find($id);
        if (!$item) {
            return null;
        }

        return new DemandaEntity(
            $item->id,
            $item->produto,
            $item->chamado,
            $item->descricao,
            $item->tipo,
            $item->data_previsao,
            $item->cliente,
            $item->responsavel_id,
            $item->status,
            $item->prioridade,
            $item->created_at->toDateTimeString(),
            $item->updated_at->toDateTimeString(),
        );
    }

    public function create(array $data): DemandaEntity
    {
        $item = $this->model->create($data);

        return new DemandaEntity(
            $item->id,
            $item->produto,
            $item->chamado,
            $item->descricao,
            $item->tipo,
            $item->data_previsao,
            $item->cliente,
            $item->responsavel_id,
            $item->status,
            $item->prioridade,
            $item->created_at->toDateTimeString(),
            $item->updated_at->toDateTimeString(),
        );
    }

    public function update(int $id, array $data): bool
    {
        $item = $this->model->find($id);
        if (!$item) {
            return false;
        }

        $original = $item->replicate();

        $updated = $item->update($data);

        if ($updated && auth()->check()) {
            foreach ($data as $key => $newValue) {
                $oldValue = $original->$key ?? null;
                if ($oldValue != $newValue) {
                    $this->logRepository->create(
                        \App\Domain\DemandaLog\DTOs\CreateDemandaLogDTO::fromArray([
                            'demanda_id'    => $item->id,
                            'user_id'       => auth()->id(),
                            'action'        => 'update',
                            'field_changed' => $key,
                            'old_value'     => (string) $oldValue,
                            'new_value'     => (string) $newValue,
                            'description'   => "Campo '{$key}' alterado",
                        ])
                    );
                }
            }
        }

        return $updated;
    }


    public function delete(int $id): bool
    {
        $item = $this->model->find($id);
        if (!$item) {
            return false;
        }
        return $item->delete();
    }

    public function getHistory(int $demandaId): array
    {
        return $this->logRepository->findByDemanda($demandaId);
    }


    public function getStats(): array
    {
        return $this->model->select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();
    }

    public function getPending(): Collection
    {
        return $this->model->where('status', 'em_branco')->get()->map(function ($item) {
            return new DemandaEntity(
                $item->id,
                $item->produto,
                $item->chamado,
                $item->descricao,
                $item->tipo,
                $item->data_previsao,
                $item->cliente,
                $item->responsavel_id,
                $item->status,
                $item->prioridade,
                $item->created_at->toDateTimeString(),
                $item->updated_at->toDateTimeString(),
            );
        });
    }

    public function getOverdue(): Collection
    {
        return $this->model
            ->where('data_previsao', '<', now())
            ->where('status', '!=', 'entregue')
            ->get()
            ->map(function ($item) {
                return new DemandaEntity(
                    $item->id,
                    $item->produto,
                    $item->chamado,
                    $item->descricao,
                    $item->tipo,
                    $item->data_previsao,
                    $item->cliente,
                    $item->responsavel_id,
                    $item->status,
                    $item->prioridade,
                    $item->created_at->toDateTimeString(),
                    $item->updated_at->toDateTimeString(),
                );
            });
    }

    public function getByUser(int $userId): Collection
    {
        return $this->model->where('responsavel_id', $userId)->get()->map(function ($item) {
            return new DemandaEntity(
                $item->id,
                $item->produto,
                $item->chamado,
                $item->descricao,
                $item->tipo,
                $item->data_previsao,
                $item->cliente,
                $item->responsavel_id,
                $item->status,
                $item->prioridade,
                $item->created_at->toDateTimeString(),
                $item->updated_at->toDateTimeString(),
            );
        });
    }

    public function updatePriority(int $id, string $priority, int $order, int $userId): DemandaEntity
    {
        $item = $this->model->findOrFail($id);
        $item->prioridade = $priority;
        $item->order = $order;
        $item->save();

        $this->logRepository->create(
            \App\Domain\DemandaLog\DTOs\CreateDemandaLogDTO::fromArray([
                'demanda_id'    => $item->id,
                'user_id'       => $userId,
                'action'        => 'update_priority',
                'field_changed' => 'priority',
                'old_value'     => (string) $item->getOriginal('priority'),
                'new_value'     => (string) $priority,
                'description'   => "Prioridade alterada",
            ])
        );


        return new DemandaEntity(
            $item->id,
            $item->produto,
            $item->chamado,
            $item->descricao,
            $item->tipo,
            $item->data_previsao,
            $item->cliente,
            $item->responsavel_id,
            $item->status,
            $item->prioridade,
            $item->created_at->toDateTimeString(),
            $item->updated_at->toDateTimeString(),
        );
    }

    public function addComment(int $id, string $comment, int $userId): array
    {
        $model = $this->model->findOrFail($id);
        $commentData = $model->comments()->create(['user_id' => $userId, 'comment' => $comment]);
        return $commentData->toArray();
    }

    public function bulkUpdate(array $ids, string $action, mixed $value, int $userId): array
    {
        $results = [];
        foreach ($ids as $id) {
            try {
                switch ($action) {
                    case 'assign':
                        $this->update($id, ['responsavel_id' => $value]);
                        break;
                    case 'change_status':
                        $this->update($id, $value);
                        break;
                    case 'update_priority':
                        $this->updatePriority($id, $value['priority'], $value['order'], $userId);
                        break;
                    default:
                        throw new \Exception("Ação inválida: $action");
                }
                $results[$id] = ['success' => true];
            } catch (\Throwable $e) {
                $results[$id] = ['success' => false, 'error' => $e->getMessage()];
            }
        }
        return $results;
    }



    public function export(array $filters): array
    {
        $query = $this->model->query();
        foreach ($filters as $f => $v) {
            $query->where($f, $v);
        }
        return $query->get()->toArray();
    }
}
