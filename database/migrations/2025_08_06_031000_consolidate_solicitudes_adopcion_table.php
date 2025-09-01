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
        // Create the base table if it doesn't exist
        if (!Schema::hasTable('solicitudes_adopcion')) {
            Schema::create('solicitudes_adopcion', function (Blueprint $table) {
                $table->id();
                $table->foreignId('usuario_id')->nullable()->constrained('usuarios')->onDelete('set null');
                $table->foreignId('animal_id')->constrained('animales')->onDelete('cascade');
                $table->foreignId('fundacion_id')->nullable()->constrained('perfil_fundaciones')->onDelete('set null');
                
                // Personal information
                $table->string('nombre_solicitante');
                $table->string('email_solicitante');
                $table->string('telefono_solicitante');
                $table->string('identificacion')->nullable();
                $table->integer('edad_solicitante');
                
                // Address information
                $table->text('direccion_solicitante');
                $table->string('barrio')->nullable();
                $table->text('referencia')->nullable();
                
                // Home information
                $table->enum('tipo_vivienda', ['casa', 'apartamento', 'duplex', 'otro']);
                $table->enum('tiene_patio', ['si', 'no']);
                $table->enum('otros_mascotas', ['si', 'no']);
                $table->enum('experiencia_mascotas', ['nada', 'poca', 'moderada', 'mucha']);
                
                // Adoption details
                $table->text('motivo_adopcion');
                $table->enum('tiempo_disponible', ['poco', 'moderado', 'mucho']);
                $table->text('bienestar_mascota')->nullable();
                $table->text('conocimiento_cuidados')->nullable();
                $table->text('compromiso_esterilizacion')->nullable();
                $table->text('preguntas_seguridad')->nullable();
                $table->text('mensaje')->nullable();
                
                // Status and tracking
                $table->enum('estado', ['pendiente', 'en_revision', 'aprobada', 'rechazada'])->default('pendiente');
                $table->text('comentario')->nullable();
                $table->text('respuesta')->nullable();
                
                // Timestamps
                $table->timestamps();
            });
        } else {
            // Table exists, let's add any missing columns
            Schema::table('solicitudes_adopcion', function (Blueprint $table) {
                // Make usuario_id nullable if it exists
                if (Schema::hasColumn('solicitudes_adopcion', 'usuario_id')) {
                    $table->foreignId('usuario_id')->nullable()->change();
                }
                
                // Add fundacion_id if it doesn't exist
                if (!Schema::hasColumn('solicitudes_adopcion', 'fundacion_id')) {
                    $table->foreignId('fundacion_id')->nullable()->after('animal_id')
                          ->constrained('perfil_fundaciones')->onDelete('set null');
                }
                
                // Add other columns if they don't exist
                $columnsToAdd = [
                    'nombre_solicitante' => 'string',
                    'email_solicitante' => 'string',
                    'telefono_solicitante' => 'string',
                    'identificacion' => 'string',
                    'edad_solicitante' => 'integer',
                    'direccion_solicitante' => 'text',
                    'barrio' => 'string',
                    'referencia' => 'text',
                    'tipo_vivienda' => 'enum',
                    'tiene_patio' => 'enum',
                    'otros_mascotas' => 'enum',
                    'experiencia_mascotas' => 'enum',
                    'motivo_adopcion' => 'text',
                    'tiempo_disponible' => 'enum',
                    'bienestar_mascota' => 'text',
                    'conocimiento_cuidados' => 'text',
                    'compromiso_esterilizacion' => 'text',
                    'preguntas_seguridad' => 'text',
                    'mensaje' => 'text',
                    'estado' => 'enum',
                    'comentario' => 'text',
                    'respuesta' => 'text',
                ];
                
                foreach ($columnsToAdd as $column => $type) {
                    if (!Schema::hasColumn('solicitudes_adopcion', $column)) {
                        if ($type === 'enum') {
                            switch ($column) {
                                case 'tipo_vivienda':
                                    $table->enum('tipo_vivienda', ['casa', 'apartamento', 'duplex', 'otro'])->after('direccion_solicitante');
                                    break;
                                case 'tiene_patio':
                                case 'otros_mascotas':
                                    $table->enum($column, ['si', 'no'])->after('tipo_vivienda');
                                    break;
                                case 'experiencia_mascotas':
                                    $table->enum('experiencia_mascotas', ['nada', 'poca', 'moderada', 'mucha'])->after('otros_mascotas');
                                    break;
                                case 'tiempo_disponible':
                                    $table->enum('tiempo_disponible', ['poco', 'moderado', 'mucho'])->after('motivo_adopcion');
                                    break;
                                case 'estado':
                                    $table->enum('estado', ['pendiente', 'en_revision', 'aprobada', 'rechazada'])->default('pendiente');
                                    break;
                            }
                        } else {
                            $table->{$type}($column)->nullable();
                        }
                    }
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // We won't drop the table in the down method to prevent data loss
        // You can manually drop it if needed
    }
};
