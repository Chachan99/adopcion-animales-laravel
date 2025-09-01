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
        Schema::create('mascotas_perdidas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('tipo');
            $table->string('raza')->nullable();
            $table->integer('edad');
            $table->enum('tipo_edad', ['meses', 'años']);
            $table->enum('sexo', ['macho', 'hembra']);
            $table->text('descripcion');
            $table->string('imagen');
            
            // Información de contacto
            $table->string('telefono_contacto');
            $table->string('email_contacto');
            $table->string('recompensa')->nullable();
            
            // Ubicación
            $table->string('ultima_ubicacion');
            $table->decimal('latitud', 10, 8)->nullable();
            $table->decimal('longitud', 11, 8)->nullable();
            $table->text('direccion')->nullable();
            
            // Estado y fechas
            $table->enum('estado', ['perdido', 'encontrado'])->default('perdido');
            $table->timestamp('fecha_encontrado')->nullable();
            
            // Relaciones
            $table->foreignId('usuario_id')->nullable()->constrained('usuarios')->onDelete('set null');
            $table->foreignId('fundacion_id')->nullable()->constrained('usuarios')->onDelete('set null');
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mascotas_perdidas');
    }
};
