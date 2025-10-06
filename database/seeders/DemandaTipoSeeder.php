<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DemandaTipoSeeder extends Seeder
{
    public function run()
    {
        DB::table('demanda_tipo')->insert([
            ['tipo' => 'Melhoria', 'descricao' => 'Melhoria de sistema ou processo', 'created_at' => now()->toDateTimeString()],
            ['tipo' => 'Sugestão', 'descricao' => 'Sugestão de novas funcionalidades ou melhorias', 'created_at' => now()->toDateTimeString()],
            ['tipo' => 'Correção', 'descricao' => 'Correção de bugs e erros', 'created_at' => now()->toDateTimeString()],
            ['tipo' => 'Novidade', 'descricao' => 'Novas funcionalidades e módulos', 'created_at' => now()->toDateTimeString()],
            ['tipo' => 'Problema', 'descricao' => 'Problemas reportados por clientes ou usuários', 'created_at' => now()->toDateTimeString()],
        ]);
    }
}
