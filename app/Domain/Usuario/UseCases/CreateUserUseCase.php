<?php

namespace App\Domain\Usuario\UseCases;


use App\Domain\Usuario\DTOs\CreateUserDTO;
use App\Domain\Usuario\Entities\User;
use App\Domain\Usuario\Repositories\UserRepositoryInterface;

class CreateUserUseCase
{
    public function __construct(
        private UserRepositoryInterface $userRepository
    ) {}

    public function execute(CreateUserDTO $dto): User
    {
        // Validar se email já existe
        if ($this->userRepository->findByEmail($dto->email)) {
            throw new \InvalidArgumentException('Email já está em uso');
        }

        return $this->userRepository->create($dto);
    }
}