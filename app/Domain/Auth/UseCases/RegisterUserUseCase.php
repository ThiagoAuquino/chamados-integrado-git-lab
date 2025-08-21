<?php

namespace App\Domain\Auth\UseCases;

use App\Domain\Auth\DTOs\RegisterUserDTO;
use App\Models\Users\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;

class RegisterUserUseCase
{
    public function execute(RegisterUserDTO $dto): User
    {
        $user = User::create([
            'name'     => $dto->name,
            'email'    => $dto->email,
            'password' => Hash::make($dto->password),
            'active'   => true, // padrão ativo
        ]);

        event(new Registered($user));

        return $user;
    }
}
