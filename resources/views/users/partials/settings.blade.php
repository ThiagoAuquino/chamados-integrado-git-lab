<div class="card">
    <div class="card-header"><h3 class="card-title"><i class="fas fa-cog mr-2"></i> Configurações</h3></div>
    <div class="card-body">
        @php
            $flags = [
                'is_active' => 'Usuário Ativo',
                'email_verified' => 'Email Verificado',
                'force_password_change' => 'Forçar Troca de Senha',
            ];
        @endphp

        @foreach($flags as $key => $label)
            <div class="form-group">
                <div class="icheck-primary">
                    <input type="checkbox" id="{{ $key }}" name="{{ $key }}" value="1"
                        {{ old($key, isset($user) && $user->{$key} ? true : false) ? 'checked' : '' }}>
                    <label for="{{ $key }}">
                        <strong>{{ $label }}</strong><br>
                        <small class="text-muted">{{ __("users.settings_descriptions.$key") }}</small>
                    </label>
                </div>
            </div>
        @endforeach

        @isset($user)
            <div class="form-group">
                <div class="icheck-danger">
                    <input type="checkbox" id="reset_sessions" name="reset_sessions" value="1">
                    <label for="reset_sessions">
                        <strong>Encerrar Sessões Ativas</strong><br>
                        <small class="text-muted">Deslogar usuário de todos os dispositivos</small>
                    </label>
                </div>
            </div>
        @endisset
    </div>
</div>
