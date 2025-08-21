<div class="card">
    <div class="card-header"><h3 class="card-title"><i class="fas fa-image mr-2"></i> Foto do Perfil</h3></div>
    <div class="card-body text-center">
        <div class="form-group">
            <div class="avatar-preview mb-3">
                <img id="avatar-preview" 
                     src="{{ isset($user) && $user->avatar ? $user->avatar : asset('images/user-default.png') }}" 
                     alt="Avatar" class="img-circle elevation-2" width="100" height="100">
            </div>

            <div class="custom-file">
                <input type="file" class="custom-file-input @error('avatar') is-invalid @enderror" name="avatar" id="avatar" accept="image/*" onchange="previewAvatar(this)">
                <label class="custom-file-label" for="avatar">Escolher arquivo...</label>
                @error('avatar') <div class="invalid-feedback">{{ $message }}</div> @enderror
                <small class="form-text text-muted">Formatos aceitos: JPG, PNG, GIF. Máximo 2MB.</small>
            </div>

            @if(isset($user) && $user->avatar)
                <button type="button" class="btn btn-sm btn-danger mt-2" onclick="removeAvatar()">
                    <i class="fas fa-trash mr-1"></i> Remover Foto
                </button>
                <input type="hidden" name="remove_avatar" id="remove_avatar" value="0">
            @endif
        </div>
    </div>
</div>
