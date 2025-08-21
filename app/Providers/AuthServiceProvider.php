<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Domain\Auth\Services\AuthServiceInterface;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        'App\Models\Demanda\Demanda' => 'App\Policies\Demanda\DemandaPolicy',
    ];

    public function boot(): void
    {
        $this->registerPolicies();

        // Gates personalizados para controle granular
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
