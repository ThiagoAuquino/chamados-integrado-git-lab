<?php

namespace App\Domain\Demanda\Repositories;

use App\Domain\Demanda\Entities\Demanda;
use Illuminate\Support\Collection;

interface DemandaRepositoryInterface
{
    public function all(): Collection;
    public function find(int $id): ?Demanda;
    public function create(array $data): Demanda;
    public function update(int $id, array $data): bool;
    public function delete(int $id): bool;
    public function getStats(): array;
    public function getHistory(int $demandaId): array;
    public function getPending(): Collection;
    public function getOverdue(): Collection;
    public function getByUser(int $userId): Collection;
    public function updatePriority(int $id, string $priority, int $order, int $userId): Demanda;
    public function addComment(int $demandaId, string $comment, int $userId): array;
    public function export(array $filters): array;
    public function bulkUpdate(array $ids, string $action, mixed $value, int $userId): array;
}
