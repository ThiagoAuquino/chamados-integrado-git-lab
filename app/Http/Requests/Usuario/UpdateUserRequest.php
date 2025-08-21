<?php

namespace App\Http\Requests\Usuario;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    public function rules(): array
    {
        $userId = $this->route('id');
        return [
            'name' => 'required|string',
            'email' => "required|email|unique:users,email,{$userId}",
            'password' => 'nullable|string|min:6|confirmed',
            'active' => 'boolean',
            'roles' => 'array',
            'permissions' => 'array',
        ];
    }
}
