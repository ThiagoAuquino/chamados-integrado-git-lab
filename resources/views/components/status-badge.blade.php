@props(['active'])
<span class="badge badge-{{ $active ? 'success' : 'danger' }}">
    <i class="fas fa-{{ $active ? 'check-circle' : 'times-circle' }} mr-1"></i>
    {{ $active ? 'Ativo' : 'Inativo' }}
</span>
