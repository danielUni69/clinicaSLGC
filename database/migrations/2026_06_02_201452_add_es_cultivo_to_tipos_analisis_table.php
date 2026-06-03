<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tipos_analisis', function (Blueprint $table) {
            // Agregamos el interruptor oficial de Cultivo
            $table->boolean('es_cultivo')->default(false)->after('tipo_parámetro');
        });
    }

    public function down(): void
    {
        Schema::table('tipos_analisis', function (Blueprint $table) {
            $table->dropColumn('es_cultivo');
        });
    }
};
