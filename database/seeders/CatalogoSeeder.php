<?php

namespace Database\Seeders;

use App\Models\Categoria;
use App\Models\TipoAnalisis;
use Illuminate\Database\Seeder;

class CatalogoSeeder extends Seeder
{
    public function run(): void
    {
        // --- 1. CATEGORÍAS NORMALES ---
        $catQuimica = Categoria::create(['nombre' => 'Química Sanguínea', 'es_cultivo' => false]);
        $catHemato = Categoria::create(['nombre' => 'Hematología', 'es_cultivo' => false]);
        $catInmuno = Categoria::create(['nombre' => 'Inmunología', 'es_cultivo' => false]);

        // --- 2. CATEGORÍA MICROBIOLOGÍA ---
        $catMicro = Categoria::create(['nombre' => 'Microbiología y Cultivos', 'es_cultivo' => true]);

        // EXÁMENES DE QUÍMICA SANGUÍNEA
        TipoAnalisis::create([
            'categoria_id' => $catQuimica->id, 'nombre' => 'Glucosa Basal', 'costo' => 30,
            'tipo_parámetro' => 'numerico', 'unidad_medida' => 'mg/dL',
            'rango_min_masculino' => 70, 'rango_max_masculino' => 100,
            'rango_min_femenino' => 70, 'rango_max_femenino' => 100,
        ]);
        TipoAnalisis::create([
            'categoria_id' => $catQuimica->id, 'nombre' => 'Ácido Úrico', 'costo' => 35,
            'tipo_parámetro' => 'numerico', 'unidad_medida' => 'mg/dL',
            'rango_min_masculino' => 3.4, 'rango_max_masculino' => 7.0,
            'rango_min_femenino' => 2.4, 'rango_max_femenino' => 6.0,
        ]);

        // EXÁMENES DE HEMATOLOGÍA
        TipoAnalisis::create([
            'categoria_id' => $catHemato->id, 'nombre' => 'Hemoglobina', 'costo' => 25,
            'tipo_parámetro' => 'numerico', 'unidad_medida' => 'g/dL',
            'rango_min_masculino' => 13.8, 'rango_max_masculino' => 17.2,
            'rango_min_femenino' => 12.1, 'rango_max_femenino' => 15.1,
        ]);

        // EXÁMENES DE INMUNOLOGÍA (CUALITATIVOS)
        TipoAnalisis::create([
            'categoria_id' => $catInmuno->id, 'nombre' => 'Prueba Rápida VIH', 'costo' => 80,
            'tipo_parámetro' => 'cualitativo', 'valor_referencia_cualitativo' => 'No Reactivo',
        ]);
        TipoAnalisis::create([
            'categoria_id' => $catInmuno->id, 'nombre' => 'Prueba de Embarazo (HCG)', 'costo' => 45,
            'tipo_parámetro' => 'cualitativo', 'valor_referencia_cualitativo' => 'Negativo',
        ]);
        TipoAnalisis::create([
            'categoria_id' => $catInmuno->id, 'nombre' => 'Grupo Sanguíneo y Factor Rh', 'costo' => 30,
            'tipo_parámetro' => 'cualitativo', 'valor_referencia_cualitativo' => 'N/A', // No hay "malo"
        ]);

        // CULTIVOS MICROBIOLÓGICOS (Sin rangos ni referencias)
        TipoAnalisis::create([
            'categoria_id' => $catMicro->id, 'nombre' => 'Urocultivo', 'costo' => 150,
            'tipo_parámetro' => 'cualitativo', 'valor_referencia_cualitativo' => 'N/A',
        ]);
        TipoAnalisis::create([
            'categoria_id' => $catMicro->id, 'nombre' => 'Coprocultivo', 'costo' => 130,
            'tipo_parámetro' => 'cualitativo', 'valor_referencia_cualitativo' => 'N/A',
        ]);
    }
}
