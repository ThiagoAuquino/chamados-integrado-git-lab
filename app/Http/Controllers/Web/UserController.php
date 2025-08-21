<?php

namespace App\Http\Controllers\Web;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{

    private string $apiUrl;

    public function __construct()
    {
        $this->apiUrl = config('app.url') . '/api/users';
    }
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('dashboard'));
        }

        throw ValidationException::withMessages([
            'email' => 'As credenciais fornecidas não conferem com nossos registros.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    public function index(Request $request)
    {
        Gate::authorize('manage-users');
        $token = $request->user()->createToken('web')->plainTextToken;

        try {
            $response = Http::withToken($token)
                ->get($this->apiUrl, $request->query());

            if (!$response->successful()) {
                return back()->with('error', 'Erro ao carregar usuários');
            }

            $data = $response->json();

            $users = new LengthAwarePaginator(
                $data['data'] ?? [],
                $data['meta']['total'] ?? 0,
                $data['meta']['per_page'] ?? 15,
                $data['meta']['current_page'] ?? 1,
                [
                    'path' => $request->url(),
                    'query' => $request->query(),
                ]
            );

            return view('users.index', compact('users'));
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao carregar usuários');
        }
    }


    public function create()
    {
        Gate::authorize('manage-users');

        return view('users.create');
    }

    public function store(Request $request)
    {
        Gate::authorize('manage-users');

        $token = $request->user()->createToken('web')->plainTextToken;

        try {
            $response = Http::withToken($token)
                ->post($this->apiUrl, $request->all());

            if ($response->successful()) {
                return redirect()->route('users.index')
                    ->with('success', 'Usuário criado com sucesso!');
            }

            $errors = $response->json()['errors'] ?? ['Erro ao criar usuário'];
            return back()->withErrors($errors)->withInput();
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao criar usuário')->withInput();
        }
    }

    public function show(Request $request, int $id)
    {
        Gate::authorize('manage-users');

        $token = $request->user()->createToken('web')->plainTextToken;

        try {
            $response = Http::withToken($token)
                ->get($this->apiUrl . '/' . $id);

            if (!$response->successful()) {
                return redirect()->route('users.index')
                    ->with('error', 'Usuário não encontrado');
            }

            $user = $response->json();

            return view('users.show', compact('user'));
        } catch (\Exception $e) {
            return redirect()->route('users.index')
                ->with('error', 'Erro ao carregar usuário');
        }
    }

    public function edit(Request $request, int $id)
    {
        Gate::authorize('manage-users');

        $token = $request->user()->createToken('web')->plainTextToken;

        try {
            $response = Http::withToken($token)
                ->get($this->apiUrl . '/' . $id);

            if (!$response->successful()) {
                return redirect()->route('users.index')
                    ->with('error', 'Usuário não encontrado');
            }

            $user = $response->json();

            return view('users.edit', compact('user'));
        } catch (\Exception $e) {
            return redirect()->route('users.index')
                ->with('error', 'Erro ao carregar usuário');
        }
    }

    public function update(Request $request, int $id)
    {
        Gate::authorize('manage-users');

        $token = $request->user()->createToken('web')->plainTextToken;

        try {
            $response = Http::withToken($token)
                ->put($this->apiUrl . '/' . $id, $request->all());

            if ($response->successful()) {
                return redirect()->route('users.show', $id)
                    ->with('success', 'Usuário atualizado com sucesso!');
            }

            $errors = $response->json()['errors'] ?? ['Erro ao atualizar usuário'];
            return back()->withErrors($errors)->withInput();
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao atualizar usuário')->withInput();
        }
    }

    public function destroy(Request $request, int $id)
    {
        Gate::authorize('manage-users');

        $token = $request->user()->createToken('web')->plainTextToken;

        try {
            $response = Http::withToken($token)
                ->delete($this->apiUrl . '/' . $id);

            if ($response->successful()) {
                return redirect()->route('users.index')
                    ->with('success', 'Usuário excluído com sucesso!');
            }

            return redirect()->route('users.index')
                ->with('error', 'Erro ao excluir usuário');
        } catch (\Exception $e) {
            return redirect()->route('users.index')
                ->with('error', 'Erro ao excluir usuário');
        }
    }

    public function permissions(Request $request, int $id)
    {
        Gate::authorize('manage-users');

        $token = $request->user()->createToken('web')->plainTextToken;

        try {
            $response = Http::withToken($token)
                ->get($this->apiUrl . "/$id/permissions");

            if (!$response->successful()) {
                return redirect()->route('users.index')
                    ->with('error', 'Não foi possível carregar permissões do usuário.');
            }

            $permissions = $response->json();

            return view('users.permissions', compact('permissions', 'id'));
        } catch (\Exception $e) {
            return redirect()->route('users.index')
                ->with('error', 'Erro ao buscar permissões do usuário.');
        }
    }

    public function updatePermissions(Request $request, int $id)
    {
        Gate::authorize('manage-users');

        $request->validate([
            'permissions' => 'array',
            'permissions.*' => 'string',
        ]);

        $token = $request->user()->createToken('web')->plainTextToken;

        try {
            $response = Http::withToken($token)
                ->post($this->apiUrl . "/$id/assign-permission", [
                    'permissions' => $request->input('permissions'),
                ]);

            if (!$response->successful()) {
                return back()->with('error', 'Erro ao atualizar permissões.');
            }

            return redirect()->route('users.permissions', $id)
                ->with('success', 'Permissões atualizadas com sucesso!');
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao atualizar permissões.');
        }
    }

    public function bulkAction(Request $request)
    {
        Gate::authorize('bulk-operations');

        $request->validate([
            'user_ids' => 'required|array',
            'action' => 'required|string|in:activate,deactivate,delete',
        ]);

        $token = $request->user()->createToken('web')->plainTextToken;

        try {
            $response = Http::withToken($token)
                ->post($this->apiUrl . '/bulk-action', [
                    'user_ids' => $request->input('user_ids'),
                    'action' => $request->input('action'),
                ]);

            if (!$response->successful()) {
                return back()->with('error', 'Erro ao executar ação em lote.');
            }

            return redirect()->route('users.index')
                ->with('success', 'Ação em lote executada com sucesso!');
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao executar ação em lote.');
        }
    }
}
