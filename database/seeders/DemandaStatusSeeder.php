<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DemandasStatusSeeder extends Seeder
{
    public function run()
    {
        DB::table('demandas_status')->insert([
            ['status' => 'em_branco', 'descricao' => 'BackLog'],
            ['status' => 'analise', 'descricao' => 'Em Análise'],
            ['status' => 'em_execucao', 'descricao' => 'Em Execução'],
            ['status' => 'em_testes', 'descricao' => 'Em Testes'],
            ['status' => 'validacao', 'descricao' => 'Em Validação'],
            ['status' => 'entregue', 'descricao' => 'Entregue'],
        ]);
    }
}
