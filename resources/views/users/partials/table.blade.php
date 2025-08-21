<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-users mr-2"></i> Lista de Usuários ({{ $users->total() }} registros)</h3>
                <div class="card-tools">
                    <div class="btn-group">
                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" data-toggle="dropdown" id="bulk-actions" disabled>Ações em massa</button>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="#" onclick="bulkAction('activate')"><i class="fas fa-check-circle mr-2 text-success"></i> Ativar selecionados</a>
                            <a class="dropdown-item" href="#" onclick="bulkAction('deactivate')"><i class="fas fa-times-circle mr-2 text-warning"></i> Desativar selecionados</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item text-danger" href="#" onclick="bulkAction('delete')"><i class="fas fa-trash mr-2"></i> Excluir selecionados</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap" id="users-table">
                    <thead>
                        <!-- Cabeçalho completo com checkbox de selecionar todos -->
                        <tr>
                            <th style="width:40px;"><div class="icheck-primary"><input type="checkbox" id="select-all"><label for="select-all"></label></div></th>
                            <th>Usuário</th>
                            <th>Email</th>
                            <th>Perfis</th>
                            <th>Permissões Específicas</th>
                            <th>Status</th>
                            <th>Última Atividade</th>
                            <th style="width:120px;">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            @include('users.partials.user-row')
                        @empty
                            <tr><td colspan="8" class="text-center py-5">
                                <div class="empty-state">
                                    <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                    <h4 class="text-muted">Nenhum usuário encontrado</h4>
                                    <p class="text-muted">
                                        @if(request()->hasAny(['search','role','status']))
                                            Tente ajustar os filtros de busca.
                                        @else
                                            Comece criando o primeiro usuário do sistema.
                                        @endif
                                    </p>
                                    @can('create-user')
                                        <a href="{{ route('users.create') }}" class="btn btn-primary"><i class="fas fa-plus mr-1"></i> Criar Primeiro Usuário</a>
                                    @endcan
                                </div>
                            </td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($users->hasPages())
            <div class="card-footer">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="text-muted">
                        Mostrando {{ $users->firstItem() }} a {{ $users->lastItem() }} de {{ $users->total() }} registros
                    </div>
                    <div>{{ $users->links() }}</div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
