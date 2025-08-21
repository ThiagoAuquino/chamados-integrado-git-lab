<?php

namespace App\Domain\Usuario\UseCases;

use App\Domain\Usuario\Repositories\UserRepositoryInterface;

class BulkUserActionUseCase
{
    public function __construct(private UserRepositoryInterface $repository) {}

    public function execute(array $userIds, string $action): array
    {
        return $this->repository->bulkAction($userIds, $action);
    }
}
