<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $rolePermissions = [
            // Administrador - Todas as permissões
            'admin' => [
                'view_dashboard', 'view_demandas', 'create_demandas', 'update_any_demanda', 'delete_demandas',
                'approve_demandas', 'assign_demandas', 'change_any_status', 'bulk_operations', 'manage_users',
                'manage_roles', 'export_data', 'view_reports', 'manage_system'
            ],
            
            // Gestor
            'gestor' => [
                'view_dashboard', 'view_demandas', 'create_demandas', 'update_any_demanda', 'approve_demandas',
                'assign_demandas', 'change_any_status', 'bulk_operations', 'export_data', 'view_reports'
            ],
            
            // Executor/Desenvolvedor
            'executor' => [
                'view_dashboard', 'view_demandas', 'view_own_demandas', 'update_own_demanda', 'change_own_status'
            ],
            
            // Analista QA
            'analista_qa' => [
                'view_dashboard', 'view_demandas', 'update_own_demanda', 'change_own_status'
            ],
            
            // Usuário Comum
            'usuario_comum' => [
                'view_dashboard', 'view_demandas', 'create_demandas'
            ],
        ];

        foreach ($rolePermissions as $roleName => $permissions) {
            $roleId = DB::table('roles')->where('name', $roleName)->value('id');
            
            foreach ($permissions as $permissionName) {
                $permissionId = DB::table('permissions')->where('name', $permissionName)->value('id');
                
                if ($roleId && $permissionId) {
                    DB::table('role_permissions')->insert([
                        'role_id' => $roleId,
                        'permission_id' => $permissionId,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
    }
}
