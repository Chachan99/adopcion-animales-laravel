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
            $table->string('direccion')->nullable()->after('longitud');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('animales', function (Blueprint $table) {
            $table->dropColumn('direccion');
        });
    }
};
