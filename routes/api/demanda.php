<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\DemandaController;
use App\Http\Controllers\Api\DemandaImportController;

Route::prefix('demandas')->group(function () {
    Route::get('/', [DemandaController::class, 'index']);
    Route::get('/{id}', [DemandaController::class, 'show']);
    Route::post('/', [DemandaController::class, 'store']);
    Route::put('/{id}', [DemandaController::class, 'update']);
    Route::delete('/{id}', [DemandaController::class, 'destroy']);

    // Operações específicas
    Route::post('/{id}/approve', [DemandaController::class, 'approve']);
    Route::post('/{id}/change-status', [DemandaController::class, 'changeStatus']);

    // Estatísticas e métricas
    Route::get('stats', [DemandaController::class, 'stats']);
    Route::get('overview', [DemandaController::class, 'overview']);

    // Histórico e logs
    Route::get('{id}/history', [DemandaController::class, 'history']);
    Route::get('{id}/timeline', [DemandaController::class, 'timeline']);

    // Operações em lote
    Route::post('bulk-update', [DemandaController::class, 'bulkUpdate']);
    Route::post('bulk-assign', [DemandaController::class, 'bulkAssign']);
    Route::post('bulk-change-status', [DemandaController::class, 'bulkChangeStatus']);

    // Filtros e consultas específicas
    Route::get('pending', [DemandaController::class, 'pending']);
    Route::get('overdue', [DemandaController::class, 'overdue']);
    Route::get('by-user/{userId}', [DemandaController::class, 'byUser']);
    Route::get('by-status/{status}', [DemandaController::class, 'byStatus']);
    Route::get('by-priority/{priority}', [DemandaController::class, 'byPriority']);

    // Comentários e anexos
    Route::post('{id}/comments', [DemandaController::class, 'addComment']);
    Route::get('{id}/comments', [DemandaController::class, 'getComments']);
    Route::post('{id}/attachments', [DemandaController::class, 'addAttachment']);
    Route::get('{id}/attachments', [DemandaController::class, 'getAttachments']);

    // Priorização
    Route::post('update-priority', [DemandaController::class, 'updatePriority']);
    Route::post('reorder', [DemandaController::class, 'reorder']);

    // Notificações e lembretes
    Route::post('{id}/reminder', [DemandaController::class, 'setReminder']);
    Route::delete('{id}/reminder', [DemandaController::class, 'removeReminder']);

    // Exportação
    Route::get('export', [DemandaController::class, 'export']);
    Route::post('export-filtered', [DemandaController::class, 'exportFiltered']);

    // Validações específicas
    Route::post('validate-assignment', [DemandaController::class, 'validateAssignment']);
    Route::post('validate-status-change', [DemandaController::class, 'validateStatusChange']);

    Route::post('/import', DemandaImportController::class);

});
