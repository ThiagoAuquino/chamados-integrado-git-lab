<div class="card">
    <div class="card-body text-center">
        <button type="submit" class="btn btn-success btn-block">
            <i class="fas fa-save mr-2"></i> Salvar
        </button>

        @isset($user)
            <button type="button" class="btn btn-warning btn-block" onclick="resetPassword({{ $user->id }})">
                <i class="fas fa-sync-alt mr-2"></i> Resetar Senha
            </button>

            <button type="button" class="btn btn-info btn-block" onclick="sendWelcomeEmail({{ $user->id }})">
                <i class="fas fa-envelope mr-2"></i> Enviar Boas-vindas
            </button>

            <button type="button" class="btn btn-danger btn-block" onclick="confirmDeleteUser({{ $user->id }})">
                <i class="fas fa-trash mr-2"></i> Excluir
            </button>
        @endisset

        <a href="{{ route('users.index') }}" class="btn btn-secondary btn-block mt-2">
            <i class="fas fa-arrow-left mr-2"></i> Voltar
        </a>
    </div>
</div>
