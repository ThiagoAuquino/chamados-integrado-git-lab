<?php

namespace App\Domain\Permission\UseCases;

use App\Domain\Auth\Services\AuthServiceInterface;
use App\Domain\User\Repositories\UserRepositoryInterface;

class CheckPermissionUseCase
{
    public function __construct(
        private AuthServiceInterface $authService,
        private UserRepositoryInterface $userRepository
    ) {}

    public function execute(int $userId, string $permission): bool
    {
        $user = $this->userRepository->findById($userId);
        
        if (!$user) {
            return false;
        }

        return $this->authService->hasPermission($userId, $permission);
    }
}