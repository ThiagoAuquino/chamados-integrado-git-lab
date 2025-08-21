<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Gate;

class DemandaController extends Controller
{
    private string $apiUrl;

    public function __construct()
    {
        $this->apiUrl = config('app.url') . '/api/demandas';
    }

    public function index(Request $request)
    {
        Gate::authorize('viewAny', \App\Models\Demanda\Demanda::class);

        $token = $request->user()->createToken('web')->plainTextToken;
        $filters = $request->only(['status', 'responsavel_id', 'tipo', 'cliente']);

        try {
            $response = Http::withToken($token)
                ->get($this->apiUrl, $filters);

            $demandas = $response->successful() ? $response->json() : ['data' => []];

            // Buscar usuários para filtro
            $users = Http::withToken($token)
                ->get(config('app.url') . '/api/users')
                ->json();

            return view('demandas.index', compact('demandas', 'users', 'filters'));
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao carregar demandas');
        }
    }

    public function create()
    {
        Gate::authorize('create', \App\Models\Demanda\Demanda::class);

        return view('demandas.create');
    }

    public function store(Request $request)
    {
        Gate::authorize('create', \App\Models\Demanda\Demanda::class);

        $token = $request->user()->createToken('web')->plainTextToken;

        try {
            $response = Http::withToken($token)
                ->post($this->apiUrl, $request->all());

            if ($response->successful()) {
                return redirect()->route('demandas.index')
                    ->with('success', 'Demanda excluída com sucesso!');
            }

            return redirect()->route('demandas.index')
                ->with('error', 'Erro ao excluir demanda');
        } catch (\Exception $e) {
            return redirect()->route('demandas.index')
                ->with('error', 'Erro ao excluir demanda');
        }
    }

    public function bulkUpdate(Request $request)
    {
        Gate::authorize('bulk-operations');

        $token = $request->user()->createToken('web')->plainTextToken;
        
        try {
            $response = Http::withToken($token)
                ->post($this->apiUrl . '/bulk-update', [
                    'ids' => $request->input('ids', []),
                    'action' => $request->input('action'),
                    'value' => $request->input('value')
                ]);

            if ($response->successful()) {
                return back()->with('success', 'Operação em lote realizada com sucesso!');
            }

            return back()->with('error', 'Erro na operação em lote');
        } catch (\Exception $e) {
            return back()->with('error', 'Erro na operação em lote');
        }
    }

        public function show(Request $request, int $id)
    {
        $token = $request->user()->createToken('web')->plainTextToken;

        try {
            $response = Http::withToken($token)
                ->get($this->apiUrl . '/' . $id);

            if (!$response->successful()) {
                return redirect()->route('demandas.index')
                    ->with('error', 'Demanda não encontrada');
            }

            $demanda = $response->json();

            // Autorizar visualização específica
            $demandaModel = \App\Models\Demanda\Demanda::find($id);
            Gate::authorize('view', $demandaModel);

            // Buscar histórico
            $history = Http::withToken($token)
                ->get($this->apiUrl . '/' . $id . '/history')
                ->json();

            return view('demandas.show', compact('demanda', 'history'));
        } catch (\Exception $e) {
            return redirect()->route('demandas.index')
                ->with('error', 'Erro ao carregar demanda');
        }
    }

    public function edit(Request $request, int $id)
    {
        $token = $request->user()->createToken('web')->plainTextToken;

        try {
            $response = Http::withToken($token)
                ->get($this->apiUrl . '/' . $id);

            if (!$response->successful()) {
                return redirect()->route('demandas.index')
                    ->with('error', 'Demanda não encontrada');
            }

            $demanda = $response->json();
            $demandaModel = \App\Models\Demanda\Demanda::find($id);

            Gate::authorize('update', $demandaModel);

            return view('demandas.edit', compact('demanda'));
        } catch (\Exception $e) {
            return redirect()->route('demandas.index')
                ->with('error', 'Erro ao carregar demanda');
        }
    }

    public function update(Request $request, int $id)
    {
        $demandaModel = \App\Models\Demanda\Demanda::find($id);
        Gate::authorize('update', $demandaModel);

        $token = $request->user()->createToken('web')->plainTextToken;

        try {
            $response = Http::withToken($token)
                ->put($this->apiUrl . '/' . $id, $request->all());

            if ($response->successful()) {
                return redirect()->route('demandas.show', $id)
                    ->with('success', 'Demanda atualizada com sucesso!');
            }

            $errors = $response->json()['errors'] ?? ['Erro ao atualizar demanda'];
            return back()->withErrors($errors)->withInput();
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao atualizar demanda')->withInput();
        }
    }

    public function approve(Request $request, int $id)
    {
        $demandaModel = \App\Models\Demanda\Demanda::find($id);
        Gate::authorize('approve', $demandaModel);

        $token = $request->user()->createToken('web')->plainTextToken;

        try {
            $response = Http::withToken($token)
                ->post($this->apiUrl . '/' . $id . '/approve');

            if ($response->successful()) {
                return back()->with('success', 'Demanda aprovada com sucesso!');
            }

            return back()->with('error', 'Erro ao aprovar demanda');
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao aprovar demanda');
        }
    }

    public function changeStatus(Request $request, int $id)
    {
        $demandaModel = \App\Models\Demanda\Demanda::find($id);
        Gate::authorize('changeStatus', $demandaModel);

        $token = $request->user()->createToken('web')->plainTextToken;

        try {
            $response = Http::withToken($token)
                ->post($this->apiUrl . '/' . $id . '/change-status', [
                    'status' => $request->input('status')
                ]);

            if ($response->successful()) {
                return back()->with('success', 'Status alterado com sucesso!');
            }

            return back()->with('error', 'Erro ao alterar status');
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao alterar status');
        }
    }

    public function destroy(Request $request, int $id)
    {
        $demandaModel = \App\Models\Demanda\Demanda::find($id);
        Gate::authorize('delete', $demandaModel);

        $token = $request->user()->createToken('web')->plainTextToken;

        try {
            $response = Http::withToken($token)
                ->delete($this->apiUrl . '/' . $id);

            if ($response->successful()) {
                return redirect()->route;
            }
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}