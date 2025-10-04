<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Domain\Demanda\Repositories\DemandaRepositoryInterface;
use App\Infrastructure\Persistence\Demanda\DemandaRepository;
use App\Domain\DemandaLog\Repositories\DemandaLogRepositoryInterface;
use App\Infrastructure\Persistence\DemandaLog\DemandaLogRepository;


class RepositoryServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->app->bind(DemandaRepositoryInterface::class, DemandaRepository::class);
        $this->app->bind(DemandaLogRepositoryInterface::class, DemandaLogRepository::class);
    }
}
