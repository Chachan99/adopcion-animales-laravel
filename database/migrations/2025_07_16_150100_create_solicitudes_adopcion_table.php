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
    Schema::create('solicitudes_adopcion', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('usuario_id')->nullable();
        $table->unsignedBigInteger('animal_id');
        $table->unsignedBigInteger('fundacion_id')->nullable();
        $table->text('mensaje')->nullable();
        $table->enum('estado', ['pendiente', 'aprobado', 'rechazado'])->default('pendiente');
        $table->timestamps();

        $table->foreign('usuario_id')->references('id')->on('usuarios')->onDelete('set null');
        $table->foreign('animal_id')->references('id')->on('animales')->onDelete('cascade');
        $table->foreign('fundacion_id')->references('id')->on('perfil_fundaciones')->onDelete('set null');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('solicitudes_adopcion');
    }
};
