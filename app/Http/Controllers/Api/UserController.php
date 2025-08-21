<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

use App\Domain\Usuario\DTOs\CreateUserDTO;
use App\Domain\Usuario\DTOs\UpdateUserDTO;
use App\Domain\Usuario\DTOs\UserDTO;
use App\Domain\Usuario\UseCases\CreateUserUseCase;
use App\Domain\Usuario\UseCases\DeleteUserUseCase;
use App\Domain\Usuario\UseCases\ListUserUseCase;
use App\Domain\Usuario\UseCases\ShowUserUseCase;
use App\Domain\Usuario\UseCases\UpdateUserUseCase;
use App\Domain\Usuario\UseCases\BulkUserActionUseCase;

class UserController extends Controller
{

    public function __construct(
        protected CreateUserUseCase $createUser,
        protected UpdateUserUseCase $updateUser,
        protected DeleteUserUseCase $deleteUser,
        protected ShowUserUseCase $showUser,
        protected ListUserUseCase $listUser,
        protected BulkUserActionUseCase $bulkUserAction, // novo
    ) {}

    public function index(Request $request)
    {
        $filters = $request->query();
        $token = $request->bearerToken(); // se precisar
        $users = $this->listUser->execute($filters, $token);

        return response()->json($users);
    }

    public function store(Request $request)
    {
        $dto = CreateUserDTO::fromRequest($request);

        try {
            $user = $this->createUser->execute($dto);
            return response()->json($user, Response::HTTP_CREATED);
        } catch (\InvalidArgumentException $e) {
            return response()->json(['error' => $e->getMessage()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    public function show(int $id)
    {
        $user = $this->showUser->execute($id);

        if (!$user) {
            return response()->json(['error' => 'Usuário não encontrado'], Response::HTTP_NOT_FOUND);
        }

        return response()->json($user);
    }

    public function update(Request $request, int $id)
    {
        $dto = UpdateUserDTO::fromArray($request->all());

        $user = $this->updateUser->execute($id, $dto);

        return response()->json(UserDTO::fromModel($user));
    }

    public function destroy(int $id)
    {
        $deleted = $this->deleteUser->execute($id);

        return response()->json(['deleted' => $deleted]);
    }

    public function permissions(int $id)
    {
        $user = $this->showUser->execute($id);

        if (!$user) {
            return response()->json(['error' => 'Usuário não encontrado'], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'permissions' => $user->permissions,
        ]);
    }

    public function assignPermission(Request $request, int $id)
    {
        $request->validate([
            'permissions' => 'required|array',
            'permissions.*' => 'string',
        ]);

        try {
            // Assumindo que haverá um use case específico para isso
            $updatedUser = $this->updateUser->assignPermissions($id, $request->input('permissions'));

            return response()->json([
                'message' => 'Permissões atribuídas com sucesso',
                'user' => UserDTO::fromModel($updatedUser),
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao atribuir permissões'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function bulkAction(Request $request)
    {
        $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'integer',
            'action' => 'required|string|in:activate,deactivate,delete',
        ]);

        try {
            // Use case dedicado para ações em lote
            $result = $this->bulkUserAction->execute($request->input('user_ids'), $request->input('action'));

            return response()->json([
                'message' => 'Ação em lote executada com sucesso',
                'result' => $result
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao executar ação em lote'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
