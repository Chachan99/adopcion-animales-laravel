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
        Schema::create('noticias', function (Blueprint $table) {
            // Campos básicos
            $table->id();
            $table->foreignId('fundacion_id')->constrained('perfil_fundaciones')->onDelete('cascade');
            $table->foreignId('usuario_id')->nullable()->constrained('usuarios')->onDelete('set null');
            
            // Contenido de la noticia
            $table->string('titulo');
            $table->string('slug')->unique();
            $table->text('contenido');
            $table->text('resumen')->nullable();
            
            // Multimedia
            $table->string('imagen')->nullable();
            
            // Metadatos
            $table->string('autor')->nullable();
            $table->boolean('destacada')->default(false);
            $table->boolean('publicada')->default(false);
            $table->enum('estado', ['borrador', 'publicada', 'archivada'])->default('borrador');
            
            // Fechas importantes
            $table->timestamp('fecha_publicacion')->nullable();
            $table->timestamp('publicado_en')->nullable();
            
            // Estadísticas
            $table->unsignedInteger('vistas')->default(0);
            
            // Timestamps y soft delete
            $table->timestamps();
            $table->softDeletes();
            
            // Índices para optimización
            $table->index('estado');
            $table->index('destacada');
            $table->index('publicada');
            $table->index('fecha_publicacion');
            $table->index('publicado_en');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('noticias');
    }
};