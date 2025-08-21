{{-- Componente: resources/views/components/kanban-card.blade.php --}}
<div class="kanban-card priority-{{ strtolower($demanda->prioridade) }}" data-id="{{ $demanda->id }}">
    <!-- Header do Card -->
    <div class="d-flex justify-content-between align-items-start mb-2">
        <span class="badge badge-secondary">#{{ $demanda->id }}</span>
        <div class="dropdown">
            <button class="btn btn-link btn-sm p-0 text-muted" data-toggle="dropdown">
                <i class="fas fa-ellipsis-v"></i>
            </button>
            <div class="dropdown-menu dropdown-menu-right">
                <a class="dropdown-item" href="{{ route('demandas.show', $demanda->id) }}">
                    <i class="fas fa-eye mr-2"></i> Visualizar
                </a>
                @can('update', $demanda)
                <a class="dropdown-item" href="{{ route('demandas.edit', $demanda->id) }}">
                    <i class="fas fa-edit mr-2"></i> Editar
                </a>
                @endcan
                <div class="dropdown-divider"></div>
                <a class="dropdown-item text-danger" href="#" onclick="confirmDelete({{ $demanda->id }})">
                    <i class="fas fa-trash mr-2"></i> Excluir
                </a>
            </div>
        </div>
    </div>

    <!-- Título da Demanda -->
    <h6 class="mb-2">
        <a href="{{ route('demandas.show', $demanda->id) }}" class="text-dark text-decoration-none">
            {{ Str::limit($demanda->titulo, 60) }}
        </a>
    </h6>

    <!-- Descrição -->
    @if($demanda->descricao)
    <p class="text-muted small mb-2">
        {{ Str::limit(strip_tags($demanda->descricao), 80) }}
    </p>
    @endif

    <!-- Tags/Labels -->
    <div class="mb-2">
        <!-- Prioridade -->
        <span class="badge badge-{{ $demanda->prioridade === 'alta' ? 'danger' : ($demanda->prioridade === 'media' ? 'warning' : 'success') }}">
            <i class="fas fa-flag mr-1"></i>
            {{ ucfirst($demanda->prioridade) }}
        </span>

        <!-- Tipo -->
        @if($demanda->tipo)
        <span class="badge badge-info ml-1">
            {{ $demanda->tipo }}
        </span>
        @endif

        <!-- Estimativa -->
        @if($demanda->estimativa_horas)
        <span class="badge badge-secondary ml-1">
            <i class="fas fa-clock mr-1"></i>
            {{ $demanda->estimativa_horas }}h
        </span>
        @endif
    </div>

    <!-- Usuário Responsável -->
    <div class="d-flex justify-content-between align-items-center">
        <div class="user-info">
            @if($demanda->responsavel)
            <img src="{{ $demanda->responsavel->avatar ?? asset('images/user-default.png') }}" 
                 class="img-circle elevation-2" 
                 width="24" height="24"
                 title="{{ $demanda->responsavel->name }}">
            <small class="ml-1">{{ $demanda->responsavel->name }}</small>
            @else
            <small class="text-muted">
                <i class="fas fa-user-slash mr-1"></i>
                Não atribuída
            </small>
            @endif
        </div>

        <!-- Data de Criação -->
        <small class="text-muted">
            {{ $demanda->created_at->format('d/m') }}
        </small>
    </div>

    <!-- Progress Bar (se houver subtarefas) -->
    @if($demanda->subtarefas_count > 0)
    <div class="mt-2">
        <div class="progress" style="height: 4px;">
            <div class="progress-bar bg-success" 
                 role="progressbar" 
                 style="width: {{ ($demanda->subtarefas_concluidas / $demanda->subtarefas_count) * 100 }}%">
            </div>
        </div>
        <small class="text-muted">
            {{ $demanda->subtarefas_concluidas }}/{{ $demanda->subtarefas_count }} subtarefas
        </small>
    </div>
    @endif

    <!-- Comentários e Anexos -->
    <div class="mt-2 d-flex justify-content-between align-items-center">
        <div class="card-icons">
            @if($demanda->comentarios_count > 0)
            <span class="text-muted mr-2" title="{{ $demanda->comentarios_count }} comentários">
                <i class="fas fa-comment"></i>
                <small>{{ $demanda->comentarios_count }}</small>
            </span>
            @endif

            @if($demanda->anexos_count > 0)
            <span class="text-muted mr-2" title="{{ $demanda->anexos_count }} anexos">
                <i class="fas fa-paperclip"></i>
                <small>{{ $demanda->anexos_count }}</small>
            </span>
            @endif

            @if($demanda->data_vencimento && $demanda->data_vencimento->isPast())
            <span class="text-danger" title="Vencida em {{ $demanda->data_vencimento->format('d/m/Y') }}">
                <i class="fas fa-exclamation-triangle"></i>
            </span>
            @elseif($demanda->data_vencimento && $demanda->data_vencimento->diffInDays(now()) <= 3)
            <span class="text-warning" title="Vence em {{ $demanda->data_vencimento->format('d/m/Y') }}">
                <i class="fas fa-clock"></i>
            </span>
            @endif
        </div>

        <!-- Status Actions -->
        <div class="dropdown">
            <button class="btn btn-outline-secondary btn-xs" data-toggle="dropdown">
                <i class="fas fa-arrows-alt"></i>
            </button>
            <div class="dropdown-menu">
                <h6 class="dropdown-header">Mover para:</h6>
                @foreach(['backlog' => 'Backlog', 'analise' => 'Análise', 'desenvolvimento' => 'Desenvolvimento', 'teste' => 'Teste', 'concluido' => 'Concluído'] as $status => $label)
                    @if($status !== $demanda->status)
                    <a class="dropdown-item" href="#" onclick="changeStatus({{ $demanda->id }}, '{{ $status }}')">
                        {{ $label }}
                    </a>
                    @endif
                @endforeach
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="mt-2 pt-2 border-top d-flex justify-content-end">
        <button class="btn btn-outline-primary btn-xs mr-1" onclick="openQuickEdit({{ $demanda->id }})">
            <i class="fas fa-edit"></i>
        </button>
        <button class="btn btn-outline-info btn-xs mr-1" onclick="addComment({{ $demanda->id }})">
            <i class="fas fa-comment"></i>
        </button>
        <button class="btn btn-outline-secondary btn-xs" onclick="viewHistory({{ $demanda->id }})">
            <i class="fas fa-history"></i>
        </button>
    </div>
