<?php

namespace App\Domain\Auth\UseCases;

use Illuminate\Contracts\Auth\MustVerifyEmail;

class SendEmailVerificationUseCase
{
    public function execute(MustVerifyEmail $user): void
    {
        if ($user->hasVerifiedEmail()) {
            return;
        }

        $user->sendEmailVerificationNotification();
    }
}
