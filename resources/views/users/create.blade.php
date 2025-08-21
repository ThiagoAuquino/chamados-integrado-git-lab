@extends('layouts.app')

@section('title', isset($user) ? 'Editar Usuário' : 'Novo Usuário')
@section('page-title', isset($user) ? 'Editar Usuário' : 'Novo Usuário')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('users.index') }}">Usuários</a></li>
    <li class="breadcrumb-item active">{{ isset($user) ? 'Editar' : 'Novo' }}</li>
@endsection

@section('content')
    <form method="POST" action="{{ isset($user) ? route('users.update', $user) : route('users.store') }}" 
          enctype="multipart/form-data" id="user-form">
        @csrf
        @if(isset($user))
            @method('PUT')
        @endif

        <div class="row">
            <!-- Informações Básicas -->
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-user mr-2"></i>
                            Informações Básicas
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Nome Completo <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           class="form-control @error('name') is-invalid @enderror" 
                                           id="name" 
                                           name="name" 
                                           value="{{ old('name', $user->name ?? '') }}" 
                                           placeholder="Digite o nome completo"
                                           required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email">Email <span class="text-danger">*</span></label>
                                    <input type="email" 
                                           class="form-control @error('email') is-invalid @enderror" 
                                           id="email" 
                                           name="email" 
                                           value="{{ old('email', $user->email ?? '') }}" 
                                           placeholder="usuario@exemplo.com"
                                           required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="password">
                                        {{ isset($user) ? 'Nova Senha (deixe em branco para manter)' : 'Senha' }}
                                        @if(!isset($user))
                                            <span class="text-danger">*</span>
                                        @endif
                                    </label>
                                    <div class="input-group">
                                        <input type="password" 
                                               class="form-control @error('password') is-invalid @enderror" 
                                               id="password" 
                                               name="password" 
                                               placeholder="Digite a senha"
                                               {{ !isset($user) ? 'required' : '' }}>
                                        <div class="input-group-append">
                                            <button type="button" class="btn btn-outline-secondary" 
                                                    onclick="togglePasswordVisibility('password')">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                    </div>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">
                                        Mínimo 8 caracteres, incluindo letras, números e símbolos
                                    </small>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="password_confirmation">
                                        Confirmar Senha
                                        @if(!isset($user))
                                            <span class="text-danger">*</span>
                                        @endif
                                    </label>
                                    <div class="input-group">
                                        <input type="password" 
                                               class="form-control" 
                                               id="password_confirmation" 
                                               name="password_confirmation" 
                                               placeholder="Confirme a senha"
                                               {{ !isset($user) ? 'required' : '' }}>
                                        <div class="input-group-append">
                                            <button type="button" class="btn btn-outline-secondary" 
                                                    onclick="togglePasswordVisibility('password_confirmation')">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="phone">Telefone</label>
                                    <input type="text" 
                                           class="form-control @error('phone') is-invalid @enderror" 
                                           id="phone" 
                                           name="phone" 
                                           value="{{ old('phone', $user->phone ?? '') }}" 
                                           placeholder="(11) 99999-9999">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="department">Departamento</label>
                                    <input type="text" 
                                           class="form-control @error('department') is-invalid @enderror" 
                                           id="department" 
                                           name="department" 
                                           value="{{ old('department', $user->department ?? '') }}" 
                                           placeholder="Ex: TI, RH, Vendas">
                                    @error('department')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="bio">Biografia/Observações</label>
                            <textarea class="form-control @error('bio') is-invalid @enderror" 
                                      id="bio" 
                                      name="bio" 
                                      rows="3" 
                                      placeholder="Informações adicionais sobre o usuário">{{ old('bio', $user->bio ?? '') }}</textarea>
                            @error('bio')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Permissões e Perfis -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-key mr-2"></i>
                            Perfis e Permissões
                        </h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Perfis (Roles) -->
                        <div class="form-group">
                            <label for="roles">Perfis do Sistema</label>
                            <select class="form-control select2-roles @error('roles') is-invalid @enderror" 
                                    id="roles" 
                                    name="roles[]" 
                                    multiple="multiple">
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}" 
                                        {{ (collect(old('roles', isset($user) ? $user->roles->pluck('id') : []))->contains($role->id)) ? 'selected' : '' }}>
                                        {{ $role->name }} - {{ $role->description }}
                                    </option>
                                @endforeach
                            </select>
                            @error('roles')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">
                                Os perfis definem conjuntos de permissões. Um usuário pode ter múltiplos perfis.
                            </small>
                        </div>

                        <!-- Permissões Específicas -->
                        <div class="form-group">
                            <label>Permissões Específicas</label>
                            <small class="form-text text-muted mb-3">
                                Permissões adicionais além das concedidas pelos perfis
                            </small>
                            
                            <div class="row">
                                @foreach($permissions->groupBy('group') as $group => $groupPermissions)
                                <div class="col-md-4 mb-3">
                                    <div class="card card-outline card-primary">
                                        <div class="card-header">
                                            <h4 class="card-title">{{ ucfirst($group) }}</h4>
                                        </div>
                                        <div class="card-body">
                                            @foreach($groupPermissions as $permission)
                                            <div class="icheck-primary">
                                                <input type="checkbox" 
                                                       id="permission-{{ $permission->id }}" 
                                                       name="permissions[]" 
                                                       value="{{ $permission->id }}"
                                                       {{ (collect(old('permissions', isset($user) ? $user->direct_permissions->pluck('id') : []))->contains($permission->id)) ? 'checked' : '' }}>
                                                <label for="permission-{{ $permission->id }}">
                                                    <strong>{{ $permission->name }}</strong><br>
                                                    <small class="text-muted">{{ $permission->description }}</small>
                                                </label>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-md-4">
                <!-- Status e Configurações -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-cog mr-2"></i>
                            Configurações
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <div class="icheck-primary">
                                <input type="checkbox" 
                                       id="is_active" 
                                       name="is_active" 
                                       value="1" 
                                       {{ old('is_active', isset($user) ? $user->is_active : true) ? 'checked' : '' }}>
                                <label for="is_active">
                                    <strong>Usuário Ativo</strong><br>
                                    <small class="text-muted">Usuário pode fazer login no sistema</small>
                                </label>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="icheck-primary">
                                <input type="checkbox" 
                                       id="email_verified" 
                                       name="email_verified" 
                                       value="1" 
                                       {{ old('email_verified', isset($user) ? $user->email_verified_at : false) ? 'checked' : '' }}>
                                <label for="email_verified">
                                    <strong>Email Verificado</strong><br>
                                    <small class="text-muted">Marcar email como verificado</small>
                                </label>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="icheck-primary">
                                <input type="checkbox" 
                                       id="force_password_change" 
                                       name="force_password_change" 
                                       value="1" 
                                       {{ old('force_password_change', false) ? 'checked' : '' }}>
                                <label for="force_password_change">
                                    <strong>Forçar Troca de Senha</strong><br>
                                    <small class="text-muted">Usuário deve trocar senha no próximo login</small>
                                </label>
                            </div>
                        </div>

                        @if(isset($user))
                        <div class="form-group">
                            <div class="icheck-danger">
                                <input type="checkbox" 
                                       id="reset_sessions" 
                                       name="reset_sessions" 
                                       value="1">
                                <label for="reset_sessions">
                                    <strong>Encerrar Sessões Ativas</strong><br>
                                    <small class="text-muted">Deslogar usuário de todos os dispositivos</small>
                                </label>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Avatar -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-image mr-2"></i>
                            Foto do Perfil
                        </h3>
                    </div>
                    <div class="card-body text-center">
                        <div class="form-group">
                            <div class="avatar-preview mb-3">
                                <img id="avatar-preview" 
                                     src="{{ isset($user) && $user->avatar ? $user->avatar : asset('images/user-default.png') }}" 
                                     alt="Avatar" 
                                     class="img-circle elevation-2" 
                                     width="100" 
                                     height="100">
                            </div>
                            
                            <div class="custom-file">
                                <input type="file" 
                                       class="custom-file-input @error('avatar') is-invalid @enderror" 
                                       id="avatar" 
                                       name="avatar" 
                                       accept="image/*" 
                                       onchange="previewAvatar(this)">
                                <label class="custom-file-label" for="avatar">
                                    Escolher arquivo...
                                </label>
                            </div>
                            @error('avatar')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">
                                Formatos aceitos: JPG, PNG, GIF. Máximo 2MB.
                            </small>
                            
                            @if(isset($user) && $user->avatar)
                            <button type="button" class="btn btn-sm btn-danger mt-2" onclick="removeAvatar()">
                                <i class="fas fa-trash mr-1"></i> Remover Foto
                            </button>
                            <input type="hidden" id="remove_avatar" name="remove_avatar" value="0">
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Informações do Sistema -->
                @if(isset($user))
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-info-circle mr-2"></i>
                            Informações do Sistema
                        </h3>
                    </div>
                    <div class="card-body">
                        <dl class="row">
                            <dt class="col-sm-5">ID:</dt>
                            <dd class="col-sm-7">#{{ $user->id }}</dd>
                            
                            <dt class="col-sm-5">Criado em:</dt>
                            <dd class="col-sm-7">{{ $user->created_at->format('d/m/Y H:i') }}</dd>
                            
                            <dt class="col-sm-5">Atualizado em:</dt>
                            <dd class="col-sm-7">{{ $user->updated_at->format('d/m/Y H:i') }}</dd>
                            
                            @if($user->last_login_at)
                            <dt class="col-sm-5">Último login:</dt>
                            <dd class="col-sm-7">{{ $user->last_login_at->format('d/m/Y H:i') }}</dd>
                            @endif
                            
                            <dt class="col-sm-5">Total de demandas:</dt>
                            <dd class="col-sm-7">{{ $user->demandas_count ?? 0 }}</dd>
                        </dl>
                    </div>
                </div>
                @endif

                <!-- Botões de Ação -->
                <div class="card">
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success btn-block">
                                <i class="fas fa-save mr-2"></i>
                                {{ isset($user) ? 'Atualizar Usuário' : 'Criar Usuário' }}
                            </button>
                            
                            <a href="{{ route('users.index') }}" class="btn btn-secondary btn-block">
                                <i class="fas fa-times mr-2"></i>
                                Cancelar
                            </a>
                            
                            @if(isset($user))
                            <div class="dropdown">
                                <button class="btn btn-outline-primary btn-block dropdown-toggle" 
                                        type="button" data-toggle="dropdown">
                                    <i class="fas fa-cog mr-2"></i>
                                    Ações Avançadas
                                </button>
                                <div class="dropdown-menu w-100">
                                    <a class="dropdown-item" href="{{ route('users.show', $user) }}">
                                        <i class="fas fa-eye mr-2"></i> Visualizar Perfil
                                    </a>
                                    <a class="dropdown-item" href="#" onclick="resetPassword({{ $user->id }})">
                                        <i class="fas fa-key mr-2"></i> Resetar Senha
                                    </a>
                                    <a class="dropdown-item" href="#" onclick="sendWelcomeEmail({{ $user->id }})">
                                        <i class="fas fa-envelope mr-2"></i> Enviar Email de Boas-vindas
                                    </a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item text-danger" href="#" onclick="confirmDeleteUser({{ $user->id }})">
                                        <i class="fas fa-trash mr-2"></i> Excluir Usuário
                                    </a>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/icheck-bootstrap@3.0.1/icheck-bootstrap.min.css">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@1.5.2/dist/select2-bootstrap4.min.css" rel="stylesheet" />

