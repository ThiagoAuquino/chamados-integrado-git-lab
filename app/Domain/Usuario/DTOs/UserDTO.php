<?php

namespace App\Domain\Usuario\DTOs;

use App\Domain\Usuario\Entities\User;

class UserDTO
{
    public function __construct(
        public int $id,
        public string $name,
        public string $email,
        public bool $active,
        public ?string $email_verified_at,
        public ?string $last_login_at,
        public ?array $roles = [],
        public ?array $permissions = [],
        public ?string $avatar_url = null,
    ) {}

    public static function fromModel(User $user): self
    {

        return new self(
            id: $user->id,
            name: $user->name,
            email: $user->email,
            active: $user->active,
            email_verified_at: $user->email_verified_at,
            last_login_at: $user->last_login_at,
            roles: $user->roles ?? [],
            permissions: $user->permissions ?? [],
            avatar_url: $user->avatar_url,
        );
    }

    public function toArray(): array
    {
        return [
            'id'                 => $this->id,
            'name'               => $this->name,
            'email'              => $this->email,
            'active'             => $this->active,
            'email_verified_at'  => $this->email_verified_at,
            'last_login_at'      => $this->last_login_at,
            'roles'              => $this->roles,
            'permissions'        => $this->permissions,
            'avatar_url'         => $this->avatar_url,
        ];
    }
}
