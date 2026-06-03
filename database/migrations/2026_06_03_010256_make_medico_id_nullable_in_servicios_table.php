<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('servicios', function (Blueprint $table) {
            // Le decimos a la base de datos que ahora permite nulos
            $table->unsignedBigInteger('medico_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('servicios', function (Blueprint $table) {
            // Si queremos revertir, vuelve a ser obligatorio
            $table->unsignedBigInteger('medico_id')->nullable(false)->change();
        });
    }
};
