<?php

namespace App\Domain\Permission\Entities;

class DeniedPermission
{
    public function __construct(
        public int $userId,
        public int $permissionId,
        public ?string $permissionName = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            userId: $data['user_id'],
            permissionId: $data['permission_id'],
            permissionName: $data['permission_name'] ?? null
        );
    }

    public function toArray(): array
    {
        return [
            'user_id' => $this->userId,
            'permission_id' => $this->permissionId,
            'permission_name' => $this->permissionName,
        ];
    }
}
