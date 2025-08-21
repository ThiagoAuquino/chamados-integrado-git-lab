<?php

namespace App\Domain\Auth\UseCases;

use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Domain\Auth\DTOs\UpdatePasswordDTO;

class UpdatePasswordUseCase
{
    public function execute(UpdatePasswordDTO $dto): void
    {
        if (!Hash::check($dto->currentPassword, $dto->user->password)) {
            throw ValidationException::withMessages([
                'current_password' => ['A senha atual está incorreta.'],
            ])->errorBag('updatePassword');
        }

        $dto->user->update([
            'password' => Hash::make($dto->newPassword),
        ]);
    }
}
