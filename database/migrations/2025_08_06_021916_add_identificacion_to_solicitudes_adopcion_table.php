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
            if (!Schema::hasColumn('solicitudes_adopcion', 'identificacion')) {
                $table->string('identificacion')->after('telefono_solicitante');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('solicitudes_adopcion', function (Blueprint $table) {
            if (Schema::hasColumn('solicitudes_adopcion', 'identificacion')) {
                $table->dropColumn('identificacion');
            }
        });
    }
};
