<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            // Dashboard
            ['name' => 'view_dashboard', 'display_name' => 'Ver Dashboard', 'description' => 'Acesso ao painel principal', 'category' => 'dashboard'],
            
            // Demandas - Visualização
            ['name' => 'view_demandas', 'display_name' => 'Ver Demandas', 'description' => 'Visualizar todas as demandas', 'category' => 'demandas'],
            ['name' => 'view_own_demandas', 'display_name' => 'Ver Próprias Demandas', 'description' => 'Ver apenas demandas atribuídas', 'category' => 'demandas'],
            
            // Demandas - Criação/Edição
            ['name' => 'create_demandas', 'display_name' => 'Criar Demandas', 'description' => 'Criar novas demandas', 'category' => 'demandas'],
            ['name' => 'update_any_demanda', 'display_name' => 'Editar Qualquer Demanda', 'description' => 'Editar qualquer demanda do sistema', 'category' => 'demandas'],
            ['name' => 'update_own_demanda', 'display_name' => 'Editar Próprias Demandas', 'description' => 'Editar apenas demandas atribuídas', 'category' => 'demandas'],
            ['name' => 'delete_demandas', 'display_name' => 'Excluir Demandas', 'description' => 'Excluir demandas do sistema', 'category' => 'demandas'],
            
            // Demandas - Fluxo
            ['name' => 'approve_demandas', 'display_name' => 'Aprovar Demandas', 'description' => 'Aprovar demandas em branco', 'category' => 'demandas'],
            ['name' => 'assign_demandas', 'display_name' => 'Atribuir Demandas', 'description' => 'Atribuir responsáveis às demandas', 'category' => 'demandas'],
            ['name' => 'change_any_status', 'display_name' => 'Alterar Qualquer Status', 'description' => 'Alterar status de qualquer demanda', 'category' => 'demandas'],
            ['name' => 'change_own_status', 'display_name' => 'Alterar Próprio Status', 'description' => 'Alterar status das próprias demandas', 'category' => 'demandas'],
            
            // Operações em lote
            ['name' => 'bulk_operations', 'display_name' => 'Operações em Lote', 'description' => 'Executar operações em múltiplas demandas', 'category' => 'demandas'],
            
            // Usuários
            ['name' => 'manage_users', 'display_name' => 'Gerenciar Usuários', 'description' => 'CRUD completo de usuários', 'category' => 'usuarios'],
            ['name' => 'manage_roles', 'display_name' => 'Gerenciar Perfis', 'description' => 'Gerenciar roles e permissões', 'category' => 'usuarios'],
            
            // Relatórios
            ['name' => 'export_data', 'display_name' => 'Exportar Dados', 'description' => 'Exportar relatórios e dados', 'category' => 'relatorios'],
            ['name' => 'view_reports', 'display_name' => 'Ver Relatórios', 'description' => 'Acessar relatórios gerenciais', 'category' => 'relatorios'],
            
            // Sistema
            ['name' => 'manage_system', 'display_name' => 'Gerenciar Sistema', 'description' => 'Configurações avançadas do sistema', 'category' => 'sistema'],
        ];

        foreach ($permissions as $permission) {
            DB::table('permissions')->insert(array_merge($permission, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}