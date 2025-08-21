<?php

namespace App\Domain\Auth\DTOs;

use Illuminate\Http\Request;

class ResetPasswordDTO
{
    public function __construct(
        public string $email,
        public string $token,
        public string $password,
        public string $passwordConfirmation
    ) {}

    public static function fromRequest(Request $request): self
    {
        return new self(
            email: $request->input('email'),
            token: $request->input('token'),
            password: $request->input('password'),
            passwordConfirmation: $request->input('password_confirmation')
        );
    }

    public function toArray(): array
    {
        return [
            'email' => $this->email,
            'token' => $this->token,
            'password' => $this->password,
            'password_confirmation' => $this->passwordConfirmation,
        ];
    }
}
