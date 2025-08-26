<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Domain\Auth\Services\AuthServiceInterface;
use App\Domain\Auth\Services\AuthService; // Implemente essa classe
use App\Domain\Permission\Repositories\PermissionRepositoryInterface;
use App\Domain\Usuario\Repositories\UserRepositoryInterface;
use App\Infrastructure\Persistence\Permission\PermissionRepository;
use App\Infrastructure\Persistence\Usuario\UserRepository;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        'App\Models\Demanda\Demanda' => 'App\Policies\Demanda\DemandaPolicy',
    ];

    /**
     * Registra bindings no container.
     */
    public function register()
    {
        // Bind da interface do serviço de autenticação para a implementação concreta
        $this->app->bind(AuthServiceInterface::class, AuthService::class);

        // Bind dos repositórios para as implementações concretas
        $this->app->bind(PermissionRepositoryInterface::class, PermissionRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
    }


    /**
     * Método boot onde você registra policies e gates.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        Gate::define('manage-users', function ($user) {
            return app(AuthServiceInterface::class)->hasPermission($user->id, 'manage_users');
        });

        Gate::define('manage-roles', function ($user) {
            return app(AuthServiceInterface::class)->hasPermission($user->id, 'manage_roles');
        });

        Gate::define('view-dashboard', function ($user) {
            return app(AuthServiceInterface::class)->hasPermission($user->id, 'view_dashboard');
        });

        Gate::define('export-data', function ($user) {
            return app(AuthServiceInterface::class)->hasPermission($user->id, 'export_data');
        });

        Gate::define('bulk-operations', function ($user) {
            return app(AuthServiceInterface::class)->hasPermission($user->id, 'bulk_operations');
        });
    }
}
