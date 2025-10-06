<?php

namespace App\Http\Controllers\Api;

use App\Domain\Demanda\DTOs\CreateDemandaDTO;
use App\Domain\Demanda\DTOs\UpdateDemandaDTO;
use App\Domain\Demanda\UseCases\AddCommentUseCase;
use App\Domain\Demanda\UseCases\ApproveDemandaUseCase;
use App\Domain\Demanda\UseCases\BulkUpdateDemandaUseCase;
use App\Domain\Demanda\UseCases\ChangeStatusDemandaUseCase;
use App\Domain\Demanda\UseCases\CreateDemandaUseCase;
use App\Domain\Demanda\UseCases\DeleteDemandaUseCase;
use App\Domain\Demanda\UseCases\ExportDemandaUseCase;
use App\Domain\Demanda\UseCases\ListDemandaUseCase;
use App\Domain\Demanda\UseCases\ShowDemandaUseCase;
use App\Domain\Demanda\UseCases\UpdateDemandaUseCase;
use App\Domain\Demanda\UseCases\UpdatePriorityUseCase;
use App\Domain\DemandaLog\UseCases\GetDemandaHistoryUseCase;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;


use App\Http\Requests\Demanda\StoreDemandaRequest;
use App\Http\Requests\Demanda\UpdateDemandaRequest;
use App\Models\Demanda\Demanda;

class DemandaController extends Controller
{

    use AuthorizesRequests;

    public function __construct(
        private ListDemandaUseCase $listDemandaUseCase,
        private CreateDemandaUseCase $createDemandaUseCase,
        private UpdateDemandaUseCase $updateDemandaUseCase,
        private DeleteDemandaUseCase $deleteDemandaUseCase,
        private ApproveDemandaUseCase $approveDemandaUseCase,
        private ChangeStatusDemandaUseCase $changeStatusDemandaUseCase,
        private ShowDemandaUseCase $showDemandaUseCase,
        private GetDemandaHistoryUseCase $getDemandaHistoryUseCase,
        private BulkUpdateDemandaUseCase $bulkUpdateDemandaUseCase,
        private UpdatePriorityUseCase $updatePriorityUseCase,
        private AddCommentUseCase $addCommentUseCase,
        private ExportDemandaUseCase $exportDemandaUseCase
    ) {}

    public function index(ListDemandaUseCase $useCase)
    {
        $this->authorize('viewAny', Demanda::class);

        return response()->json($useCase->execute());
    }

    private function getPriorityByOrder(int $order): string
    {
        return match ($order) {
            1 => 'vermelha',
            2 => 'amarela',
            3 => 'laranja',
            default => 'verde',
        };
    }

    public function show($id, ShowDemandaUseCase $useCase)
    {
        $demanda = Demanda::findOrFail($id);
        $this->authorize('view', $demanda);

        return response()->json($useCase->execute($id));
    }

    public function store(StoreDemandaRequest $request, CreateDemandaUseCase $useCase)
    {
        $this->authorize('create', Demanda::class);

        $validated = $request->validated();
        $dto = new CreateDemandaDTO(...$validated);
        $demanda = $useCase->execute($dto);

        return response()->json($demanda, 201);
    }

    public function update(UpdateDemandaRequest $request, $id, UpdateDemandaUseCase $useCase)
    {
        $demanda = Demanda::findOrFail($id);
        $this->authorize('update', $demanda);

        $validated = $request->validated();
        $dto = UpdateDemandaDTO::fromArray($validated);
        $ok = $useCase->execute($id, $dto);

        if (!$ok) {
            return response()->json(['message' => 'Demanda não encontrada'], 404);
        }

        return response()->json(['message' => 'Atualizado com sucesso']);
    }

    public function destroy($id, DeleteDemandaUseCase $useCase)
    {
        $demanda = Demanda::findOrFail($id);
        $this->authorize('delete', $demanda);

        $ok = $useCase->execute($id);

        if (!$ok) {
            return response()->json(['message' => 'Demanda não encontrada'], 404);
        }

        return response()->json(['message' => 'Excluída com sucesso']);
    }

    public function approve($id, ApproveDemandaUseCase $useCase)
    {
        $demanda = Demanda::findOrFail($id);
        $this->authorize('approve', $demanda);

        $ok = $useCase->execute($id);

        if (!$ok) {
            return response()->json(['message' => 'Demanda não encontrada ou erro ao aprovar'], 400);
        }

        return response()->json(['message' => 'Demanda aprovada']);
    }

