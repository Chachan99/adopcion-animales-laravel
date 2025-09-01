<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        // Primero intentamos eliminar la clave foránea si existe
        Schema::table('animales', function (Blueprint $table) {
            // Intentamos eliminar la clave foránea si existe
            // Usamos try-catch para evitar errores si la clave no existe
            try {
                $table->dropForeign(['fundacion_id']);
            } catch (\Exception $e) {
                // No hacer nada si la clave foránea no existe
            }
            
            // Verificamos si la columna existe antes de intentar eliminarla
            if (Schema::hasColumn('animales', 'fundacion_id')) {
                $table->dropColumn('fundacion_id');
            }
        });

        // Añadimos la columna fundacion_id si no existe
        if (!Schema::hasColumn('animales', 'fundacion_id')) {
            Schema::table('animales', function (Blueprint $table) {
                $table->unsignedBigInteger('fundacion_id')->after('id');
            });
        }

        // Añadimos la clave foránea
        Schema::table('animales', function (Blueprint $table) {
            $table->foreign('fundacion_id')
                  ->references('id')
                  ->on('perfil_fundaciones')
                  ->onDelete('cascade');
        });

        // Añadimos soft deletes a las tablas principales si no existen
        if (!Schema::hasColumn('usuarios', 'deleted_at')) {
            Schema::table('usuarios', function (Blueprint $table) {
                $table->softDeletes();
            });
        }

        if (!Schema::hasColumn('perfil_fundaciones', 'deleted_at')) {
            Schema::table('perfil_fundaciones', function (Blueprint $table) {
                $table->softDeletes();
            });
        }

        if (!Schema::hasColumn('animales', 'deleted_at')) {
            Schema::table('animales', function (Blueprint $table) {
                $table->softDeletes();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        // Eliminamos los soft deletes si existen
        if (Schema::hasColumn('usuarios', 'deleted_at')) {
            Schema::table('usuarios', function (Blueprint $table) {
                $table->dropSoftDeletes();
            });
        }

        if (Schema::hasColumn('perfil_fundaciones', 'deleted_at')) {
            Schema::table('perfil_fundaciones', function (Blueprint $table) {
                $table->dropSoftDeletes();
            });
        }

        if (Schema::hasColumn('animales', 'deleted_at')) {
            Schema::table('animales', function (Blueprint $table) {
                $table->dropSoftDeletes();
            });
        }
    }
};
