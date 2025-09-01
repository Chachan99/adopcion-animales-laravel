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
        // Primero eliminamos la restricción de clave foránea existente
        Schema::table('solicitudes_adopcion', function (Blueprint $table) {
            // Verificamos si la restricción existe antes de intentar eliminarla
            if (Schema::hasColumn('solicitudes_adopcion', 'fundacion_id')) {
                $table->dropForeign(['fundacion_id']);
                $table->unsignedBigInteger('fundacion_id')->nullable()->change();
            } else {
                $table->unsignedBigInteger('fundacion_id')->nullable()->after('animal_id');
            }
        });

        // Luego volvemos a agregar la restricción de clave foránea
        Schema::table('solicitudes_adopcion', function (Blueprint $table) {
            $table->foreign('fundacion_id')
                  ->references('id')
                  ->on('perfil_fundaciones')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Para revertir, primero eliminamos la restricción
        Schema::table('solicitudes_adopcion', function (Blueprint $table) {
            if (Schema::hasColumn('solicitudes_adopcion', 'fundacion_id')) {
                $table->dropForeign(['fundacion_id']);
                // No podemos hacer que la columna sea NOT NULL si ya tiene valores NULL
                // $table->unsignedBigInteger('fundacion_id')->nullable(false)->change();
            }
        });

        // Y luego volvemos a agregar la restricción sin ON DELETE SET NULL
        Schema::table('solicitudes_adopcion', function (Blueprint $table) {
            if (Schema::hasColumn('solicitudes_adopcion', 'fundacion_id')) {
                $table->foreign('fundacion_id')
                      ->references('id')
                      ->on('perfil_fundaciones');
            }
        });
    }
};
