<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Domain\Demanda\Repositories\DemandaRepositoryInterface;
use App\Infrastructure\Persistence\DemandaRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Vincula interface à implementação
        $this->app->bind(DemandaRepositoryInterface::class, DemandaRepository::class);
    }
}
