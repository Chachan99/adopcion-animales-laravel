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
        Schema::table('perfil_fundaciones', function (Blueprint $table) {
            // Renombrar cuenta_bancaria a numero_cuenta si existe
            if (Schema::hasColumn('perfil_fundaciones', 'cuenta_bancaria')) {
                $table->renameColumn('cuenta_bancaria', 'numero_cuenta');
            } else if (!Schema::hasColumn('perfil_fundaciones', 'numero_cuenta')) {
                $table->string('numero_cuenta')->nullable()->after('imagen');
            }
            
            // Asegurar que las demás columnas existan
            if (!Schema::hasColumn('perfil_fundaciones', 'banco')) {
                $table->string('banco', 100)->nullable()->after('numero_cuenta');
            }
            
            if (!Schema::hasColumn('perfil_fundaciones', 'tipo_cuenta')) {
                $table->string('tipo_cuenta', 20)->nullable()->after('banco');
            }
            
            // Agregar columnas faltantes
            if (!Schema::hasColumn('perfil_fundaciones', 'titular_cuenta')) {
                $table->string('titular_cuenta', 255)->nullable()->after('tipo_cuenta');
            }
            
            if (!Schema::hasColumn('perfil_fundaciones', 'tipo_identificacion')) {
                $table->string('tipo_identificacion', 50)->nullable()->after('titular_cuenta');
            }
            
            if (!Schema::hasColumn('perfil_fundaciones', 'identificacion_titular')) {
                $table->string('identificacion_titular', 50)->nullable()->after('tipo_identificacion');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('perfil_fundaciones', function (Blueprint $table) {
            // No revertir el cambio de nombre para mantener consistencia
            $table->dropColumn([
                'banco',
                'tipo_cuenta',
                'numero_cuenta',
                'titular_cuenta',
                'tipo_identificacion',
                'identificacion_titular'
            ]);
        });
    }
};
