<div class="card">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-key mr-2"></i> Perfis e Permissões</h3>
        <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
        </div>
    </div>
    <div class="card-body">
        <div class="form-group">
            <label for="roles">Perfis do Sistema</label>
            <select class="form-control select2-roles @error('roles') is-invalid @enderror" name="roles[]" id="roles" multiple>
                @foreach($roles as $role)
                    <option value="{{ $role->id }}" {{ collect(old('roles', $user->roles->pluck('id') ?? []))->contains($role->id) ? 'selected' : '' }}>
                        {{ $role->name }} - {{ $role->description }}
                    </option>
                @endforeach
            </select>
            @error('roles') <div class="invalid-feedback">{{ $message }}</div> @enderror
            <small class="form-text text-muted">Os perfis definem conjuntos de permissões. Um usuário pode ter múltiplos perfis.</small>
        </div>

        <div class="form-group">
            <label>Permissões Específicas</label>
            <small class="form-text text-muted mb-3">Permissões adicionais além das concedidas pelos perfis</small>
            <div class="row">
                @foreach($permissions->groupBy('group') as $group => $groupPermissions)
                    <div class="col-md-4 mb-3">
                        <div class="card card-outline card-primary">
                            <div class="card-header"><h4 class="card-title">{{ ucfirst($group) }}</h4></div>
                            <div class="card-body">
                                @foreach($groupPermissions as $permission)
                                    <div class="icheck-primary">
                                        <input type="checkbox" id="permission-{{ $permission->id }}" name="permissions[]" value="{{ $permission->id }}"
                                            {{ collect(old('permissions', $user->direct_permissions->pluck('id') ?? []))->contains($permission->id) ? 'checked' : '' }}>
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
