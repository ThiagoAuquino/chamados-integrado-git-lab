<?php

namespace App\Domain\Demanda\UseCases;

use App\Domain\Demanda\DTOs\CreateDemandaDTO;
use App\Domain\Demanda\UseCases\CreateDemandaUseCase;
use App\Domain\DemandaLog\DTOs\CreateDemandaLogDTO;
use App\Domain\DemandaLog\UseCases\CreateLogUseCase;
use App\Models\Users\User;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class ImportDemandasUseCase
{
    public function __construct(
        private CreateDemandaUseCase $createDemandaUseCase,
        private CreateLogUseCase $createLogUseCase
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
                'produto'        => 'required|string',
                'chamado'        => 'nullable|string',
                'descricao'      => 'required|string',
                'tipo'           => 'required|in:bug,melhoria,funcionalidade',
                'data_previsao'  => 'required|date',
                'cliente'        => 'required|string',
                'status'         => 'required|in:em_branco,analise,em_execucao,em_testes,validacao,entregue',
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
                    tipo_id: $this->mapTipoToId($dados['tipo']),
                    data_previsao: $dados['data_previsao'],
                    cliente: $dados['cliente'],
                    status_id: $this->mapStatusToId($dados['status']),
                    responsavel_id: null,
                    priority: 'verde',
                    order: 0
                );

                $demanda = $this->createDemandaUseCase->execute($dto, $user);
                $importadas[] = $demanda->id;

                $logDTO = new CreateDemandaLogDTO(
                    demanda_id: $demanda->id,
                    user_id: $user->id,
                    action: 'importada',
                    description: 'Demanda importada via planilha',
                    created_at: now()->toDateTimeString()
                );

                $this->createLogUseCase->execute($logDTO);

            } catch (\Throwable $e) {
                $erros[] = "Linha {$linha}: Erro inesperado ({$e->getMessage()})";
            }
        }

        return compact('importadas', 'erros');
    }

    private function mapRowToData(array $row): array
    {
        return [
            'produto'        => $row[0] ?? null,
            'chamado'        => $row[1] ?? null,
            'descricao'      => $row[2] ?? null,
            'tipo'           => $row[3] ?? null,
            'data_previsao'  => $row[4] ?? null,
            'cliente'        => $row[5] ?? null,
            'status'         => $row[6] ?? null,
        ];
    }

    private function mapStatusToId(string $status): int
    {
        return match ($status) {
            'em_branco'   => 1,
            'analise'     => 2,
            'em_execucao' => 3,
            'em_testes'   => 4,
            'validacao'   => 5,
            'entregue'    => 6,
            default       => throw new \InvalidArgumentException("Status inválido: {$status}")
        };
    }

    private function mapTipoToId(string $tipo): int
    {
        return match (strtolower($tipo)) {
            'melhoria'       => 1,
            'sugestão'       => 2,
            'correção'       => 3,
            'novidade'       => 4,
            'problema'       => 5,
            'bug'            => 3, // ou outro mapeamento que você quiser
            'funcionalidade' => 4, // idem
            default          => throw new \InvalidArgumentException("Tipo inválido: {$tipo}")
        };
    }
}
