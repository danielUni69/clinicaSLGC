<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Usamos una consulta SQL pura porque modificar ENUMs con Schema::table a veces da problemas en Laravel
        DB::statement("ALTER TABLE servicios MODIFY COLUMN estado_muestra ENUM('pendiente', 'recolectada', 'rechazada', 'completada') DEFAULT 'pendiente'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE servicios MODIFY COLUMN estado_muestra ENUM('pendiente', 'recolectada', 'rechazada') DEFAULT 'pendiente'");
    }
};
