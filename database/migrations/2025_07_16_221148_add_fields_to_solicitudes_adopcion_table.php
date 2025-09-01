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
            // Only add fundacion_id if it doesn't exist
            if (!Schema::hasColumn('solicitudes_adopcion', 'fundacion_id')) {
                $table->unsignedBigInteger('fundacion_id')->nullable()->after('animal_id');
                // Add foreign key for fundacion_id if it doesn't exist
                $table->foreign('fundacion_id')->references('id')->on('perfil_fundaciones')->onDelete('set null');
            }
            
            // Add other columns if they don't exist
            $columnsToAdd = [
                'nombre_solicitante' => 'string',
                'email_solicitante' => 'string',
                'telefono_solicitante' => 'string',
                'edad_solicitante' => 'integer',
                'direccion_solicitante' => 'text',
                'tipo_vivienda' => ['type' => 'enum', 'values' => ['casa', 'apartamento', 'duplex', 'otro']],
                'tiene_patio' => ['type' => 'enum', 'values' => ['si', 'no']],
                'otros_mascotas' => ['type' => 'enum', 'values' => ['si', 'no']],
                'experiencia_mascotas' => ['type' => 'enum', 'values' => ['nada', 'poca', 'moderada', 'mucha']],
                'motivo_adopcion' => 'text',
                'tiempo_disponible' => ['type' => 'enum', 'values' => ['poco', 'moderado', 'mucho']],
            ];
            
            foreach ($columnsToAdd as $column => $config) {
                if (!Schema::hasColumn('solicitudes_adopcion', $column)) {
                    if (is_array($config)) {
                        $table->enum($column, $config['values']);
                    } else {
                        $table->{$config}($column);
                    }
                }
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('solicitudes_adopcion', function (Blueprint $table) {
            $table->dropForeign(['fundacion_id']);
            $table->dropColumn([
                'fundacion_id',
                'nombre_solicitante',
                'email_solicitante',
                'telefono_solicitante',
                'edad_solicitante',
                'direccion_solicitante',
                'tipo_vivienda',
                'tiene_patio',
                'otros_mascotas',
                'experiencia_mascotas',
                'motivo_adopcion',
                'tiempo_disponible',
            ]);
        });
    }
};
