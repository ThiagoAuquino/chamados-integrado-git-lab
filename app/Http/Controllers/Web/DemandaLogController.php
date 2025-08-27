<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Domain\DemandaLog\UseCases\GetDemandaHistoryUseCase;
use Illuminate\Http\Request;

class DemandaLogController extends Controller
{
    public function __construct(
        private GetDemandaHistoryUseCase $getDemandaHistoryUseCase
    ) {}

    /**
     * Exibe o histórico de uma demanda via interface web
     */
    public function show(int $id)
    {
        try {
            $logs = $this->getDemandaHistoryUseCase->execute($id);

            return view('demandas.logs', [
                'logs' => $logs,
                'demanda_id' => $id
            ]);
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withErrors('Erro ao carregar histórico: ' . $e->getMessage());
        }
    }
}
