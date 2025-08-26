<?php

use App\Http\Controllers\Api\DashboardController;
use Illuminate\Support\Facades\Route;



Route::middleware('auth:api')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    // Estatísticas gerais
    Route::get('dashboard/stats', [DashboardController::class, 'stats']);
    Route::get('dashboard/demandas-overview', [DashboardController::class, 'demandasOverview']);
    Route::get('dashboard/team-performance', [DashboardController::class, 'teamPerformance']);
    Route::get('dashboard/priority-distribution', [DashboardController::class, 'priorityDistribution']);
    
    // Dados para gráficos
    Route::get('dashboard/charts/status-timeline', [DashboardController::class, 'statusTimeline']);
    Route::get('dashboard/charts/completion-rate', [DashboardController::class, 'completionRate']);
    Route::get('dashboard/charts/workload-by-user', [DashboardController::class, 'workloadByUser']);
    
    // Alertas e notificações
    Route::get('dashboard/overdue-tasks', [DashboardController::class, 'overdueTasks']);
    Route::get('dashboard/pending-approvals', [DashboardController::class, 'pendingApprovals']);
    Route::get('dashboard/my-tasks-summary', [DashboardController::class, 'myTasksSummary']);
});