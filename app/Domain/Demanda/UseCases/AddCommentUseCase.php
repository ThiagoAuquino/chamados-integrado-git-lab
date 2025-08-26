<?php

namespace App\Domain\Demanda\UseCases;

use App\Domain\Demanda\Repositories\DemandaRepositoryInterface;

class AddCommentUseCase
{
    public function __construct(private DemandaRepositoryInterface $repository) {}

    public function execute(int $id, string $comment, int $userId): array
    {
        return $this->repository->addComment($id, $comment, $userId);
    }
}
