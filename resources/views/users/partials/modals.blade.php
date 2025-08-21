@foreach($users as $user)
    @if($user->direct_permissions->count())
        <div class="modal fade" id="permissions-modal-{{ $user->id }}" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Permissões Específicas - {{ $user->name }}</h4>
                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            @foreach($user->direct_permissions->chunk(3) as $chunk)
                                <div class="col-md-4">
                                    @foreach($chunk as $permission)
                                        <div class="form-check mb-2">
                                            <input type="checkbox" checked disabled>
                                            <label class="form-check-label"><strong>{{ $permission->name }}</strong><br><small class="text-muted">{{ $permission->description }}</small></label>
                                        </div>
                                    @endforeach
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                        <a href="{{ route('users.permissions', $user) }}" class="btn btn-primary">Gerenciar Permissões</a>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endforeach

<div class="modal fade" id="permissions-manager-modal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Gerenciar Permissões</h4>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body" id="permissions-content">
                <!-- Carregado via AJAX -->
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button class="btn btn-primary" onclick="savePermissions()">Salvar Alterações</button>
            </div>
        </div>
    </div>
</div>
