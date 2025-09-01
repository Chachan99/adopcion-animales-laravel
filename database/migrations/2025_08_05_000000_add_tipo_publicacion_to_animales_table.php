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
        Schema::table('animales', function (Blueprint $table) {
            $table->enum('tipo_publicacion', ['adopcion', 'perdido'])->default('adopcion')->after('estado');
            $table->string('telefono_contacto')->nullable()->after('tipo_publicacion');
            $table->string('email_contacto')->nullable()->after('telefono_contacto');
            $table->string('recompensa')->nullable()->after('email_contacto');
            $table->string('ultima_ubicacion')->nullable()->after('recompensa');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('animales', function (Blueprint $table) {
            $table->dropColumn([
                'tipo_publicacion',
                'telefono_contacto',
                'email_contacto',
                'recompensa',
                'ultima_ubicacion'
            ]);
        });
    }
};
