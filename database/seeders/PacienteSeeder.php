<?php

namespace Database\Seeders;

use App\Models\Paciente;
use Illuminate\Database\Seeder;

class PacienteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Paciente::create([
            'ci' => '1030507',
            'nombre_completo' => 'Carlos Mamani Choque',
            'fecha_nacimiento' => '1985-08-20',
            'sexo' => 'M',
            'telefono' => '71234567',
        ]);

        Paciente::create([
            'ci' => '8541236',
            'nombre_completo' => 'Ana Maria Rojas',
            'fecha_nacimiento' => '1992-11-15',
            'sexo' => 'F',
            'telefono' => '78965412',
        ]);
    }
}
