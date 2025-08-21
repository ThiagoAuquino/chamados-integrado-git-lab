<?php

namespace App\Providers;

use App\Domain\Usuario\Repositories\UserRepositoryInterface;
use Illuminate\Support\ServiceProvider;


use App\Infrastructure\Persistence\Usuario\UserRepository;

class UserServiceProvider extends ServiceProvider
{

    public function register()
    {
        // Vincula interface à implementação
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
    }
    
}
