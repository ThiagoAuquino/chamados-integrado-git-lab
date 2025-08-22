<?php

namespace App\Http\Controllers\Api;

use App\Domain\Demanda\UseCases\ImportDemandasUseCase;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class DemandaImportController extends Controller
{
    public function __construct(
        private ImportDemandasUseCase $importDemandasUseCase
    ) {}

    public function __invoke(Request $request)
    {
        if (Gate::denies('import_demandas')) {
            return response()->json(['error' => 'Não autorizado'], 403);
        }

        $request->validate([
            'file' => 'required|file|mimes:xlsx,csv'
        ]);

        $path = $request->file('file')->store('imports');

        $resultado = $this->importDemandasUseCase->execute(storage_path('app/' . $path), $request->user());

        return response()->json($resultado);
    }
}
