<?php

namespace App\Infrastructure\Persistence;

use App\Domain\Demanda\Repositories\DemandaRepositoryInterface;
use App\Domain\Demanda\Entities\Demanda as DemandaEntity;
use App\Models\Demanda\Demanda;
use Illuminate\Support\Collection;

class DemandaRepository implements DemandaRepositoryInterface
{
    protected Demanda $model;

    public function __construct(Demanda $model)
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
        return $item->update($data);
    }

    public function delete(int $id): bool
    {
        $item = $this->model->find($id);
        if (!$item) {
            return false;
        }
        return $item->delete();
    }
}
