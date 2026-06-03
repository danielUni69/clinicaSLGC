<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UsuarioSeeder extends Seeder
{
    public function run(): void
    {
        // Asegura 3 usuarios (bioquímico, admin, recepcionista)
        // con nombres pedidos por el usuario.
        $users = [
            [
                'name' => 'juanbioquimico',
                'email' => 'juanbioquimico@clinica.local',
                'role' => 'bioquimico',
                'active' => true,
                'password' => '12345678',
            ],
            [
                'name' => 'saeed admin',
                'email' => 'admin@clinica.local',
                'role' => 'administrador',
                'active' => true,
                'password' => '123456789',
            ],
            [
                'name' => 'Carla Bolaños',
                'email' => 'resecinista@clinica.local',
                'role' => 'recepcionista',
                'active' => true,
                'password' => '1234567',
            ],
        ];

        foreach ($users as $u) {
            User::updateOrCreate(
                ['email' => $u['email']],
                [
                    'name' => $u['name'],
                    'role' => $u['role'],
                    'active' => $u['active'],
                    'password' => bcrypt($u['password']),
                ]
            );
        }
    }
}
