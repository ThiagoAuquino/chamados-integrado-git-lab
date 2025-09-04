<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Demanda\Demanda;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class DemandaController extends Controller
{
    private string $apiUrl;

    public function __construct()
    {
        $this->apiUrl = config('app.url') . '/api/demandas';
    }

    public function importForm()
    {
        // if (Gate::denies('import_demandas')) {
        //     abort(403, 'Você não tem permissão para importar demandas.');
        // }

        return view('demandas.import');
    }

    /**
     * Processa o upload e envia o arquivo para a API
     */
    public function processImport(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx'
        ]);

        try {
            $response = Http::attach(
                'file',
                file_get_contents($request->file('file')->getRealPath()),
                $request->file('file')->getClientOriginalName()
            )->post(config('services.api.base_url') . '/demandas/import');

            if ($response->failed()) {
                return redirect()->back()->withErrors(['file' => 'Erro ao processar o arquivo.']);
            }

            $result = $response->json();

            return redirect()->back()->with('import_result', [
                'importadas' => $result['importadas'] ?? 0,
                'erros' => $result['erros'] ?? []
            ]);
        } catch (\Exception $e) {
            Log::error('Erro na importação de demandas: ' . $e->getMessage());

            return redirect()->back()->withErrors(['file' => 'Erro inesperado ao importar o arquivo.']);
        }
    }

    public function importProcess(Request $request)
    {
        if (Gate::denies('import_demandas')) {
            abort(403);
        }

        $request->validate([
            'arquivo' => 'required|file|mimes:xlsx,xls',
        ]);

        $file = $request->file('arquivo');

        // Envia o arquivo para a API
        $response = Http::attach(
            'arquivo',
            file_get_contents($file->getRealPath()),
            $file->getClientOriginalName()
        )->post(route('api.demandas.importar'), [
            'user_id' => Auth::id(),
        ]);

        if ($response->failed()) {
            return redirect()->back()->withErrors(['file' => 'Erro ao processar o arquivo.']);
        }


        if ($response->successful()) {
            return redirect()->route('demandas.import.form')->with('feedback', $response->json());
        }

        return redirect()->route('demandas.import.form')->withErrors(['arquivo' => 'Erro ao importar demandas.']);
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
        Gate::authorize('delete', Demanda::findOrFail($id));
        $token = $request->user()->createToken('web')->plainTextToken;
        Http::withToken($token)
            ->delete($this->apiUrl . '/' . $id);
        return redirect()->route('demandas.index')->with('success', 'Demanda excluída com sucesso!');
    }

    // Em App\Http\Controllers\Web\DemandaController.php

    public function kanban()
    {
        $demandas = [
            'backlog'        => Demanda::where('status', 'Backlog')->get(),
            'analise'        => Demanda::where('status', 'Análise')->get(),
            'desenvolvimento' => Demanda::where('status', 'Desenvolvimento')->get(),
            'teste'          => Demanda::where('status', 'Teste')->get(),
            'concluido'      => Demanda::where('status', 'Concluído')->get(),
        ];

        return view('kanban.index', compact('demandas'));
    }
}
