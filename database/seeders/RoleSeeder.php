<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            [
                'name' => 'admin',
                'display_name' => 'Administrador',
                'description' => 'Acesso total ao sistema',
                'active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'gestor',
                'display_name' => 'Gestor',
                'description' => 'Gestão de equipe e aprovação de demandas',
                'active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'executor',
                'display_name' => 'Executor/Desenvolvedor',
                'description' => 'Execução de tarefas atribuídas',
                'active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'analista_qa',
                'display_name' => 'Analista de QA',
                'description' => 'Responsável por testes e validação',
                'active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'usuario_comum',
                'display_name' => 'Usuário Comum',
                'description' => 'Criação de demandas básicas',
                'active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('roles')->insert($roles);
    }
}