</div>

<style>
.kanban-card {
    transition: all 0.3s ease;
    border: 1px solid #e9ecef;
}

.kanban-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    border-color: #007bff;
}

.priority-alta {
    border-left: 4px solid #dc3545;
}

.priority-media {
    border-left: 4px solid #ffc107;
}

.priority-baixa {
    border-left: 4px solid #28a745;
}

.btn-xs {
    padding: 0.25rem 0.5rem;
    font-size: 0.75rem;
    line-height: 1.2;
    border-radius: 0.2rem;
}

.user-info img {
    vertical-align: middle;
}

.card-icons i {
    font-size: 0.8rem;
}

.progress {
    height: 4px !important;
}
</style>

<script>
// Função para mudar status via dropdown
function changeStatus(demandaId, newStatus) {
    if (confirm('Deseja realmente mover esta demanda?')) {
        fetch(`/api/demandas/${demandaId}/status`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ status: newStatus })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                toastr.success('Status atualizado com sucesso!');
                location.reload();
            } else {
                toastr.error('Erro ao atualizar status');
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            toastr.error('Erro de conexão');
        });
    }
}

// Função para confirmação de exclusão
function confirmDelete(demandaId) {
    if (confirm('Tem certeza que deseja excluir esta demanda? Esta ação não pode ser desfeita.')) {
        fetch(`/api/demandas/${demandaId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                toastr.success('Demanda excluída com sucesso!');
                document.querySelector(`[data-id="${demandaId}"]`).remove();
            } else {
                toastr.error('Erro ao excluir demanda');
            }
        });
    }
}

// Função para abrir modal de edição rápida
function openQuickEdit(demandaId) {
    // Implementar modal de edição rápida
    window.location.href = `/demandas/${demandaId}/edit`;
}

// Função para adicionar comentário
function addComment(demandaId) {
    // Implementar modal de comentário
    const comment = prompt('Digite seu comentário:');
    if (comment && comment.trim()) {
        fetch(`/api/demandas/${demandaId}/comments`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ comment: comment.trim() })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                toastr.success('Comentário adicionado!');
                location.reload();
            }
        });
    }
}

// Função para ver histórico
function viewHistory(demandaId) {
    window.open(`/demandas/${demandaId}/history`, '_blank');
}
</script>