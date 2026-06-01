<?php

namespace Database\Seeders;

use App\Models\MedicoSolicitante;
use Illuminate\Database\Seeder;

class MedicoSeeder extends Seeder
{
    public function run(): void
    {
        MedicoSolicitante::updateOrCreate(
            ['matricula_profesional' => 'MP-1025'],
            [
                'nombre_completo' => 'Dr. Roberto Ramos',
                'especialidad' => 'Medicina General',
            ]
        );

        MedicoSolicitante::updateOrCreate(
            ['matricula_profesional' => 'MP-3048'],
            [
                'nombre_completo' => 'Dra. Elena Vargas',
                'especialidad' => 'Urología',
            ]
        );

    }
}
