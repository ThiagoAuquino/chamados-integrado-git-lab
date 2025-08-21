<?php

namespace App\Domain\Usuario\Entities;

class User
{
    public function __construct(
        public readonly ?int $id,
        public string $name,
        public string $email,
        public bool $active,
        public ?string $password,
        public ?string $email_verified_at,
        public ?string $last_login_at,
        public ?array $roles,
        public ?array $permissions,
        public ?string $avatar_url,
        public ?string $remember_token,
        public string $created_at,
        public string $updated_at,
    ) {}
}
