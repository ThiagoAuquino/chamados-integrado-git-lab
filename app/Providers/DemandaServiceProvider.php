<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;


use App\Domain\Demanda\Repositories\DemandaRepositoryInterface;
use App\Infrastructure\Persistence\DemandaRepository;
use App\Models\Demanda\Demanda;
use App\Policies\Demanda\DemandaPolicy;

class DemandaServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Bind da interface para o repositório concreto
        $this->app->bind(DemandaRepositoryInterface::class, DemandaRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Registro da policy para a model Eloquent
        Gate::policy(Demanda::class, DemandaPolicy::class);
    }
}
