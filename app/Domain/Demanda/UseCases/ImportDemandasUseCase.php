<?php

namespace App\Domain\Demanda\UseCases;

use App\Domain\Demanda\DTOs\CreateDemandaDTO;
use App\Domain\Demanda\UseCases\CreateDemandaUseCase;
use App\Domain\DemandaLog\DTOs\DemandaLogDTO;
use App\Models\DemandaLog;
use App\Exceptions\ValidationException;
use App\Models\Users\User;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class ImportDemandasUseCase
{
    public function __construct(
        private CreateDemandaUseCase $createDemandaUseCase
    ) {}

    public function execute(string $path, User $user): array
    {
        $rows = Excel::toArray([], $path)[0]; // primeira aba
        $importadas = [];
        $erros = [];

        foreach ($rows as $index => $row) {
            $linha = $index + 1;

            // Ignora cabeçalho
            if ($linha === 1 || empty(array_filter($row))) continue;

            $dados = $this->mapRowToData($row);
            $validator = Validator::make($dados, [
                'produto' => 'required|string',
                'chamado' => 'nullable|string',
                'descricao' => 'required|string',
                'tipo' => 'required|in:bug,melhoria,funcionalidade',
                'data_previsao' => 'required|date',
                'cliente' => 'required|string',
                'status' => 'required|in:em_branco,analise,em_execucao,em_testes,validacao,entregue',
            ]);

            if ($validator->fails()) {
                $erros[] = "Linha {$linha}: " . implode(', ', $validator->errors()->all());
                continue;
            }

            try {
                $dto = new CreateDemandaDTO(
                    produto: $dados['produto'],
                    chamado: $dados['chamado'],
                    descricao: $dados['descricao'],
                    tipo: $dados['tipo'],
                    data_previsao: $dados['data_previsao'],
                    cliente: $dados['cliente'],
                    status: $dados['status'],
                    responsavel_id: null,
                    priority: 'verde',
                    order: 0
                );

                $demanda = $this->createDemandaUseCase->execute($dto, $user);
                $importadas[] = $demanda->id;

                DemandaLogDTO::create([
                    'demanda_id' => $demanda->id,
                    'user_id' => $user->id,
                    'action' => 'importada',
                    'description' => 'Demanda importada via planilha',
                    'created_at' => now(),
                ]);

            } catch (\Throwable $e) {
                $erros[] = "Linha {$linha}: Erro inesperado ({$e->getMessage()})";
            }
        }

        return compact('importadas', 'erros');
    }

    private function mapRowToData(array $row): array
    {
        return [
            'produto' => $row[0] ?? null,
            'chamado' => $row[1] ?? null,
            'descricao' => $row[2] ?? null,
            'tipo' => $row[3] ?? null,
            'data_previsao' => $row[4] ?? null,
            'cliente' => $row[5] ?? null,
            'status' => $row[6] ?? null,
        ];
    }
}
