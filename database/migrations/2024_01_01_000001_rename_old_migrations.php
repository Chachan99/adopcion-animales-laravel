<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameOldMigrations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Renombrar las migraciones antiguas para que se ejecuten en el orden correcto
        if (Schema::hasTable('migrations')) {
            $migrations = DB::table('migrations')
                ->where('migration', 'like', '0001%')
                ->get();
            
            foreach ($migrations as $migration) {
                $newName = '2023_' . substr($migration->migration, 5);
                DB::table('migrations')
                    ->where('id', $migration->id)
                    ->update([
                        'migration' => $newName,
                        'batch' => 1
                    ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Revertir los cambios si es necesario
        if (Schema::hasTable('migrations')) {
            $migrations = DB::table('migrations')
                ->where('migration', 'like', '2023_%')
                ->get();
            
            foreach ($migrations as $migration) {
                $newName = '0001' . substr($migration->migration, 5);
                DB::table('migrations')
                    ->where('id', $migration->id)
                    ->update([
                        'migration' => $newName,
                        'batch' => 1
                    ]);
            }
        }
    }
}
