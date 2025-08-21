<div class="card">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-user mr-2"></i> Informações Básicas</h3>
    </div>
    <div class="card-body">
        <div class="row">
            @include('components.inputs.text', [
                'label' => 'Nome Completo',
                'name' => 'name',
                'value' => old('name', $user->name ?? ''),
                'required' => true,
            ])
            @include('components.inputs.email', [
                'label' => 'Email',
                'name' => 'email',
                'value' => old('email', $user->email ?? ''),
                'required' => true,
            ])
        </div>

        <div class="row">
            @include('components.inputs.password', [
                'label' => isset($user) ? 'Nova Senha (deixe em branco para manter)' : 'Senha',
                'name' => 'password',
                'required' => !isset($user),
            ])
            @include('components.inputs.password', [
                'label' => 'Confirmar Senha',
                'name' => 'password_confirmation',
                'required' => !isset($user),
            ])
        </div>

        <div class="row">
            @include('components.inputs.text', [
                'label' => 'Telefone',
                'name' => 'phone',
                'value' => old('phone', $user->phone ?? ''),
                'mask' => '(00) 00000-0000',
            ])
            @include('components.inputs.text', [
                'label' => 'Departamento',
                'name' => 'department',
                'value' => old('department', $user->department ?? ''),
            ])
        </div>

        <div class="form-group">
            <label for="bio">Biografia/Observações</label>
            <textarea class="form-control @error('bio') is-invalid @enderror" name="bio" id="bio" rows="3">{{ old('bio', $user->bio ?? '') }}</textarea>
            @error('bio') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>
</div>
    