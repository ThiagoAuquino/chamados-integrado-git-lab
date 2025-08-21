// Funcionalidades de Permissões
document.addEventListener('DOMContentLoaded', function() {
    // Inicializar Select2 para seleção de permissões
    $('.select2-permissions').select2({
        placeholder: 'Selecione as permissões',
        allowClear: true,
        width: '100%'
    });
    
    // Inicializar DataTables
    $('#users-table').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/pt-BR.json'
        },
        pageLength: 25,
        order: [[0, 'asc']]
    });
});

// Função para toggle de permissões
function togglePermission(userId, permission) {
    fetch(`/api/users/${userId}/permissions`, {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ 
            permission: permission,
            action: 'toggle'
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    });
}