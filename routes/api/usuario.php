
<?php

// routes/api/usuario.php

use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

Route::post('/login', [AuthenticatedSessionController::class, 'store']);


Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy']);
    // CRUD básico de usuários
    Route::get('users', [UserController::class, 'index']);
    Route::get('users/{id}', [UserController::class, 'show']);
    Route::post('users', [UserController::class, 'store']);
    Route::put('users/{id}', [UserController::class, 'update']);
    Route::delete('users/{id}', [UserController::class, 'destroy']);
    
    // Operações específicas de usuário
    Route::get('users/{id}/permissions', [UserController::class, 'permissions']);
    Route::get('users/{id}/roles', [UserController::class, 'roles']);
    Route::post('users/{id}/assign-role', [UserController::class, 'assignRole']);
    Route::delete('users/{id}/remove-role/{roleId}', [UserController::class, 'removeRole']);
    Route::post('users/{id}/assign-permission', [UserController::class, 'assignPermission']);
    Route::delete('users/{id}/remove-permission/{permissionId}', [UserController::class, 'removePermission']);
    
    // Usuário autenticado
    Route::get('user/profile', [UserController::class, 'profile']);
    Route::put('user/profile', [UserController::class, 'updateProfile']);
});

