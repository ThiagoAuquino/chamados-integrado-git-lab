<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\UserController;

Route::middleware(['auth', 'verified', 'can:manage-users'])->prefix('users')->name('users.')->group(function () {
    Route::get('/', [UserController::class, 'index'])->name('index');
    Route::get('/create', [UserController::class, 'create'])->name('create');
    Route::post('/', [UserController::class, 'store'])->name('store');
    Route::get('/{id}', [UserController::class, 'show'])->name('show');
    Route::get('/{id}/edit', [UserController::class, 'edit'])->name('edit');
    Route::put('/{id}', [UserController::class, 'update'])->name('update');
    Route::delete('/{id}', [UserController::class, 'destroy'])->name('destroy');
    
    // Gerenciamento de permissões
    Route::get('/{id}/permissions', [UserController::class, 'permissions'])->name('permissions');
    Route::post('/{id}/permissions', [UserController::class, 'updatePermissions'])->name('permissions.update');
    
    // Operações em lote
    Route::post('/bulk-action', [UserController::class, 'bulkAction'])->name('bulk-action');
});

