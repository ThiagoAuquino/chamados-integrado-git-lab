<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Auth\Events\Login;
use App\Listeners\UpdateLastLoginAt;

class EventServiceProvider extends ServiceProvider
{
    /**
     * Os eventos a serem ouvidos pela aplicação.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Login::class => [
            UpdateLastLoginAt::class,
        ],
    ];

    public function boot(): void
    {
        //
    }
}
