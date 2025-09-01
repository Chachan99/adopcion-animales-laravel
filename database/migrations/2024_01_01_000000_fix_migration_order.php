<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FixMigrationOrder extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // No necesitamos hacer nada en el método up
        // ya que solo estamos reordenando las migraciones
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // No hacemos nada en el down ya que es solo para reordenar
    }

    /**
     * Este método se ejecutará después de que se registre la migración
     * pero antes de que se ejecute el método up
     */
    public function __construct()
    {
        // Renombrar migraciones problemáticas para que se ejecuten en el orden correcto
        $migrations = [
            '2024_07_18_000001_add_metodo_to_donaciones_table' => '2025_07_17_000001_add_metodo_to_donaciones_table',
            '2024_07_24_000001_add_descripcion_to_donaciones_table' => '2025_07_17_000002_add_descripcion_to_donaciones_table',
            '2024_07_24_000002_make_animal_id_nullable_in_donaciones_table' => '2025_07_17_000003_make_animal_id_nullable_in_donaciones_table',
            '2024_07_24_000003_make_usuario_id_nullable_in_donaciones_table' => '2025_07_17_000004_make_usuario_id_nullable_in_donaciones_table',
        ];

        foreach ($migrations as $oldName => $newName) {
            $oldPath = database_path("migrations/{$oldName}.php");
            $newPath = database_path("migrations/{$newName}.php");
            
            if (file_exists($oldPath) && !file_exists($newPath)) {
                rename($oldPath, $newPath);
            }
        }
    }
}
