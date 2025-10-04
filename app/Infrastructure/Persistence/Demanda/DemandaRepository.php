<?php

namespace App\Infrastructure\Persistence\Demanda;

use App\Domain\Demanda\Repositories\DemandaRepositoryInterface;
use App\Domain\DemandaLog\Repositories\DemandaLogRepositoryInterface;
use App\Domain\Demanda\Entities\Demanda as DemandaEntity;
use App\Domain\DemandaLog\DTOs\CreateDemandaLogDTO;
use App\Models\Demanda\Demanda as DemandaModel;
use App\Models\Demanda\DemandaStatus;
use App\Models\Demanda\DemandaTipo;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DemandaRepository implements DemandaRepositoryInterface
{
    protected DemandaModel $model;
    protected DemandaLogRepositoryInterface $logRepository;

    public function __construct(DemandaModel $model, DemandaLogRepositoryInterface $logRepository)
    {
        $this->model = $model;
        $this->logRepository = $logRepository;
    }

    public function all(): Collection
    {
        return $this->model
            ->with(['tipo', 'status'])
            ->get()
            ->map(fn($item) => $this->mapToEntity($item));
    }

    public function find(int $id): ?DemandaEntity
    {
        $item = $this->model
            ->with(['tipo', 'status'])
            ->find($id);

        return $item ? $this->mapToEntity($item) : null;
    }

    public function create(array $data): DemandaEntity
    {

        // Cria o registro
        $item = $this->model->create($data);

        // Recarregar com relacionamentos para montar a entidade corretamente
        $item = $item->fresh(['tipo', 'status']);

        return $this->mapToEntity($item);
    }

    public function update(int $id, array $data): bool
    {
        $item = $this->model->find($id);
        if (!$item) {
            return false;
        }

        $original = $item->replicate();

        // Para casos em que o DTO ou chamada possam ainda passar 'tipo' ou 'status'
        if (isset($data['tipo'])) {
            $tipoModel = DemandaTipo::where('tipo', $data['tipo'])->first();
            $data['tipo_id'] = $tipoModel ? $tipoModel->id : null;
            unset($data['tipo']);
        }
        if (isset($data['status'])) {
            $statusModel = DemandaStatus::where('status', $data['status'])->first();
            $data['status_id'] = $statusModel ? $statusModel->id : null;
            unset($data['status']);
        }

        $updated = $item->update($data);

        if ($updated && Auth::check()) {
            foreach ($data as $key => $newValue) {
                $oldValue = $original->$key ?? null;
                if ($oldValue != $newValue) {
                    $this->logRepository->create(
                    CreateDemandaLogDTO::fromArray([
                            'demanda_id'    => $item->id,
                            'user_id'       => Auth::id(),
                            'action'        => 'update',
                            'field_changed' => $key,
                            'old_value'     => (string) $oldValue,
                            'new_value'     => (string) $newValue,
                            'description'   => "Campo '{$key}' alterado",
                            'created_at'    => now()->toDateTimeString(),
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

    public function getPending(): Collection
    {
        // Filtrar por status “em_branco” via relacionamento status
        return $this->model
            ->with(['tipo', 'status'])
            ->whereHas('status', fn($q) => $q->where('status', 'em_branco'))
            ->get()
            ->map(fn($item) => $this->mapToEntity($item));
    }

    public function getOverdue(): Collection
    {
        return $this->model
            ->with(['tipo', 'status'])
            ->where('data_previsao', '<', now())
            ->whereHas('status', fn($q) => $q->where('status', '!=', 'entregue'))
            ->get()
            ->map(fn($item) => $this->mapToEntity($item));
    }

    // outros métodos que usam findByUser, getStats etc, adaptando semelhante a above

    public function getByUser(int $userId): Collection
    {
        return $this->model
            ->with(['tipo', 'status'])
            ->where('responsavel_id', $userId)
            ->get()
            ->map(fn($item) => $this->mapToEntity($item));
    }

    public function getStats(): array
    {
        // Esse método retorna estatísticas por nome de status
        $rows = $this->model
            ->select('status_id', DB::raw('count(*) as count'))
            ->groupBy('status_id')
            ->get();

        $result = [];
        foreach ($rows as $row) {
            $status = DemandaStatus::find($row->status_id);
            $result[$status->status] = $row->count;
        }

        return $result;
    }

    public function updatePriority(int $id, string $priority, int $order, int $userId): DemandaEntity
    {
        $item = $this->model->findOrFail($id);
        $item->prioridade = $priority;
        $item->order = $order;
        $item->save();

        $item = $item->fresh(['tipo', 'status']);

        $this->logRepository->create(
        CreateDemandaLogDTO::fromArray([
                'demanda_id'    => $item->id,
                'user_id'       => $userId,
                'action'        => 'update_priority',
                'field_changed' => 'priority',
                'old_value'     => (string) $item->getOriginal('prioridade'),
                'new_value'     => $priority,
                'description'   => "Prioridade alterada",
                'created_at'    => now()->toDateTimeString(),
            ])
        );

        return $this->mapToEntity($item);
    }

        public function getHistory(int $demandaId): array
    {
        return $this->logRepository->findByDemanda($demandaId);
    }

        public function addComment(int $id, string $comment, int $userId): array
    {
        $model = $this->model->findOrFail($id);
        $commentData = $model->comments()->create(['user_id' => $userId, 'comment' => $comment]);
        return $commentData->toArray();
    }

        public function export(array $filters): array
    {
        $query = $this->model->query();
        foreach ($filters as $f => $v) {
            $query->where($f, $v);
        }
        return $query->get()->toArray();
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

    // Função auxiliar para mapear modelo Eloquent para DemandaEntity
    protected function mapToEntity(DemandaModel $item): DemandaEntity
    {

        return new DemandaEntity(
            $item->id,
            $item->produto,
            $item->chamado,
            $item->descricao,
            $item->tipo?->tipo ?? '',
            $item->data_previsao->format('Y-m-d'),
            $item->cliente,
            $item->responsavel_id,
            $item->status?->status ?? '',
            $item->priority,
            $item->created_at->toDateTimeString(),
            $item->updated_at->toDateTimeString()
        );
    }
}