<style>
.avatar-preview img {
    transition: all 0.3s ease;
    border: 3px solid #fff;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.avatar-preview:hover img {
    transform: scale(1.05);
}

.custom-file-label::after {
    content: "Procurar";
}

.card-outline {
    border-width: 1px;
}

.permission-group {
    border: 1px solid #dee2e6;
    border-radius: 0.375rem;
    padding: 1rem;
    margin-bottom: 1rem;
}

.permission-group h5 {
    color: #495057;
    margin-bottom: 0.75rem;
    padding-bottom: 0.5rem;
    border-bottom: 1px solid #dee2e6;
}

.icheck-primary label {
    font-weight: normal;
}
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery.mask@1.14.16/dist/jquery.mask.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Inicializar Select2
    $('.select2-roles').select2({
        theme: 'bootstrap4',
        placeholder: 'Selecione os perfis do usuário',
        allowClear: true,
        width: '100%'
    });

    // Máscara para telefone
    $('#phone').mask('(00) 00000-0000', {
        translation: {
            '0': {pattern: /[0-9]/}
        }
    });

    // Validação em tempo real
    $('#user-form').on('submit', function(e) {
        let isValid = true;
        
        // Validar senha se estiver preenchida
        const password = $('#password').val();
        const passwordConfirm = $('#password_confirmation').val();
        
        if (password && password !== passwordConfirm) {
            isValid = false;
            toastr.error('As senhas não coincidem');
            $('#password_confirmation').addClass('is-invalid');
        }
        
        if (password && password.length < 8) {
            isValid = false;
            toastr.error('A senha deve ter pelo menos 8 caracteres');
            $('#password').addClass('is-invalid');
        }
        
        if (!isValid) {
            e.preventDefault();
        }
    });

    // Limpar validação ao digitar
    $('.form-control').on('input', function() {
        $(this).removeClass('is-invalid');
    });
});

