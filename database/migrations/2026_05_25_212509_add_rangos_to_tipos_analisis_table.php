<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tipos_analisis', function (Blueprint $table) {
            // Identifica si el examen es numérico (Glucosa) o de texto/cualitativo (Grupo Sanguíneo)
            $table->enum('tipo_parámetro', ['numerico', 'cualitativo'])->default('numerico')->after('unidad_medida');

            // Rangos de referencia para Varones
            $table->decimal('rango_min_masculino', 8, 2)->nullable()->after('tipo_parámetro');
            $table->decimal('rango_max_masculino', 8, 2)->nullable()->after('rango_min_masculino');

            // Rangos de referencia para Mujeres
            $table->decimal('rango_min_femenino', 8, 2)->nullable()->after('rango_max_masculino');
            $table->decimal('rango_max_femenino', 8, 2)->nullable()->after('rango_min_femenino');

            // Texto guía en caso de ser cualitativo (Ej: "Negativo", "No reactivo")
            $table->string('valor_referencia_cualitativo')->nullable()->after('rango_max_femenino');
        });
    }

    public function down(): void
    {
        Schema::table('tipos_analisis', function (Blueprint $table) {
            $table->dropColumn([
                'tipo_parámetro',
                'rango_min_masculino',
                'rango_max_masculino',
                'rango_min_femenino',
                'rango_max_femenino',
                'valor_referencia_cualitativo',
            ]);
        });
    }
};
