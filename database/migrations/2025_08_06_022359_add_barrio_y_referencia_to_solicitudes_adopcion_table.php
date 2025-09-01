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
            if (!Schema::hasColumn('solicitudes_adopcion', 'barrio')) {
                $table->string('barrio')->after('direccion_solicitante')->nullable();
            }
            
            if (!Schema::hasColumn('solicitudes_adopcion', 'referencia')) {
                $table->string('referencia')->after('barrio')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('solicitudes_adopcion', function (Blueprint $table) {
            if (Schema::hasColumn('solicitudes_adopcion', 'barrio')) {
                $table->dropColumn('barrio');
            }
            
            if (Schema::hasColumn('solicitudes_adopcion', 'referencia')) {
                $table->dropColumn('referencia');
            }
        });
    }
};
