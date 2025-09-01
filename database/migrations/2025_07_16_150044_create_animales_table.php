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
        Schema::create('animales', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('tipo');
            $table->string('raza')->nullable();
            $table->integer('edad');
            $table->enum('sexo', ['macho', 'hembra']);
            $table->text('descripcion');
            $table->string('imagen')->nullable();
            $table->unsignedBigInteger('fundacion_id');
            $table->enum('estado', ['disponible', 'adoptado', 'en_adopcion']);
            $table->timestamps();

            $table->foreign('fundacion_id')->references('usuario_id')->on('perfil_fundaciones')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('animales');
    }
};