// Função para visualizar avatar
function previewAvatar(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            $('#avatar-preview').attr('src', e.target.result);
        };
        
        reader.readAsDataURL(input.files[0]);
        
        // Atualizar nome do arquivo
        const fileName = input.files[0].name;
        $(input).next('.custom-file-label').text(fileName);
    }
}

// Função para remover avatar
function removeAvatar() {
    if (confirm('Deseja remover a foto do perfil?')) {
        $('#avatar-preview').attr('src', '{{ asset("images/user-default.png") }}');
        $('#avatar').val('');
        $('#remove_avatar').val('1');
        $('.custom-file-label').text('Escolher arquivo...');
        toastr.info('Foto será removida ao salvar');
    }
}

// Função para alternar visibilidade da senha
function togglePasswordVisibility(fieldId) {
    const field = document.getElementById(fieldId);
    const icon = field.parentElement.querySelector('i');
    
    if (field.type === 'password') {
        field.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        field.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}

// Função para resetar senha
function resetPassword(userId) {
    if (confirm('Deseja gerar uma nova senha temporária para este usuário?')) {
        fetch(`/api/users/${userId}/reset-password`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(`Nova senha temporária: ${data.password}\n\nO usuário deve trocar a senha no próximo login.`);
                toastr.success('Senha resetada com sucesso!');
            } else {
                toastr.error('Erro ao resetar senha');
            }
        });
    }
}

