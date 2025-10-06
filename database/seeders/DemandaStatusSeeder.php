<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DemandaStatusSeeder extends Seeder
{
    public function run()
    {
        DB::table('demandas_status')->insert([
            ['status' => 'em_branco', 'descricao' => 'BackLog', 'created_at' => now()->toDateTimeString()],
            ['status' => 'analise', 'descricao' => 'Em Análise', 'created_at' => now()->toDateTimeString() ],
            ['status' => 'em_execucao', 'descricao' => 'Em Execução', 'created_at' => now()->toDateTimeString()],
            ['status' => 'em_testes', 'descricao' => 'Em Testes', 'created_at' => now()->toDateTimeString()],
            ['status' => 'validacao', 'descricao' => 'Em Validação', 'created_at' => now()->toDateTimeString()],
            ['status' => 'entregue', 'descricao' => 'Entregue', 'created_at' => now()->toDateTimeString()],
        ]);
    }
}
