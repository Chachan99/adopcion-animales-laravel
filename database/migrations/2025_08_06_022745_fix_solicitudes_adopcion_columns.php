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
        Schema::table('solicitudes_adopcion', function (Blueprint $table) {
            // Asegurar que la columna direccion_solicitante exista y sea nullable
            if (!Schema::hasColumn('solicitudes_adopcion', 'direccion_solicitante')) {
                $table->text('direccion_solicitante')->nullable()->after('ocupacion');
            }
            
            // Asegurar que la columna barrio exista y sea nullable
            if (!Schema::hasColumn('solicitudes_adopcion', 'barrio')) {
                $table->string('barrio')->nullable()->after('direccion_solicitante');
            }
            
            // Asegurar que la columna referencia exista y sea nullable
            if (!Schema::hasColumn('solicitudes_adopcion', 'referencia')) {
                $table->string('referencia')->nullable()->after('barrio');
            }
            
            // Asegurar que la columna tipo_vivienda exista
            if (!Schema::hasColumn('solicitudes_adopcion', 'tipo_vivienda')) {
                $table->enum('tipo_vivienda', ['casa', 'apartamento', 'duplex', 'otro'])->default('casa')->after('referencia');
            }
            
            // Asegurar que la columna tiene_patio exista
            if (!Schema::hasColumn('solicitudes_adopcion', 'tiene_patio')) {
                $table->enum('tiene_patio', ['si', 'no'])->default('si')->after('tipo_vivienda');
            }
            
            // Asegurar que la columna otros_mascotas exista
            if (!Schema::hasColumn('solicitudes_adopcion', 'otros_mascotas')) {
                $table->enum('otros_mascotas', ['si', 'no'])->default('no')->after('tiene_patio');
            }
            
            // Asegurar que la columna experiencia_mascotas exista
            if (!Schema::hasColumn('solicitudes_adopcion', 'experiencia_mascotas')) {
                $table->enum('experiencia_mascotas', ['nada', 'poca', 'moderada', 'mucha'])->default('nada')->after('otros_mascotas');
            }
            
            // Asegurar que la columna motivo_adopcion exista
            if (!Schema::hasColumn('solicitudes_adopcion', 'motivo_adopcion')) {
                $table->text('motivo_adopcion')->nullable()->after('experiencia_mascotas');
            }
            
            // Asegurar que la columna tiempo_disponible exista
            if (!Schema::hasColumn('solicitudes_adopcion', 'tiempo_disponible')) {
                $table->enum('tiempo_disponible', ['poco', 'moderado', 'mucho'])->default('moderado')->after('motivo_adopcion');
            }
            
            // Asegurar que la columna bienestar_mascota exista
            if (!Schema::hasColumn('solicitudes_adopcion', 'bienestar_mascota')) {
                $table->text('bienestar_mascota')->nullable()->after('tiempo_disponible');
            }
            
            // Asegurar que la columna conocimiento_cuidados exista
            if (!Schema::hasColumn('solicitudes_adopcion', 'conocimiento_cuidados')) {
                $table->text('conocimiento_cuidados')->nullable()->after('bienestar_mascota');
            }
            
            // Asegurar que la columna compromiso_esterilizacion exista
            if (!Schema::hasColumn('solicitudes_adopcion', 'compromiso_esterilizacion')) {
                $table->enum('compromiso_esterilizacion', ['si', 'no', 'consultar'])->default('consultar')->after('conocimiento_cuidados');
            }
            
            // Asegurar que la columna preguntas_seguridad exista
            if (!Schema::hasColumn('solicitudes_adopcion', 'preguntas_seguridad')) {
                $table->json('preguntas_seguridad')->nullable()->after('compromiso_esterilizacion');
            }
            
            // Asegurar que la columna mensaje exista
            if (!Schema::hasColumn('solicitudes_adopcion', 'mensaje')) {
                $table->text('mensaje')->nullable()->after('preguntas_seguridad');
            }
            
            // Asegurar que la columna estado exista
            if (!Schema::hasColumn('solicitudes_adopcion', 'estado')) {
                $table->enum('estado', ['pendiente', 'en_revision', 'aprobada', 'rechazada'])->default('pendiente')->after('mensaje');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No hacemos nada en el down para evitar perder datos
        // Si necesitas revertir esta migración, crea una nueva migración específica para eso
    }
};