// Função para enviar email de boas-vindas
function sendWelcomeEmail(userId) {
    if (confirm('Enviar email de boas-vindas para este usuário?')) {
        fetch(`/api/users/${userId}/welcome-email`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                toastr.success('Email enviado com sucesso!');
            } else {
                toastr.error('Erro ao enviar email');
            }
        });
    }
}

// Função para confirmar exclusão
function confirmDeleteUser(userId) {
    if (confirm('Tem certeza que deseja excluir este usuário? Esta ação não pode ser desfeita.')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/users/${userId}`;
        
        const methodField = document.createElement('input');
        methodField.type = 'hidden';
        methodField.name = '_method';
        methodField.value = 'DELETE';
        
        const tokenField = document.createElement('input');
        tokenField.type = 'hidden';
        tokenField.name = '_token';
        tokenField.value = document.querySelector('meta[name="csrf-token"]').content;
        
        form.appendChild(methodField);
        form.appendChild(tokenField);
        document.body.appendChild(form);
        form.submit();
    }
}

// Função para gerar senha forte
function generateStrongPassword() {
    const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!@#$%^&*';
    let password = '';
    for (let i = 0; i < 12; i++) {
        password += chars.charAt(Math.floor(Math.random() * chars.length));
    }
    
    $('#password').val(password);
    $('#password_confirmation').val(password);
    
    // Mostrar senha temporariamente
    $('#password').attr('type', 'text');
    setTimeout(() => {
        $('#password').attr('type', 'password');
    }, 3000);
    
    toastr.info('Senha gerada! Anote-a pois desaparecerá em 3 segundos.');
}

// Adicionar botão de gerar senha
$('#password').after(`
    <div class="input-group-append">
        <button type="button" class="btn btn-outline-secondary" onclick="generateStrongPassword()" title="Gerar senha forte">
            <i class="fas fa-magic"></i>
        </button>
        <button type="button" class="btn btn-outline-secondary" onclick="togglePasswordVisibility('password')">
            <i class="fas fa-eye"></i>
        </button>
    </div>
`);
</script>
@endpush