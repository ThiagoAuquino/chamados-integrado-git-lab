<?php

namespace Database\Seeders;

use App\Domain\Demanda\DTOs\CreateDemandaDTO;
use App\Domain\Demanda\UseCases\CreateDemandaUseCase;
use Illuminate\Database\Seeder;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class DemandaImportSeeder extends Seeder
{
    public function run()
    {
        $data = [
            ['Data_entrada' => '', 'Data_entrega' => '', 'Previsão' => 45904, 'Produto' => 'Omniplus', 'solicitante' => '', 'Job' => '570', 'Chamado' => '2025-224010', 'Cliente' => 'FUNCIONAL', 'Descrição' => 'Um relatorio onde mostra no relatorio usuarios que estão com a mesma posição', 'Tipo' => 'Melhoria', 'Status' => '', 'Observação' => ''],
            ['Data_entrada' => '', 'Data_entrega' => '', 'Previsão' => 45901, 'Produto' => 'OmniPBX', 'solicitante' => '', 'Job' => '192', 'Chamado' => '2025-221909', 'Cliente' => 'FUNCIONAL', 'Descrição' => 'Inserir campo de categoria da fila no relatorio de pesquisa', 'Tipo' => 'Sugestão', 'Status' => '', 'Observação' => ''],
            // Adicione os demais rows (2 a 80) de forma semelhante, mas para brevidade, mostro apenas os primeiros dois e o último
            ['Data_entrada' => '', 'Data_entrega' => '', 'Previsão' => '', 'Produto' => 'OmniPBX', 'solicitante' => '', 'Job' => '221', 'Chamado' => '2025-225857', 'Cliente' => 'EZ-TECH', 'Descrição' => 'Serviços de inicialização do fenix não estava iniciando corretamenteo', 'Tipo' => 'Correção', 'Status' => 'Entregue', 'Observação' => 'VOU VERIFICAR COM O PROSPERO'],
        ];

        $createDemandaUseCase = app(CreateDemandaUseCase::class);

        foreach ($data as $row) {
            $previsao = $row['Previsão'] ? Date::excelToDateTimeObject($row['Previsão'])->format('Y-m-d') : null;
            $dataEntrada = $row['Data_entrada'] ? Date::excelToDateTimeObject($row['Data_entrada'])->format('Y-m-d') : null;
            $dataEntrega = $row['Data_entrega'] ? Date::excelToDateTimeObject($row['Data_entrega'])->format('Y-m-d') : null;

            $tipoMap = [
                'Melhoria' => 'melhoria',
                'Sugestão' => 'funcionalidade',
                'Correção' => 'bug',
                'Novidade' => 'funcionalidade',
                'Problema' => 'bug',
            ];

            $statusMap = [
                'backlog' => 'em branco',
                'Em analise' => 'analise',
                'Entregue' => 'entregue',
                'Em teste' => 'em_testes',
                'Aguardando' => 'em_branco',
                
            ];

            $dto = new CreateDemandaDTO([   
                'produto' => $row['Produto'],
                'chamado' => $row['Chamado'],
                'descricao' => $row['Descrição'],
                'tipo' => $tipoMap[$row['Tipo']] ?? 'funcionalidade',
                'data_previsao' => $previsao,
                'cliente' => $row['Cliente'],
                'job_gitlab' => $row['Job'],
                'status' => $statusMap[$row['Status']] ?? 'em_branco',
                'prioridade' => 'verde', // Default, ajustável depois via drag-and-drop
                'observacao' => $row['Observação'],
                'data_entrada' => $dataEntrada,
                'data_entrega' => $dataEntrega,
                'responsavel_id' => null, // A ser atribuído após aprovação
            ]);

            $demanda = $createDemandaUseCase->execute($dto);

            // Log inicial da importação
            $logDto = new \App\Domain\DemandaLog\DTOs\CreateDemandaLogDTO([
                'demanda_id' => $demanda->id,
                'user_id' => 1, // Usuário temporário (ajustar para autenticado)
                'action' => 'imported',
                'description' => 'Demanda importada via seeder',
            ]);
            app(\App\Domain\DemandaLog\UseCases\CreateLogUseCase::class)->execute($logDto);
        }
    }
}