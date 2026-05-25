<?php

namespace Database\Seeders;

use App\Models\MedicoSolicitante;
use Illuminate\Database\Seeder;

class MedicoSeeder extends Seeder
{
    public function run(): void
    {
        MedicoSolicitante::create([
            'nombre_completo' => 'Dr. Roberto Ramos',
            'especialidad' => 'Medicina General',
            'matricula_profesional' => 'MP-1025',
        ]);

        MedicoSolicitante::create([
            'nombre_completo' => 'Dra. Elena Vargas',
            'especialidad' => 'Urología',
            'matricula_profesional' => 'MP-3048',
        ]);
    }
}
