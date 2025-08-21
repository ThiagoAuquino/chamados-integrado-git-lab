<?php

namespace App\Domain\Auth\UseCases;

use App\Domain\Auth\DTOs\ResetPasswordDTO;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Str;
use App\Models\Users\User;

class ResetPasswordUseCase
{
    public function execute(ResetPasswordDTO $dto): string
    {
        return Password::reset(
            $dto->toArray(),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );
    }
}
