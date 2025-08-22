<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\DemandaController;

Route::middleware(['auth', 'verified'])->prefix('demandas')->name('demandas.')->group(function () {
    
    // Rotas básicas (já definidas no resource)
    // Route::resource('/', DemandaController::class);
    
    // Rotas específicas para ações de demanda
    Route::get('pending', [DemandaController::class, 'pending'])->name('pending');
    Route::get('my-tasks', [DemandaController::class, 'myTasks'])->name('my-tasks');
    Route::get('overdue', [DemandaController::class, 'overdue'])->name('overdue');
    
    // Filtros e buscas
    Route::get('filter/{status}', [DemandaController::class, 'filterByStatus'])->name('filter.status');
    Route::get('search', [DemandaController::class, 'search'])->name('search');
    
    // Exportação
    Route::get('export', [DemandaController::class, 'export'])->name('export')
        ->middleware('can:export-data');
    
    // API endpoints para AJAX (consumindo API interna)
    Route::prefix('api')->name('api.')->group(function () {
        Route::get('{demanda}/history', [DemandaController::class, 'getHistory'])->name('history');
        Route::post('{demanda}/comment', [DemandaController::class, 'addComment'])->name('comment');
        Route::post('update-priority', [DemandaController::class, 'updatePriority'])->name('update-priority');
    });

    // Rotas para importação via formulário
    Route::get('importar', [DemandaController::class, 'importForm'])->name('import.form')
        ->middleware('can:import_demandas');
    Route::post('importar', [DemandaController::class, 'importProcess'])->name('import.process')
        ->middleware('can:import_demandas');
});