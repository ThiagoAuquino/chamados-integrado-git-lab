<?php

use App\Http\Controllers\Api\PermissionController;
use App\Http\Controllers\Api\RoleController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:api')->group(function () {
    // Permissões
    Route::get('permissions', [PermissionController::class, 'index']);
    Route::get('permissions/{id}', [PermissionController::class, 'show']);
    Route::post('permissions', [PermissionController::class, 'store']);
    Route::put('permissions/{id}', [PermissionController::class, 'update']);
    Route::delete('permissions/{id}', [PermissionController::class, 'destroy']);
    
    // Roles
    Route::get('roles', [RoleController::class, 'index']);
    Route::get('roles/{id}', [RoleController::class, 'show']);
    Route::post('roles', [RoleController::class, 'store']);
    Route::put('roles/{id}', [RoleController::class, 'update']);
    Route::delete('roles/{id}', [RoleController::class, 'destroy']);
    
    // Relacionamentos Role-Permission
    Route::get('roles/{id}/permissions', [RoleController::class, 'permissions']);
    Route::post('roles/{id}/assign-permission', [RoleController::class, 'assignPermission']);
    Route::delete('roles/{id}/remove-permission/{permissionId}', [RoleController::class, 'removePermission']);
    
    // Verificações de permissão
    Route::post('check-permission', [PermissionController::class, 'checkPermission']);
    Route::get('user-permissions/{userId}', [PermissionController::class, 'userPermissions']);
});