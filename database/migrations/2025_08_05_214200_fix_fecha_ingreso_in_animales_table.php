<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        // Check if the column exists and has the correct type
        if (Schema::hasColumn('animales', 'fecha_ingreso')) {
            // Modify the column to ensure it's nullable and has the correct type
            Schema::table('animales', function (Blueprint $table) {
                $table->timestamp('fecha_ingreso')->nullable()->change();
            });
        } else {
            // Add the column if it doesn't exist
            Schema::table('animales', function (Blueprint $table) {
                $table->timestamp('fecha_ingreso')->nullable()->after('estado');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        // This migration is not reversible as it's fixing a column
    }
};
