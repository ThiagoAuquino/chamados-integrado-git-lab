@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('breadcrumbs')
    <li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('content')
    <!-- Info boxes -->
    <div class="row">
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
                <span class="info-box-icon bg-info elevation-1">
                    <i class="fas fa-clipboard-list"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">Total de Demandas</span>
                    <span class="info-box-number">{{ $statistics['total_demandas'] ?? 0 }}</span>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
                <span class="info-box-icon bg-warning elevation-1">
                    <i class="fas fa-hourglass-half"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">Em Andamento</span>
                    <span class="info-box-number">{{ $statistics['em_andamento'] ?? 0 }}</span>
                </div>
            </div>
        </div>

        <div class="clearfix hidden-md-up"></div>

        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
                <span class="info-box-icon bg-success elevation-1">
                    <i class="fas fa-check-circle"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">Concluídas</span>
                    <span class="info-box-number">{{ $statistics['concluidas'] ?? 0 }}</span>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
                <span class="info-box-icon bg-danger elevation-1">
                    <i class="fas fa-exclamation-triangle"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">Alta Prioridade</span>
                    <span class="info-box-number">{{ $statistics['alta_prioridade'] ?? 0 }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Kanban Board -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-columns mr-2"></i>
                        Kanban - Gestão de Demandas
                    </h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                        <a href="{{ route('demandas.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus mr-1"></i> Nova Demanda
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="kanban-board" style="overflow-x: auto;">
                        <div class="d-flex" style="min-width: 1200px; padding: 20px;">
                            
                            <!-- Coluna: Backlog -->
                            <div class="kanban-column" style="flex: 1;">
                                <div class="kanban-header bg-secondary text-white p-3 mb-3 rounded">
                                    <h5 class="mb-0">
                                        <i class="fas fa-inbox mr-2"></i>
                                        Backlog
                                        <span class="badge badge-light ml-2">{{ count($demandas['backlog'] ?? []) }}</span>
                                    </h5>
                                </div>
                                <div class="kanban-cards" data-status="backlog">
                                    @foreach(($demandas['backlog'] ?? []) as $demanda)
                                        @include('components.kanban-card', ['demanda' => $demanda])
                                    @endforeach
                                </div>
                            </div>

                            <!-- Coluna: Em Análise -->
                            <div class="kanban-column" style="flex: 1;">
                                <div class="kanban-header bg-info text-white p-3 mb-3 rounded">
                                    <h5 class="mb-0">
                                        <i class="fas fa-search mr-2"></i>
                                        Em Análise
                                        <span class="badge badge-light ml-2">{{ count($demandas['analise'] ?? []) }}</span>
                                    </h5>
                                </div>
                                <div class="kanban-cards" data-status="analise">
                                    @foreach(($demandas['analise'] ?? []) as $demanda)
                                        @include('components.kanban-card', ['demanda' => $demanda])
                                    @endforeach
                                </div>
                            </div>

                            <!-- Coluna: Em Desenvolvimento -->
                            <div class="kanban-column" style="flex: 1;">
                                <div class="kanban-header bg-warning text-dark p-3 mb-3 rounded">
                                    <h5 class="mb-0">
                                        <i class="fas fa-code mr-2"></i>
                                        Desenvolvimento
                                        <span class="badge badge-dark ml-2">{{ count($demandas['desenvolvimento'] ?? []) }}</span>
                                    </h5>
                                </div>
                                <div class="kanban-cards" data-status="desenvolvimento">
                                    @foreach(($demandas['desenvolvimento'] ?? []) as $demanda)
                                        @include('components.kanban-card', ['demanda' => $demanda])
                                    @endforeach
                                </div>
                            </div>

                            <!-- Coluna: Em Teste -->
                            <div class="kanban-column" style="flex: 1;">
                                <div class="kanban-header bg-primary text-white p-3 mb-3 rounded">
                                    <h5 class="mb-0">
                                        <i class="fas fa-vial mr-2"></i>
                                        Em Teste
                                        <span class="badge badge-light ml-2">{{ count($demandas['teste'] ?? []) }}</span>
                                    </h5>
                                </div>
                                <div class="kanban-cards" data-status="teste">
                                    @foreach(($demandas['teste'] ?? []) as $demanda)
                                        @include('components.kanban-card', ['demanda' => $demanda])
                                    @endforeach
                                </div>
                            </div>

                            <!-- Coluna: Concluído -->
                            <div class="kanban-column" style="flex: 1;">
                                <div class="kanban-header bg-success text-white p-3 mb-3 rounded">
                                    <h5 class="mb-0">
                                        <i class="fas fa-check-double mr-2"></i>
                                        Concluído
                                        <span class="badge badge-light ml-2">{{ count($demandas['concluido'] ?? []) }}</span>
                                    </h5>
                                </div>
                                <div class="kanban-cards" data-status="concluido">
                                    @foreach(($demandas['concluido'] ?? []) as $demanda)
                                        @include('components.kanban-card', ['demanda' => $demanda])
                                    @endforeach
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Gráficos -->
    <div class="row">
        <!-- Gráfico de Demandas por Status -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-chart-pie mr-2"></i>
                        Demandas por Status
                    </h3>
                </div>
                <div class="card-body">
                    <canvas id="statusChart" height="300"></canvas>
                </div>
            </div>
        </div>

        <!-- Gráfico de Demandas por Prioridade -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-chart-bar mr-2"></i>
                        Demandas por Prioridade
                    </h3>
                </div>
                <div class="card-body">
                    <canvas id="priorityChart" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Atividades Recentes -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-history mr-2"></i>
                        Atividades Recentes
                    </h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th>Data/Hora</th>
                                <th>Usuário</th>
                                <th>Ação</th>
                                <th>Demanda</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse(($recent_activities ?? []) as $activity)
                            <tr>
                                <td>
                                    <small class="text-muted">
                                        {{ $activity->created_at->format('d/m/Y H:i') }}
                                    </small>
                                </td>
                                <td>
                                    <img src="{{ $activity->user->avatar ?? asset('images/user-default.png') }}" 
                                         class="img-circle elevation-2" width="25" height="25">
                                    {{ $activity->user->name }}
                                </td>
                                <td>
                                    <span class="badge badge-{{ $activity->action_color ?? 'secondary' }}">
                                        {{ $activity->action_description }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('demandas.show', $activity->demanda_id) }}" class="text-decoration-none">
                                        #{{ $activity->demanda_id }} - {{ Str::limit($activity->demanda->titulo, 40) }}
                                    </a>
                                </td>
                                <td>
                                    <span class="badge badge-{{ $activity->status_color ?? 'secondary' }}">
                                        {{ $activity->demanda->status ?? 'N/A' }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    <i class="fas fa-inbox fa-2x mb-2"></i><br>
                                    Nenhuma atividade recente encontrada
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if(count($recent_activities ?? []) > 0)
                <div class="card-footer text-center">
                    <a href="{{ route('logs.index') }}" class="btn btn-sm btn-secondary">
                        Ver todos os logs
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('styles')
<style>
.kanban-board {
    background-color: #f4f4f4;
}

.kanban-column {
    min-height: 600px;
    margin: 0 10px;
}

.sortable-ghost {
    opacity: 0.4;
    background: #c8ebfb;
}

.sortable-chosen {
    transform: rotate(5deg);
}

.sortable-drag {
    transform: rotate(5deg);
}
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Dados dos gráficos vindos do controller
    const statusData = @json($chart_data['status'] ?? []);
    const priorityData = @json($chart_data['priority'] ?? []);
    
    // Gráfico de Status (Pizza)
    const statusCtx = document.getElementById('statusChart').getContext('2d');
    new Chart(statusCtx, {
        type: 'doughnut',
        data: {
            labels: Object.keys(statusData),
            datasets: [{
                data: Object.values(statusData),
                backgroundColor: [
                    '#6c757d', // Backlog - cinza
                    '#17a2b8', // Análise - azul
                    '#ffc107', // Desenvolvimento - amarelo
                    '#007bff', // Teste - azul primário
                    '#28a745'  // Concluído - verde
                ]
            }]
        },
        options: {
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
    
    // Gráfico de Prioridade (Barras)
    const priorityCtx = document.getElementById('priorityChart').getContext('2d');
    new Chart(priorityCtx, {
        type: 'bar',
        data: {
            labels: Object.keys(priorityData),
            datasets: [{
                label: 'Quantidade',
                data: Object.values(priorityData),
                backgroundColor: [
                    '#dc3545', // Alta - vermelho
                    '#ffc107', // Média - amarelo
                    '#28a745'  // Baixa - verde
                ]
            }]
        },
        options: {
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });
    
    // Auto-refresh a cada 30 segundos
    setInterval(function() {
        // Recarregar apenas os contadores
        fetch('/api/dashboard/stats')
            .then(response => response.json())
            .then(data => {
                // Atualizar contadores
                document.querySelector('.info-box:nth-child(1) .info-box-number').textContent = data.total_demandas;
                document.querySelector('.info-box:nth-child(2) .info-box-number').textContent = data.em_andamento;
                document.querySelector('.info-box:nth-child(3) .info-box-number').textContent = data.concluidas;
                document.querySelector('.info-box:nth-child(4) .info-box-number').textContent = data.alta_prioridade;
            })
            .catch(error => console.log('Erro ao atualizar stats:', error));
    }, 30000);
});
</script>
@endpush
                