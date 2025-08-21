<?php 

namespace App\Domain\Auth\UseCases;

use App\Models\Users\User;
use Illuminate\Auth\Events\Verified;


class VerifyEmailUseCase
{
    public function execute(User $user): bool
    {
        if ($user->hasVerifiedEmail()) {
            return false;
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
            return true;
        }

        return false;
    }
}
