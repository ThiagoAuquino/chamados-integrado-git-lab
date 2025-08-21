<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name' => 'Administrador',
                'email' => 'admin@sistema.com',
                'password' => Hash::make('123456'),
                'email_verified_at' => now(),
                'active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'João Silva - Gestor',
                'email' => 'gestor@sistema.com',
                'password' => Hash::make('123456'),
                'email_verified_at' => now(),
                'active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Maria Santos - Desenvolvedora',
                'email' => 'dev@sistema.com',
                'password' => Hash::make('123456'),
                'email_verified_at' => now(),
                'active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Carlos QA - Analista',
                'email' => 'qa@sistema.com',
                'password' => Hash::make('123456'),
                'email_verified_at' => now(),
                'active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($users as $user) {
            DB::table('users')->insert($user);
        }

        // Atribuir roles aos usuários
        $userRoles = [
            'admin@sistema.com' => 'admin',
            'gestor@sistema.com' => 'gestor',
            'dev@sistema.com' => 'executor',
            'qa@sistema.com' => 'analista_qa',
        ];

        foreach ($userRoles as $email => $roleName) {
            $userId = DB::table('users')->where('email', $email)->value('id');
            $roleId = DB::table('roles')->where('name', $roleName)->value('id');
            
            if ($userId && $roleId) {
                DB::table('user_roles')->insert([
                    'user_id' => $userId,
                    'role_id' => $roleId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}