<?php

namespace App\Domain\Usuario\UseCases;

use App\Domain\Usuario\Repositories\UserRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ListUserUseCase
{
    public function __construct(
        private UserRepositoryInterface $userRepository
    ) {}


    public function execute(array $filters, string $token): LengthAwarePaginator
    {
        return $this->userRepository->paginate(15); // ou usar filtros, etc.
    }
}
