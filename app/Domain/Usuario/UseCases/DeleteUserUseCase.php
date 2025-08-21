<?php

namespace App\Domain\Usuario\UseCases;

use App\Domain\Usuario\Repositories\UserRepositoryInterface;

class DeleteUserUseCase
{
    public function __construct(private UserRepositoryInterface $repository) {}

    public function execute(int $id): bool
    {
        return $this->repository->delete($id);
    }
}