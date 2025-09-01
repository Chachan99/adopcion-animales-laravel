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
            // Agregar nuevos campos
            $table->enum('estado_civil', ['soltero', 'casado', 'divorciado', 'viudo', 'union_libre'])->nullable()->after('edad_solicitante');
            $table->string('ocupacion', 100)->nullable()->after('estado_civil');
            $table->boolean('permiso_mascotas')->nullable()->after('tipo_vivienda');
            $table->text('descripcion_vivienda')->nullable()->after('permiso_mascotas');
            $table->text('horas_solo')->nullable()->after('descripcion_vivienda');
            $table->text('plan_para_mudanza')->nullable()->after('horas_solo');
            $table->text('vacaciones_mascota')->nullable()->after('plan_para_mudanza');
            $table->text('gasto_mensual_estimado')->nullable()->after('vacaciones_mascota');
            $table->text('compromiso_esterilizacion')->nullable()->after('gasto_mensual_estimado');
            $table->text('referencias_veterinario')->nullable()->after('compromiso_esterilizacion');
            $table->string('como_supo', 255)->nullable()->after('tiempo_disponible');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('solicitudes_adopcion', function (Blueprint $table) {
            $table->dropColumn([
                'estado_civil',
                'ocupacion',
                'permiso_mascotas',
                'descripcion_vivienda',
                'horas_solo',
                'plan_para_mudanza',
                'vacaciones_mascota',
                'gasto_mensual_estimado',
                'compromiso_esterilizacion',
                'referencias_veterinario',
                'como_supo'
            ]);
        });
    }
};
