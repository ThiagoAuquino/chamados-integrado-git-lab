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
}


 