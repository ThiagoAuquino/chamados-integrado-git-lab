<?php

namespace App\Domain\Usuario\DTOs;

use Illuminate\Http\Request;

class CreateUserDTO
{


    public function __construct(
        public string $name,
        public string $email,
        public string $password,
        public ?string $phone = null,
        public ?string $department = null,
        public ?string $bio = null,
        public array $roles = [],
        public array $permissions = [],
        public bool $is_active = true,
        public bool $email_verified = false,
        public bool $force_password_change = false,
        public ?\Illuminate\Http\UploadedFile $avatar = null,
    ) {}

    public static function fromRequest(Request $request): self
    {
        return new self(
            $request->name,
            $request->email,
            $request->password,
            $request->phone,
            $request->department,
            $request->bio,
            $request->input('roles', []),
            $request->input('permissions', []),
            $request->boolean('is_active', true),
            $request->boolean('email_verified', false),
            $request->boolean('force_password_change', false),
            $request->file('avatar')
        );
    }

    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'] ?? '',
            email: $data['email'] ?? '',
            password: $data['password'] ?? '',
            is_active: $data['is_active'] ?? true,
            roles: $data['roles'] ?? [],
            permissions: $data['permissions'] ?? [],
            email_verified: $data['email_verified'] ?? false,
            force_password_change: $data['force_password_change'] ?? false,
        );
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
            'password' => $this->password,
            'active' => $this->is_active,
            'roles' => $this->roles,
            'permissions' => $this->permissions,
            'email_verified' => $this->email_verified,
            'force_password_change' => $this->force_password_change,
        ];
    }

}

