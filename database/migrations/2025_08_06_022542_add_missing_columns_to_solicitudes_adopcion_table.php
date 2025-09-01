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
            // Agregar bienestar_mascota
            if (!Schema::hasColumn('solicitudes_adopcion', 'bienestar_mascota')) {
                $table->text('bienestar_mascota')->nullable()->after('tiempo_disponible');
            }
            
            // Agregar conocimiento_cuidados
            if (!Schema::hasColumn('solicitudes_adopcion', 'conocimiento_cuidados')) {
                $table->text('conocimiento_cuidados')->nullable()->after('bienestar_mascota');
            }
            
            // Agregar compromiso_esterilizacion
            if (!Schema::hasColumn('solicitudes_adopcion', 'compromiso_esterilizacion')) {
                $table->enum('compromiso_esterilizacion', ['si', 'no'])->default('si')->after('conocimiento_cuidados');
            }
            
            // Agregar preguntas_seguridad (se guardará como JSON)
            if (!Schema::hasColumn('solicitudes_adopcion', 'preguntas_seguridad')) {
                $table->json('preguntas_seguridad')->nullable()->after('compromiso_esterilizacion');
            }
            
            // Agregar mensaje (por si acaso no existe)
            if (!Schema::hasColumn('solicitudes_adopcion', 'mensaje')) {
                $table->text('mensaje')->nullable()->after('preguntas_seguridad');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('solicitudes_adopcion', function (Blueprint $table) {
            // Eliminar las columnas en orden inverso
            $columnsToDrop = [
                'mensaje',
                'preguntas_seguridad',
                'compromiso_esterilizacion',
                'conocimiento_cuidados',
                'bienestar_mascota'
            ];
            
            foreach ($columnsToDrop as $column) {
                if (Schema::hasColumn('solicitudes_adopcion', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