    public function changeStatus(Request $request, $id, ChangeStatusDemandaUseCase $useCase)
    {
        $demanda = Demanda::findOrFail($id);
        $this->authorize('changeStatus', $demanda);

        $validated = $request->validate([
            'status' => 'required|in:em_branco,analise,em_execucao,em_testes,validacao,entregue',
        ]);

        $ok = $useCase->execute($id, $validated['status']);

        if (!$ok) {
            return response()->json(['message' => 'Demanda não encontrada ou erro ao alterar status'], 400);
        }

        return response()->json(['message' => 'Status alterado']);
    }

    /**
     * Estatísticas gerais das demandas
     */
    public function stats(Request $request)
    {
        try {
            $stats = $this->listDemandaUseCase->getStats();

            return response()->json([
                'data' => $stats,
                'message' => 'Estatísticas obtidas com sucesso'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erro ao obter estatísticas',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Histórico de uma demanda específica
     */
    public function history(Request $request, int $id)
    {
        try {
            $history = $this->getDemandaHistoryUseCase->execute($id);

            return response()->json([
                'data' => $history,
                'message' => 'Histórico obtido com sucesso'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erro ao obter histórico',
                'message' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Operações em lote
     */
    public function bulkUpdate(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer|exists:demandas,id',
            'action' => 'required|string|in:assign,change_status,update_priority',
            'value' => 'required'
        ]);

        try {
            $result = $this->bulkUpdateDemandaUseCase->execute(
                $request->input('ids'),
                $request->input('action'),
                $request->input('value'),
                auth()->id()
            );

            return response()->json([
                'data' => $result,
                'message' => 'Operação em lote realizada com sucesso'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erro na operação em lote',
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function bulkReorder(Request $request)
    {
        $this->authorize('update', Demanda::class);

        $data = $request->validate([
            'demandas' => 'required|array',
            'demandas.*.id' => 'required|integer|exists:demandas,id',
            'demandas.*.order' => 'required|integer|min:0'
        ]);

        foreach ($data['demandas'] as $item) {
            $priority = $this->getPriorityByOrder($item['order']);

            $this->updatePriorityUseCase->execute(
                $item['id'],
                $priority,
                $item['order'],
                auth()->id()
            );
        }

        return response()->json(['message' => 'Reordenação realizada com sucesso.']);
    }




    /**
     * Demandas pendentes de aprovação
     */
    public function pending(Request $request)
    {
        try {
            $demandas = $this->listDemandaUseCase->getPending();

            return response()->json([
                'data' => $demandas,
                'message' => 'Demandas pendentes obtidas com sucesso'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erro ao obter demandas pendentes',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Demandas em atraso
     */
    public function overdue(Request $request)
    {
        try {
            $demandas = $this->listDemandaUseCase->getOverdue();

            return response()->json([
                'data' => $demandas,
                'message' => 'Demandas em atraso obtidas com sucesso'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erro ao obter demandas em atraso',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Demandas por usuário
     */
    public function byUser(Request $request, int $userId)
    {
        try {
            $demandas = $this->listDemandaUseCase->getByUser($userId);

            return response()->json([
                'data' => $demandas,
                'message' => 'Demandas do usuário obtidas com sucesso'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erro ao obter demandas do usuário',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Atualizar prioridade
     */
    public function updatePriority(Request $request)
    {
        $request->validate([
            'demanda_id' => 'required|integer|exists:demandas,id',
            'priority' => 'required|string|in:verde,amarelo,laranja,vermelho',
            'order' => 'integer|min:0'
        ]);

        try {
            $demanda = $this->updatePriorityUseCase->execute(
                $request->input('demanda_id'),
                $request->input('priority'),
                $request->input('order', 0),
                auth()->id()
            );

            return response()->json([
                'data' => $demanda,
                'message' => 'Prioridade atualizada com sucesso'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erro ao atualizar prioridade',
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Adicionar comentário
     */
    public function addComment(Request $request, int $id)
    {
        $request->validate([
            'comment' => 'required|string|max:1000'
        ]);

        try {
            $comment = $this->addCommentUseCase->execute(
                $id,
                $request->input('comment'),
                auth()->id()
            );

            return response()->json([
                'data' => $comment,
                'message' => 'Comentário adicionado com sucesso'
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erro ao adicionar comentário',
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Exportar demandas
     */
    public function export(Request $request)
    {
        try {
            $filters = $request->only(['status', 'responsavel_id', 'tipo', 'cliente', 'priority']);

            $export = $this->exportDemandaUseCase->execute($filters);

            return response()->json([
                'data' => $export,
                'message' => 'Exportação gerada com sucesso'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erro ao exportar demandas',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
