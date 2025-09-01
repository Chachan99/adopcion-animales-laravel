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
        // Check if the column exists and modify it
        Schema::table('solicitudes_adopcion', function (Blueprint $table) {
            if (Schema::hasColumn('solicitudes_adopcion', 'telefono')) {
                $table->string('telefono')->nullable()->change();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert the changes if needed
        Schema::table('solicitudes_adopcion', function (Blueprint $table) {
            if (Schema::hasColumn('solicitudes_adopcion', 'telefono')) {
                $table->string('telefono')->nullable(false)->change();
            }
        });
    }
};
