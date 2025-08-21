<tr data-user-id="{{ $user->id }}">
    <td>
        <div class="icheck-primary">
            <input type="checkbox" class="user-checkbox" id="checkbox-{{ $user->id }}" value="{{ $user->id }}">
            <label for="checkbox-{{ $user->id }}"></label>
        </div>
    </td>
    <td>
        <div class="user-block">
            <img src="{{ $user->avatar ?? asset('images/user-default.png') }}" class="img-circle img-bordered-sm" width="40" height="40">
            <span class="username"><strong>{{ $user->name }}</strong></span>
            <span class="description">Criado em {{ $user->created_at->format('d/m/Y') }}</span>
        </div>
    </td>
    <td>
        <a href="mailto:{{ $user->email }}">{{ $user->email }}</a>
        @if($user->email_verified_at)
            <i class="fas fa-check-circle text-success ml-1" title="Email verificado"></i>
        @else
            <i class="fas fa-exclamation-triangle text-warning ml-1" title="Email não verificado"></i>
        @endif
    </td>
    <td>
        @forelse($user->roles as $role)
            <x-role-badge :role="$role"/>
        @empty
            <span class="text-muted">Nenhum perfil</span>
        @endforelse
    </td>
    <td>
        @if($user->direct_permissions->count())
            <button class="btn btn-sm btn-outline-info" data-toggle="modal" data-target="#permissions-modal-{{ $user->id }}">
                <i class="fas fa-key mr-1"></i> {{ $user->direct_permissions->count() }} permissões
            </button>
        @else
            <span class="text-muted">Via perfil</span>
        @endif
    </td>
    <td><x-status-badge :active="$user->is_active"/></td>
    <td>
        @if($user->last_login_at)
            <small class="text-muted">{{ $user->last_login_at->diffForHumans() }}</small>
        @else
            <span class="text-muted">Nunca logou</span>
        @endif
    </td>
    <td>
        <div class="btn-group">
            <button class="btn btn-sm btn-outline-primary dropdown-toggle" data-toggle="dropdown"><i class="fas fa-cog"></i></button>
            <div class="dropdown-menu">
                <a class="dropdown-item" href="{{ route('users.show', $user) }}"><i class="fas fa-eye mr-2"></i> Visualizar</a>
                @can('update', $user)
                    <a class="dropdown-item" href="{{ route('users.edit', $user) }}"><i class="fas fa-edit mr-2"></i> Editar</a>
                @endcan
                <a class="dropdown-item" href="#" onclick="managePermissions({{ $user->id }})"><i class="fas fa-key mr-2"></i> Permissões</a>
                <div class="dropdown-divider"></div>
                @if($user->is_active)
                    <a class="dropdown-item text-warning" href="#" onclick="toggleUserStatus({{ $user->id }}, false)"><i class="fas fa-pause mr-2"></i> Desativar</a>
                @else
                    <a class="dropdown-item text-success" href="#" onclick="toggleUserStatus({{ $user->id }}, true)"><i class="fas fa-play mr-2"></i> Ativar</a>
                @endif
                @can('delete', $user)
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item text-danger" href="#" onclick="confirmDeleteUser({{ $user->id }})"><i class="fas fa-trash mr-2"></i> Excluir</a>
                @endcan
            </div>
        </div>
    </td>
</tr>
