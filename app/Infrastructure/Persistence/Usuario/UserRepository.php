<?php

namespace App\Infrastructure\Persistence\Usuario;

use App\Domain\Usuario\Entities\User as UserEntiti;
use App\Models\Users\User;
use App\Domain\Usuario\Repositories\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;
use App\Domain\Usuario\DTOs\CreateUserDTO;
use App\Domain\Usuario\DTOs\UpdateUserDTO;
use Illuminate\Pagination\LengthAwarePaginator;

class UserRepository implements UserRepositoryInterface
{
    public function findById(int $id): ?UserEntiti
    {
        $model = User::with(['roles', 'permissions'])->find($id);

        if (!$model) {
            return null;
        }

        return new UserEntiti(
            id: $model->id,
            name: $model->name,
            email: $model->email,
            active: (bool) $model->active,
            password: $model->password,
            email_verified_at: $model->email_verified_at?->toDateTimeString(),
            last_login_at: $model->last_login_at?->toDateTimeString(),
            roles: $model->roles()->pluck('name')->toArray(),
            permissions: $model->permissions()->pluck('name')->toArray(),
            avatar_url: $model->avatar_url,
            remember_token: $model->remember_token,
            created_at: $model->created_at,
            updated_at: $model->updated_at,
        );
    }
    public function findByEmail(string $email): ?UserEntiti
    {
        return User::where('email', $email)->first();
    }

    public function create(CreateUserDTO $dto): UserEntiti
    {
        $user = User::create([
            'name' => $dto->name,
            'email' => $dto->email,
            'password' => Hash::make($dto->password),
            'active' => $dto->is_active,
            'email_verified_at' => $dto->email_verified ? now() : null,
        ]);

        $user->roles()->sync($dto->roles);
        $user->permissions()->sync($dto->permissions);

        return new UserEntiti(
            id: $user->id,
            name: $user->name,
            email: $user->email,
            active: (bool) $user->active,
            password: $user->password,
            email_verified_at: $user->email_verified_at?->toDateTimeString(),
            last_login_at: $user->last_login_at?->toDateTimeString(),
            roles: $user->roles()->pluck('name')->toArray(),
            permissions: $user->permissions()->pluck('name')->toArray(),
            avatar_url: $user->avatar_url,
            remember_token: $user->remember_token,
            created_at: $user->created_at,
            updated_at: $user->updated_at,
        );
    }

    public function update(int $id, UpdateUserDTO $dto): UserEntiti
    {
        $model = User::findOrFail($id); // <- Eloquent model

        $data = array_filter([
            'name' => $dto->name,
            'email' => $dto->email,
            'active' => $dto->active,
            'email_verified_at' => $dto->emailVerified ? now() : null,
        ], fn($value) => !is_null($value));

        if ($dto->password) {
            $data['password'] = Hash::make($dto->password);
        }

        $model->update($data);

        if (!is_null($dto->roles)) {
            $model->roles()->sync($dto->roles);
        }

        if (!is_null($dto->permissions)) {
            $model->permissions()->sync($dto->permissions);
        }

        // Retorna entidade de domínio atualizada
        return new UserEntiti(
            id: $model->id,
            name: $model->name,
            email: $model->email,
            active: (bool) $model->active,
            password: $model->password,
            email_verified_at: $model->email_verified_at?->toDateTimeString(),
            last_login_at: $model->last_login_at?->toDateTimeString(),
            roles: $model->roles()->pluck('name')->toArray(),
            permissions: $model->permissions()->pluck('name')->toArray(),
            avatar_url: $model->avatar_url,
            remember_token: $model->remember_token,
            created_at: $model->created_at,
            updated_at: $model->updated_at,
        );
    }

    public function delete(int $id): bool
    {
        return User::destroy($id) > 0;
    }

    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        $paginator = User::with(['roles', 'permissions'])->paginate($perPage);

        // Mapeia cada modelo para uma Entidade de Domínio
        $results = $paginator->getCollection()->map(function ($model) {
            return new UserEntiti(
                id: $model->id,
                name: $model->name,
                email: $model->email,
                active: (bool) $model->active,
                password: $model->password,
                email_verified_at: $model->email_verified_at?->toDateTimeString(),
                last_login_at: $model->last_login_at?->toDateTimeString(),
                roles: $model->roles->pluck('name')->toArray(),
                permissions: $model->permissions->pluck('name')->toArray(),
                avatar_url: $model->avatar_url,
                remember_token: $model->remember_token,
                created_at: $model->created_at,
                updated_at: $model->updated_at,
            );
        });

        return new LengthAwarePaginator(
            $results,
            $paginator->total(),
            $paginator->perPage(),
            $paginator->currentPage(),
            ['path' => request()->url(), 'query' => request()->query()]
        );
    }

    public function findByRole(string $role): array
    {
        return User::whereHas('roles', fn($q) => $q->where('name', $role))->get()->all();
    }

    public function syncPermissions(int $userId, array $permissions): UserEntiti
    {
        $model = User::findOrFail($userId);
        $model->permissions()->sync($permissions);

        return $this->findById($userId);
    }

    public function bulkAction(array $userIds, string $action): array
    {
        $result = [
            'affected' => 0,
            'errors' => [],
        ];

        foreach ($userIds as $id) {
            try {
                $user = User::findOrFail($id);

                match ($action) {
                    'activate' => $user->update(['active' => true]),
                    'deactivate' => $user->update(['active' => false]),
                    'delete' => $user->delete(),
                    default => throw new \InvalidArgumentException("Ação inválida: {$action}")
                };

                $result['affected']++;
            } catch (\Exception $e) {
                $result['errors'][] = ['id' => $id, 'message' => $e->getMessage()];
            }
        }

        return $result;
    }
}
