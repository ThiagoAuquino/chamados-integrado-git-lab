<?php

namespace App\Policies\Demanda;

use App\Domain\Auth\Services\AuthServiceInterface;
use App\Models\Demanda\Demanda;
use App\Models\Users\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DemandaPolicy
{
    public function __construct(
        private AuthServiceInterface $authService
    ) {}
    public function viewAny(User $user): bool
    {
        return $this->authService->hasPermission($user->id, 'view_demandas');
    }
    public function view(User $user, Demanda $demanda): bool
    {
        // Pode ver se tem permissão geral OU se é o responsável
        return $this->authService->hasPermission($user->id, 'view_demandas') ||
            $demanda->responsavel_id === $user->id;
    }
    public function create(User $user): bool
    {
        return $this->authService->hasPermission($user->id, 'create_demandas');
    }
    public function update(User $user, Demanda $demanda): bool
    {
        // Gestor pode editar qualquer demanda
        if ($this->authService->hasPermission($user->id, 'update_any_demanda')) {
            return true;
        }
        // Executor só pode editar suas próprias demandas
        return $this->authService->hasPermission($user->id, 'update_own_demanda') &&
            $demanda->responsavel_id === $user->id;
    }
    public function delete(User $user, Demanda $demanda): bool
    {
        return $this->authService->hasPermission($user->id, 'delete_demandas');
    }
    public function approve(User $user, Demanda $demanda): bool
    {
        return $this->authService->hasPermission($user->id, 'approve_demandas') &&
            $demanda->status === 'em_branco';
    }
    public function changeStatus(User $user, Demanda $demanda): bool
    {
        // Gestor pode alterar qualquer status
        if ($this->authService->hasPermission($user->id, 'change_any_status')) {
            return true;
        }
        // Executor só pode alterar status das suas demandas
        return $this->authService->hasPermission($user->id, 'change_own_status') &&
            $demanda->responsavel_id === $user->id;
    }
    public function assign(User $user, Demanda $demanda): bool
    {
        return $this->authService->hasPermission($user->id, 'assign_demandas') &&
            $demanda->status !== 'em_branco';
    }
}
