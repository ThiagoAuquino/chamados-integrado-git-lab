<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DemandaTipoSeeder extends Seeder
{
    public function run()
    {
        DB::table('demanda_tipo')->insert([
            ['tipo' => 'Melhoria', 'descricao' => 'Melhoria de sistema ou processo'],
            ['tipo' => 'Sugestão', 'descricao' => 'Sugestão de novas funcionalidades ou melhorias'],
            ['tipo' => 'Correção', 'descricao' => 'Correção de bugs e erros'],
            ['tipo' => 'Novidade', 'descricao' => 'Novas funcionalidades e módulos'],
            ['tipo' => 'Problema', 'descricao' => 'Problemas reportados por clientes ou usuários'],
        ]);
    }
}
