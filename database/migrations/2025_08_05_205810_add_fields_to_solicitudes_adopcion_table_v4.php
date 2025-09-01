<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('solicitudes_adopcion', function (Blueprint $table) {
            if (!Schema::hasColumn('solicitudes_adopcion', 'ocupacion')) {
                $table->string('ocupacion')->after('edad_solicitante');
            }

            if (!Schema::hasColumn('solicitudes_adopcion', 'direccion')) {
                $table->string('direccion')->after('ocupacion');
            }

            if (!Schema::hasColumn('solicitudes_adopcion', 'telefono')) {
                $table->string('telefono')->after('direccion');
            }

            // Agrega más campos según sea necesario con la misma comprobación
        });
    }

    public function down()
    {
        Schema::table('solicitudes_adopcion', function (Blueprint $table) {
            $table->dropColumn(['ocupacion', 'direccion', 'telefono']);
            // Asegúrate de incluir aquí todos los campos que añades en up()
        });
    }
};