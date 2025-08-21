<?php

namespace App\Domain\Auth\UseCases;


use App\Domain\Auth\DTOs\SendPasswordResetLinkDTO;
use Illuminate\Support\Facades\Password;

class SendPasswordResetLinkUseCase
{
    public function execute(SendPasswordResetLinkDTO $dto): string
    {
        return Password::sendResetLink([
            'email' => $dto->email
        ]);
    }
}
