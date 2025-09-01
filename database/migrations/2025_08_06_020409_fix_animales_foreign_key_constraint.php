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
        Schema::table('animales', function (Blueprint $table) {
            // Remove the existing foreign key constraint
            $table->dropForeign(['fundacion_id']);
            
            // Add a new foreign key that references the id column
            $table->foreign('fundacion_id')
                  ->references('id')
                  ->on('perfil_fundaciones')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('animales', function (Blueprint $table) {
            // Drop the new foreign key
            $table->dropForeign(['fundacion_id']);
            
            // Recreate the old foreign key that references usuario_id
            $table->foreign('fundacion_id')
                  ->references('usuario_id')
                  ->on('perfil_fundaciones')
                  ->onDelete('cascade');
        });
    }
};
