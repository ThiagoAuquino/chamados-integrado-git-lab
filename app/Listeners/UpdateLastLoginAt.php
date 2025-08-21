<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;

class UpdateLastLoginAt
{
    public function handle(Login $event): void
    {
        // Garante que o usuário seja Eloquent model
        if ($event->user instanceof \App\Models\Users\User) {
            $event->user->update([
                'last_login_at' => now(),
            ]);
        }
    }
}
