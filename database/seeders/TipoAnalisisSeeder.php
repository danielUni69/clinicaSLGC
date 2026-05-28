<?php

namespace Database\Seeders;

use App\Models\TipoAnalisis;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TipoAnalisisSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Creamos las categorías maestras para evitar el error de llave foránea
        $idQuimica = $this->obtenerOCrearCategoria('Química Sanguínea');
        $idHemato = $this->obtenerOCrearCategoria('Hematología');
        $idInmuno = $this->obtenerOCrearCategoria('Inmunología y Serología');

        // 2. Definimos los análisis, ahora incluyendo su 'categoria_id'
        $analisis = [
            // ==========================================
            // QUÍMICA SANGUÍNEA
            // ==========================================
            [
                'categoria_id' => $idQuimica,
                'nombre' => 'Glucosa en Ayunas',
                'costo' => 30.00,
                'unidad_medida' => 'mg/dL',
                'estado' => true,
                'tipo_parámetro' => 'numerico',
                'rango_min_masculino' => 70.00,
                'rango_max_masculino' => 100.00,
                'rango_min_femenino' => 70.00,
                'rango_max_femenino' => 100.00,
                'valor_referencia_cualitativo' => null,
            ],
            [
                'categoria_id' => $idQuimica,
                'nombre' => 'Colesterol Total',
                'costo' => 45.00,
                'unidad_medida' => 'mg/dL',
                'estado' => true,
                'tipo_parámetro' => 'numerico',
                'rango_min_masculino' => 0.00,
                'rango_max_masculino' => 200.00,
                'rango_min_femenino' => 0.00,
                'rango_max_femenino' => 200.00,
                'valor_referencia_cualitativo' => null,
            ],
            [
                'categoria_id' => $idQuimica,
                'nombre' => 'Triglicéridos',
                'costo' => 40.00,
                'unidad_medida' => 'mg/dL',
                'estado' => true,
                'tipo_parámetro' => 'numerico',
                'rango_min_masculino' => 0.00,
                'rango_max_masculino' => 150.00,
                'rango_min_femenino' => 0.00,
                'rango_max_femenino' => 150.00,
                'valor_referencia_cualitativo' => null,
            ],
            [
                'categoria_id' => $idQuimica,
                'nombre' => 'Ácido Úrico',
                'costo' => 38.00,
                'unidad_medida' => 'mg/dL',
                'estado' => true,
                'tipo_parámetro' => 'numerico',
                'rango_min_masculino' => 3.40,
                'rango_max_masculino' => 7.00,
                'rango_min_femenino' => 2.40,
                'rango_max_femenino' => 6.00,
                'valor_referencia_cualitativo' => null,
            ],

            // ==========================================
            // HEMATOLOGÍA
            // ==========================================
            [
                'categoria_id' => $idHemato,
                'nombre' => 'Hemoglobina',
                'costo' => 35.00,
                'unidad_medida' => 'g/dL',
                'estado' => true,
                'tipo_parámetro' => 'numerico',
                'rango_min_masculino' => 13.80,
                'rango_max_masculino' => 17.20,
                'rango_min_femenino' => 12.10,
                'rango_max_femenino' => 15.10,
                'valor_referencia_cualitativo' => null,
            ],

            // ==========================================
            // INMUNOLOGÍA Y SEROLOGÍA
            // ==========================================
            [
                'categoria_id' => $idInmuno,
                'nombre' => 'Prueba de Embarazo (HCG en Sangre)',
                'costo' => 60.00,
                'unidad_medida' => null,
                'estado' => true,
                'tipo_parámetro' => 'cualitativo',
                'rango_min_masculino' => null,
                'rango_max_masculino' => null,
                'rango_min_femenino' => null,
                'rango_max_femenino' => null,
                'valor_referencia_cualitativo' => 'Negativo',
            ],
            [
                'categoria_id' => $idInmuno,
                'nombre' => 'Prueba Rápida VIH (Anticuerpos)',
                'costo' => 80.00,
                'unidad_medida' => null,
                'estado' => true,
                'tipo_parámetro' => 'cualitativo',
                'rango_min_masculino' => null,
                'rango_max_masculino' => null,
                'rango_min_femenino' => null,
                'rango_max_femenino' => null,
                'valor_referencia_cualitativo' => 'No Reactivo',
            ],
            [
                'categoria_id' => $idInmuno,
                'nombre' => 'Grupo Sanguíneo y Factor Rh',
                'costo' => 30.00,
                'unidad_medida' => null,
                'estado' => true,
                'tipo_parámetro' => 'cualitativo',
                'rango_min_masculino' => null,
                'rango_max_masculino' => null,
                'rango_min_femenino' => null,
                'rango_max_femenino' => null,
                'valor_referencia_cualitativo' => 'N/A',
            ],
        ];

        // 3. Insertamos o actualizamos los análisis
        foreach ($analisis as $item) {
            TipoAnalisis::updateOrCreate(
                ['nombre' => $item['nombre']],
                $item
            );
        }
    }

    /**
     * Función auxiliar para obtener el ID de una categoría o crearla si no existe.
     */
    private function obtenerOCrearCategoria($nombreCategoria)
    {
        // Buscamos la categoría en la base de datos
        $categoria = DB::table('categorias')->where('nombre', $nombreCategoria)->first();

        if ($categoria) {
            return $categoria->id;
        }

        // Si no existe, la creamos y retornamos su nuevo ID
        return DB::table('categorias')->insertGetId([
            'nombre' => $nombreCategoria,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
