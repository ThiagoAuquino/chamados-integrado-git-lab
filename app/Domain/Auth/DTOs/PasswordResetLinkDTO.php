<?php

namespace App\Domain\Auth\DTOs;

use Illuminate\Http\Request;

class PasswordResetLinkDTO
{
    public function __construct(
        public string $email
    ) {}

    public static function fromRequest(Request $request): self
    {
        return new self(
            email: $request->input('email'),
        );
    }

    public function toArray(): array
    {
        return ['email' => $this->email];
    }
}
