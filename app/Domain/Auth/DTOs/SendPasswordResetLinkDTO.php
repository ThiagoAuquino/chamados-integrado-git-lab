<?php

namespace App\Domain\Auth\DTOs;

class SendPasswordResetLinkDTO
{
    public function __construct(
        public string $email
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            email: $data['email'] ?? ''
        );
    }
}
