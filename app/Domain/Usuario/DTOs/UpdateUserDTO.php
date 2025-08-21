<?php

namespace App\Domain\Usuario\DTOs;

class UpdateUserDTO
{
    public function __construct(
        public ?string $name = null,
        public ?string $email = null,
        public ?string $password = null,
        public ?bool $active = null,
        public ?array $roles = [],
        public ?array $permissions = [],
        public ?bool $emailVerified = null,
        public ?bool $forcePasswordChange = null,
        public ?bool $removeAvatar = false,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'] ?? null,
            email: $data['email'] ?? null,
            password: $data['password'] ?? null,
            active: $data['active'] ?? null,
            roles: $data['roles'] ?? [],
            permissions: $data['permissions'] ?? [],
            emailVerified: $data['email_verified'] ?? null,
            forcePasswordChange: $data['force_password_change'] ?? null,
            removeAvatar: $data['remove_avatar'] ?? false,
        );
    }

    public function toArray(): array
    {
        return array_filter([
            'name' => $this->name,
            'email' => $this->email,
            'password' => $this->password,
            'active' => $this->active,
            'roles' => $this->roles,
            'permissions' => $this->permissions,
            'email_verified' => $this->emailVerified,
            'force_password_change' => $this->forcePasswordChange,
            'remove_avatar' => $this->removeAvatar,
        ], function ($value) {
            // Mantenha valores que não são null, inclusive false e arrays vazios
            return !is_null($value);
        });
    }
}
