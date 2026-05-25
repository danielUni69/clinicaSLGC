<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ---------------------------------------------------------
        // NIVEL 1: TABLAS MAESTRAS (Sin llaves foráneas a otras)
        // ---------------------------------------------------------
        Schema::create('categorias', function (Blueprint $table) {
            $table->id();
            $table->string('nombre')->unique();
            $table->timestamps();
        });

        Schema::create('responsables', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_completo');
            $table->string('celular');
            $table->string('relacion');
            $table->string('correo')->nullable();
            $table->timestamps();
        });

        Schema::create('medicos_solicitantes', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_completo');
            $table->string('especialidad')->nullable();
            $table->string('matricula_profesional')->unique();
            $table->string('correo')->nullable();
            $table->timestamps();
        });

        Schema::create('antibioticos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_antibiotico')->unique();
            $table->boolean('estado')->default(true);
            $table->timestamps();
        });

        // ---------------------------------------------------------
        // NIVEL 2: TABLAS CON DEPENDENCIAS SIMPLES
        // ---------------------------------------------------------
        Schema::create('pacientes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('responsable_id')->nullable()->constrained('responsables')->onDelete('set null');
            $table->string('ci')->unique();
            $table->string('nombre_completo');
            $table->date('fecha_nacimiento');
            $table->char('sexo', 1);
            $table->string('telefono')->nullable();
            $table->timestamps();
        });

        Schema::create('tipos_analisis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('categoria_id')->constrained('categorias')->onDelete('cascade');
            $table->string('nombre');
            $table->decimal('costo', 8, 2);
            $table->string('unidad_medida')->nullable();
            $table->boolean('estado')->default(true);
            $table->timestamps();
        });

        Schema::create('turnos_domingo_feriados', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->date('fecha');
            $table->timestamps();
            $table->unique(['user_id', 'fecha']);
        });

        // ---------------------------------------------------------
        // NIVEL 3: EL CORAZÓN DEL NEGOCIO (SERVICIOS)
        // ---------------------------------------------------------
        Schema::create('servicios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('paciente_id')->constrained('pacientes');
            $table->foreignId('medico_id')->constrained('medicos_solicitantes');
            $table->string('codigo_unico')->unique();
            $table->enum('estado_pago', ['pendiente', 'pagado'])->default('pendiente');
            $table->enum('estado_muestra', ['pendiente', 'recolectada', 'rechazada'])->default('pendiente');
            $table->string('observaciones_calidad')->nullable();
            $table->timestamps();
        });

        // ---------------------------------------------------------
        // NIVEL 4: TABLAS DEPENDIENTES DE SERVICIOS
        // ---------------------------------------------------------
        Schema::create('servicio_analisis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('servicio_id')->constrained('servicios')->onDelete('cascade');
            $table->foreignId('tipo_analisis_id')->constrained('tipos_analisis')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('recibos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('servicio_id')->unique()->constrained('servicios')->onDelete('cascade');
            $table->string('numero_correlativo')->unique();
            $table->decimal('subtotal', 8, 2);
            $table->decimal('descuento', 8, 2)->default(0.00);
            $table->decimal('total', 8, 2);
            $table->string('medio_pago');
            $table->timestamps();
        });

        Schema::create('resultados_analisis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('servicio_id')->constrained('servicios')->onDelete('cascade');
            $table->foreignId('tipo_analisis_id')->constrained('tipos_analisis');
            $table->string('valor_registrado');
            $table->enum('alerta_rango', ['normal', 'alto', 'bajo', 'critico'])->default('normal');
            $table->foreignId('bioquimico_id')->constrained('users');
            $table->timestamps();
        });

        Schema::create('cultivos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('servicio_id')->constrained('servicios')->onDelete('cascade');
            $table->foreignId('tipo_analisis_id')->constrained('tipos_analisis');
            $table->enum('estado_cultivo', ['en_incubacion', 'negativo', 'positivo_identificado'])->default('en_incubacion');
            $table->string('cepa_bacteriana')->nullable();
            $table->foreignId('bioquimico_id')->constrained('users');
            $table->timestamps();
        });

        // ---------------------------------------------------------
        // NIVEL 5: HIJOS DE CULTIVOS (MICROBIOLOGÍA)
        // ---------------------------------------------------------
        Schema::create('reportes_evolucion', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cultivo_id')->constrained('cultivos')->onDelete('cascade');
            $table->string('observacion');
            $table->timestamps();
        });

        Schema::create('antibiogramas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cultivo_id')->constrained('cultivos')->onDelete('cascade');
            $table->foreignId('antibiotico_id')->constrained('antibioticos');
            $table->char('susceptibilidad', 1);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        // El rollback debe ser exactamente en el orden inverso
        Schema::dropIfExists('antibiogramas');
        Schema::dropIfExists('reportes_evolucion');
        Schema::dropIfExists('cultivos');
        Schema::dropIfExists('resultados_analisis');
        Schema::dropIfExists('recibos');
        Schema::dropIfExists('servicio_analisis');
        Schema::dropIfExists('servicios');
        Schema::dropIfExists('turnos_domingo_feriados');
        Schema::dropIfExists('tipos_analisis');
        Schema::dropIfExists('pacientes');
        Schema::dropIfExists('antibioticos');
        Schema::dropIfExists('medicos_solicitantes');
        Schema::dropIfExists('responsables');
        Schema::dropIfExists('categorias');
    }
};
