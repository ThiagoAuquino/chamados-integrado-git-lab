<div class="card">
    <div class="card-header"><h3 class="card-title"><i class="fas fa-info-circle mr-2"></i> Informações do Sistema</h3></div>
    <div class="card-body">
        <dl class="row">
            <dt class="col-sm-5">ID:</dt>
            <dd class="col-sm-7">#{{ $user->id }}</dd>

            <dt class="col-sm-5">Criado em:</dt>
            <dd class="col-sm-7">{{ $user->created_at->format('d/m/Y H:i') }}</dd>

            <dt class="col-sm-5">Último Login:</dt>
            <dd class="col-sm-7">{{ $user->last_login ? $user->last_login->format('d/m/Y H:i') : 'Nunca' }}</dd>
        </dl>
    </div>
</div>
