@extends('layouts.app')

@section('title', 'Gestão de Usuários')
@section('page-title', 'Gestão de Usuários')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Usuários</li>
@endsection

@section('content')
    @include('users.partials.filters')
    @include('users.partials.table')
    @include('users.partials.modals')

@push('scripts')
<script>
    const currentUserName = @json(Auth::user()->name);

    document.addEventListener('DOMContentLoaded', function() {
        console.log("Usuário atual:", currentUserName);

        // Seu restante do JS mantendo acesso PHP dentro
        const selectAll = document.getElementById('select-all');
        const checkboxes = document.querySelectorAll('.user-checkbox');
        const bulkBtn = document.getElementById('bulk-actions');

        function updateBulk() {
            const cnt = document.querySelectorAll('.user-checkbox:checked').length;
            bulkBtn.disabled = !cnt;
            bulkBtn.textContent = cnt ? `Ações em massa (${cnt} selecionados)` : 'Ações em massa';
        }

        selectAll?.addEventListener('change', function() {
            checkboxes.forEach(cb => cb.checked = this.checked);
            updateBulk();
        });

        checkboxes.forEach(cb => cb.addEventListener('change', () => {
            updateBulk();
            const checked = document.querySelectorAll('.user-checkbox:checked').length;
            selectAll.checked = checked === checkboxes.length;
            selectAll.indeterminate = checked > 0 && checked < checkboxes.length;
        }));

        window.bulkAction = function(action) {
            const ids = Array.from(document.querySelectorAll('.user-checkbox:checked')).map(cb => cb.value);
            if (!ids.length) {
                toastr.warning('Selecione pelo menos um usuário');
                return;
            }
            if (!confirm(`Deseja ${action} ${ids.length} usuário(s)?`)) return;

            fetch('/api/users/bulk-action', {
                method: 'POST',
                headers: {
                    'Content-Type':'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ action, users: ids })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) toastr.success(data.message);
                else toastr.error('Erro ao executar ação em massa');
                setTimeout(() => location.reload(), 500);
            });
        };

        window.toggleUserStatus = function(id, activate) {
            const action = activate ? 'ativar' : 'desativar';
            if (!confirm(`Deseja ${action} este usuário?`)) return;

            fetch(`/api/users/${id}/status`, {
                method: 'PATCH',
                headers: {
                    'Content-Type':'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ active: activate })
            }).then(res => res.json())
              .then(data => {
                toastr[data.success ? 'success' : 'error'](`${activate ? 'Ativado' : 'Desativado'} com sucesso!`);
                setTimeout(() => location.reload(), 500);
              });
        };

        window.confirmDeleteUser = function(id) {
            if (!confirm('Confirma exclusão deste usuário?')) return;
            fetch(`/api/users/${id}`, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
            }).then(res => res.json())
              .then(data => {
                if (data.success) {
                    toastr.success('Usuário excluído');
                    document.querySelector(`tr[data-user-id="${id}"]`).remove();
                } else toastr.error('Erro ao excluir');
              });
        };

        window.managePermissions = function(id) {
            $('#permissions-manager-modal').modal('show');
            document.getElementById('permissions-content').innerHTML = '<div class="text-center"><i class="fas fa-spinner fa-spin fa-2x"></i><br>Carregando...</div>';
            fetch(`/users/${id}/permissions/edit`)
                .then(r => r.text())
                .then(html => {
                    document.getElementById('permissions-content').innerHTML = html;
                    $('.select2-permissions').select2({ placeholder:'Selecione permissões', allowClear:true, width:'100%' });
                })
                .catch(() => {
                    document.getElementById('permissions-content').innerHTML = '<div class="alert alert-danger">Erro ao carregar</div>';
                });
        };

        window.savePermissions = function() {
            const form = document.getElementById('permissions-form');
            fetch(form.action, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                body: new FormData(form)
            }).then(res => res.json())
              .then(data => {
                toastr[data.success ? 'success' : 'error'](data.success ? 'Atualizado!' : 'Erro ao atualizar');
                if (data.success) $('#permissions-manager-modal').modal('hide');
              });
        };

        let searchTimeout;
        document.getElementById('search')?.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => document.getElementById('filter-form').submit(), 1000);
        });
    });
</script>
@endpush

@endsection
