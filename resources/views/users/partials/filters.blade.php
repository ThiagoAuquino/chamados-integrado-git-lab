<div class="row mb-3">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-filter mr-2"></i> Filtros e Ações</h3>
                <div class="card-tools">
                    <button class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                </div>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('users.index') }}" id="filter-form">
                    <div class="row">
                        <!-- Campo de busca -->
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="search">Buscar por nome/email:</label>
                                <input type="text" class="form-control" id="search" name="search" 
                                       value="{{ request('search') }}" placeholder="Digite para buscar...">
                            </div>
                        </div>
                        <!-- Perfil -->
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="role">Filtrar por perfil:</label>
                                <select class="form-control" id="role" name="role">
                                    <option value="">Todos os perfis</option>
                                    @foreach($roles as $role)
                                        <option value="{{ $role->id }}" {{ request('role') == $role->id ? 'selected' : '' }}>
                                            {{ $role->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <!-- Status -->
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="status">Status:</label>
                                <select class="form-control" id="status" name="status">
                                    <option value="">Todos</option>
                                    <option value="active" {{ request('status')=='active' ? 'selected':'' }}>Ativos</option>
                                    <option value="inactive" {{ request('status')=='inactive' ? 'selected':'' }}>Inativos</option>
                                </select>
                            </div>
                        </div>
                        <!-- Botões -->
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>&nbsp;</label>
                                <div>
                                    <button type="submit" class="btn btn-primary"><i class="fas fa-search mr-1"></i> Filtrar</button>
                                    <a href="{{ route('users.index') }}" class="btn btn-secondary ml-1"><i class="fas fa-times mr-1"></i> Limpar</a>
                                    @can('create-user')
                                        <a href="{{ route('users.create') }}" class="btn btn-success ml-2"><i class="fas fa-plus mr-1"></i> Novo Usuário</a>
                                    @endcan
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
