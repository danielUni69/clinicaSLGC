<?php

namespace Database\Seeders;

use App\Models\TipoAnalisis;
use Illuminate\Database\Seeder;

class TipoAnalisisSeeder extends Seeder
{
    public function run(): void
    {
        // Hematología (ID 1)
        TipoAnalisis::create(['categoria_id' => 1, 'nombre' => 'Hemograma Completo', 'costo' => 50.00, 'unidad_medida' => null]);

        // Química Sanguínea (ID 2)
        TipoAnalisis::create(['categoria_id' => 2, 'nombre' => 'Glucosa', 'costo' => 30.00, 'unidad_medida' => 'mg/dL']);
        TipoAnalisis::create(['categoria_id' => 2, 'nombre' => 'Colesterol Total', 'costo' => 40.00, 'unidad_medida' => 'mg/dL']);

        // Microbiología (ID 3)
        TipoAnalisis::create(['categoria_id' => 3, 'nombre' => 'Urocultivo', 'costo' => 120.00, 'unidad_medida' => null]);
    }
}
