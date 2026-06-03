<?php

namespace Database\Seeders;

use App\Models\MedicoSolicitante;
use Illuminate\Database\Seeder;

class MedicoSeeder extends Seeder
{
    public function run(): void
    {
        MedicoSolicitante::create([
            'nombre_completo' => 'Dr. Carlos Mendoza',
            'especialidad' => 'Medicina General',
            'matricula_profesional' => 'MG-12345',
            'correo' => 'cmendoza@clinica.com',
        ]);

        MedicoSolicitante::create([
            'nombre_completo' => 'Dra. Elena Rojas',
            'especialidad' => 'Urología',
            'matricula_profesional' => 'UR-67890',
            'correo' => 'erojas@clinica.com',
        ]);
    }
}
