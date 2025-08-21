@props(['role'])
<span class="badge badge-{{ $role->color ?? 'secondary' }} mr-1">
    <i class="fas fa-user-tag mr-1"></i>
    {{ $role->name }}
</span>
