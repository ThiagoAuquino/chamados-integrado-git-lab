<?php

namespace App\Domain\Auth\DTOs;

use Illuminate\Http\Request;

class LoginDTO
{
    public function __construct(
        public string $email,
        public string $password,
        public bool $remember
    ) {}

    public static function fromRequest(Request $request): self
    {
        return new self(
            $request->input('email'),
            $request->input('password'),
            $request->boolean('remember', false)
        );
    }
}
