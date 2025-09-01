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
    public function up(): void
    {
        // SQLite doesn't support MODIFY COLUMN, so we'll recreate the table
        Schema::table('animales', function (Blueprint $table) {
            // Drop foreign key first
            $table->dropForeign(['fundacion_id']);
        });

        // For SQLite, we'll just add a check constraint or handle it in the application
        // Since SQLite doesn't have native ENUM support, we'll use a string column with validation
        
        // Re-add the foreign key constraint
        Schema::table('animales', function (Blueprint $table) {
            $table->foreign('fundacion_id')
                  ->references('usuario_id')
                  ->on('perfil_fundaciones')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop the foreign key constraint
        Schema::table('animales', function (Blueprint $table) {
            $table->dropForeign(['fundacion_id']);
        });

        // For SQLite, no need to modify the column structure
        
        // Re-add the foreign key constraint
        Schema::table('animales', function (Blueprint $table) {
            $table->foreign('fundacion_id')
                  ->references('usuario_id')
                  ->on('perfil_fundaciones')
                  ->onDelete('cascade');
        });
    }
};