<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;




return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: null,
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
        then: function () {
            // Carrega rotas API por módulo
            $modules = [
                'demanda',
                'usuario',
                'status',
                'notificacao',
                'lembrete',
            ];

            foreach ($modules as $module) {
                $path = base_path("routes/api/{$module}.php");

                if (file_exists($path)) {
                    Route::middleware('api')
                        ->prefix('api') // somente 'api', já que os arquivos têm seus próprios prefixos
                        ->group($path);
                }
            }
        }
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
