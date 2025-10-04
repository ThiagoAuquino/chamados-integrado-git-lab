<?php

namespace Database\Seeders;

use App\Domain\Demanda\DTOs\CreateDemandaDTO;
use App\Domain\Demanda\UseCases\CreateDemandaUseCase;
use App\Domain\DemandaLog\DTOs\CreateDemandaLogDTO;
use App\Domain\DemandaLog\UseCases\CreateLogUseCase;
use App\Models\Demanda\DemandaStatus;
use App\Models\Demanda\DemandaTipo;
use Illuminate\Database\Seeder;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class DemandaImportSeeder extends Seeder
{
    public function run()
    {
        $data = [
            ['Data_entrada' => '', 'Data_entrega' => '', 'Previsão' => 45904, 'Produto' => 'Omniplus', 'solicitante' => '', 'Job' => '570', 'Chamado' => '2025-224010', 'Cliente' => 'FUNCIONAL', 'Descrição' => 'Um relatorio onde mostra no relatorio usuarios que estão com a mesma posição', 'Tipo' => 'Melhoria', 'Status' => '', 'Observação' => ''],
            ['Data_entrada' => '', 'Data_entrega' => '', 'Previsão' => 45901, 'Produto' => 'OmniPBX', 'solicitante' => '', 'Job' => '192', 'Chamado' => '2025-221909', 'Cliente' => 'FUNCIONAL', 'Descrição' => 'Inserir campo de categoria da fila no relatorio de pesquisa', 'Tipo' => 'Sugestão', 'Status' => '', 'Observação' => ''],
            ['Data_entrada' => '', 'Data_entrega' => '', 'Previsão' => '', 'Produto' => 'OmniPBX', 'solicitante' => '', 'Job' => '221', 'Chamado' => '2025-225857', 'Cliente' => 'EZ-TECH', 'Descrição' => 'Serviços de inicialização do fenix não estava iniciando corretamenteo', 'Tipo' => 'Correção', 'Status' => 'Entregue', 'Observação' => 'VOU VERIFICAR COM O PROSPERO'],
        ];


        // Buscar do banco: nome => id  para compatibilidade com validação e DTO
        // Aqui 'nome' é o campo no banco com o nome do tipo, ajuste se for diferente

        $createDemandaUseCase = app(CreateDemandaUseCase::class);
        $createLogUseCase = app(CreateLogUseCase::class);

        foreach ($data as $row) {
            $previsao = $row['Previsão'] ? Date::excelToDateTimeObject($row['Previsão'])->format('Y-m-d') : now()->format('Y-m-d');
            $dataEntrada = $row['Data_entrada'] ? Date::excelToDateTimeObject($row['Data_entrada'])->format('Y-m-d') : null;
            $dataEntrega = $row['Data_entrega'] ? Date::excelToDateTimeObject($row['Data_entrega'])->format('Y-m-d') : null;

            // Busca o tipo pelo nome (enum) na tabela demanda_tipo
            $tipo = DemandaTipo::where('tipo', $row['Tipo'])->first();
            $tipoId = $tipo ? $tipo->id : 1; // fallback para 1 caso não encontre

            // Busca o status pelo nome na tabela demandas_status
            $status = DemandaStatus::where('status', strtolower($row['Status']) ?: 'em_branco')->first();
            $statusId = $status ? $status->id : 1;

            $dto = new CreateDemandaDTO(
                produto: $row['Produto'],
                chamado: $row['Chamado'] ?? null,
                descricao: $row['Descrição'],
                tipo_id: $tipoId,
                data_previsao: $previsao,
                cliente: $row['Cliente'],
                status_id: $statusId,
                responsavel_id: null,
                priority: 'verde',
                order: 0,
                observacao: $row['Observação'] ?? null,
                job: $row['Job'] ?? null,
                created_at: now()->toDateTimeString(),
            );


            $demanda = $createDemandaUseCase->execute($dto);

            $logDto = new CreateDemandaLogDTO(
                demanda_id: $demanda->id,
                user_id: 1, // ajustar se precisar
                action: 'imported',
                description: 'Demanda importada via seeder',
                created_at: now()->toDateTimeString()
            );

            $createLogUseCase->execute($logDto);
        }
    }
}
