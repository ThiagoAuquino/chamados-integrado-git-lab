<?php

namespace App\Domain\Usuario\UseCases;

use App\Domain\Usuario\Repositories\UserRepositoryInterface;
use App\Domain\Usuario\DTOs\UserDTO;
use App\Models\User;

class ShowUserUseCase
{
    public function __construct(
        private UserRepositoryInterface $userRepository
    ) {}

    public function execute(int $id): ?UserDTO
    {
        $user = $this->userRepository->findById($id);

        if (!$user) {
            return null;
        }

        return UserDTO::fromModel($user);
    }
}
