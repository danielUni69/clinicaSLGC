<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Le damos el poder a la Categoría
        Schema::table('categorias', function (Blueprint $table) {
            $table->boolean('es_cultivo')->default(false)->after('nombre');
        });

        // 2. Se lo quitamos a los exámenes individuales
        Schema::table('tipos_analisis', function (Blueprint $table) {
            $table->dropColumn('es_cultivo');
        });
    }

    public function down(): void
    {
        Schema::table('categorias', function (Blueprint $table) {
            $table->dropColumn('es_cultivo');
        });

        Schema::table('tipos_analisis', function (Blueprint $table) {
            $table->boolean('es_cultivo')->default(false);
        });
    }
};
