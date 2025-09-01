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
        // Check if the column exists and modify it
        Schema::table('solicitudes_adopcion', function (Blueprint $table) {
            if (Schema::hasColumn('solicitudes_adopcion', 'direccion')) {
                $table->text('direccion')->nullable()->change();
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
            if (Schema::hasColumn('solicitudes_adopcion', 'direccion')) {
                $table->text('direccion')->nullable(false)->change();
            }
        });
    }
};
