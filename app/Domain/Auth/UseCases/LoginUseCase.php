<?php

namespace App\Domain\Auth\UseCases;

use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use App\Domain\Auth\DTOs\LoginDTO;

class LoginUseCase
{
    public function execute(LoginDTO $dto): void
    {
        if (!Auth::attempt([
            'email' => $dto->email,
            'password' => $dto->password,
        ], $dto->remember)) {
            throw ValidationException::withMessages([
                'email' => __('As credenciais fornecidas estão incorretas.'),
            ]);
        }

        session()->regenerate();
        
    }
}
