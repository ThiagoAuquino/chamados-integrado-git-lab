<script>
document.addEventListener('DOMContentLoaded', function () {
    $('.select2-roles').select2({
        theme: 'bootstrap4',
        placeholder: 'Selecione os perfis do usuário',
        allowClear: true,
        width: '100%'
    });

    $('#phone').mask('(00) 00000-0000');

    $('#user-form').on('submit', function (e) {
        let isValid = true;
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

        if (!isValid) e.preventDefault();
    });

    $('.form-control').on('input', function () {
        $(this).removeClass('is-invalid');
    });

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
});

function previewAvatar(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => $('#avatar-preview').attr('src', e.target.result);
        reader.readAsDataURL(input.files[0]);
        $(input).next('.custom-file-label').text(input.files[0].name);
    }
}

function removeAvatar() {
    if (confirm('Deseja remover a foto do perfil?')) {
        $('#avatar-preview').attr('src', '{{ asset("images/user-default.png") }}');
        $('#avatar').val('');
        $('#remove_avatar').val('1');
        $('.custom-file-label').text('Escolher arquivo...');
        toastr.info('Foto será removida ao salvar');
    }
}

function togglePasswordVisibility(fieldId) {
    const field = document.getElementById(fieldId);
    const icon = field.parentElement.querySelector('i');

    if (field.type === 'password') {
        field.type = 'text';
        icon.classList.replace('fa-eye', 'fa-eye-slash');
    } else {
        field.type = 'password';
        icon.classList.replace('fa-eye-slash', 'fa-eye');
    }
}

function generateStrongPassword() {
    const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!@#$%^&*';
    let password = Array.from({ length: 12 }, () => chars.charAt(Math.floor(Math.random() * chars.length))).join('');

    $('#password').val(password).attr('type', 'text');
    $('#password_confirmation').val(password);

    setTimeout(() => $('#password').attr('type', 'password'), 3000);
    toastr.info('Senha gerada! Anote-a pois desaparecerá em 3 segundos.');
}

function resetPassword(userId) {
    if (confirm('Deseja gerar uma nova senha temporária para este usuário?')) {
        fetch(`/api/users/${userId}/reset-password`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                alert(`Nova senha temporária: ${data.password}`);
                toastr.success('Senha resetada com sucesso!');
            } else {
                toastr.error('Erro ao resetar senha');
            }
        });
    }
}

function sendWelcomeEmail(userId) {
    if (confirm('Enviar email de boas-vindas para este usuário?')) {
        fetch(`/api/users/${userId}/welcome-email`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                toastr.success('Email enviado com sucesso!');
            } else {
                toastr.error('Erro ao enviar email');
            }
        });
    }
}

function confirmDeleteUser(userId) {
    if (confirm('Tem certeza que deseja excluir este usuário? Esta ação não pode ser desfeita.')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/users/${userId}`;

        form.innerHTML = `
            <input type="hidden" name="_method" value="DELETE">
            <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').content}">
        `;
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
