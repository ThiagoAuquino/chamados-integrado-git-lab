<?php

namespace App\Domain\Auth\DTOs;

use App\Models\Users\User;

class UpdatePasswordDTO
{
    public function __construct(
        public User $user,
        public string $currentPassword,
        public string $newPassword
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            user: $data['user'],
            currentPassword: $data['current_password'],
            newPassword: $data['password'],
        );
    }
}
