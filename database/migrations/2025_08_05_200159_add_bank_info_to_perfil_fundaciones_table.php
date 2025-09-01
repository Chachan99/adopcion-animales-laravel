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
            // Only add columns that don't already exist
            if (!Schema::hasColumn('perfil_fundaciones', 'email_contacto_pagos')) {
                $table->string('email_contacto_pagos')->nullable()->after('identificacion_titular');
            }
            
            if (!Schema::hasColumn('perfil_fundaciones', 'otros_metodos_pago')) {
                $table->text('otros_metodos_pago')->nullable()->after('email_contacto_pagos');
            }
            
            // Ensure the banco column is named consistently
            if (Schema::hasColumn('perfil_fundaciones', 'banco') && !Schema::hasColumn('perfil_fundaciones', 'banco_nombre')) {
                $table->renameColumn('banco', 'banco_nombre');
            } else if (!Schema::hasColumn('perfil_fundaciones', 'banco_nombre')) {
                $table->string('banco_nombre')->nullable()->after('descripcion');
            }
            
            // Ensure titular_cuenta is named consistently
            if (Schema::hasColumn('perfil_fundaciones', 'titular_cuenta') && !Schema::hasColumn('perfil_fundaciones', 'nombre_titular')) {
                $table->renameColumn('titular_cuenta', 'nombre_titular');
            } else if (!Schema::hasColumn('perfil_fundaciones', 'nombre_titular')) {
                $table->string('nombre_titular')->nullable()->after('tipo_cuenta');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('perfil_fundaciones', function (Blueprint $table) {
            // Only drop columns that were added by this migration
            if (Schema::hasColumn('perfil_fundaciones', 'email_contacto_pagos')) {
                $table->dropColumn('email_contacto_pagos');
            }
            
            if (Schema::hasColumn('perfil_fundaciones', 'otros_metodos_pago')) {
                $table->dropColumn('otros_metodos_pago');
            }
            
            // Revert column name changes if they were made
            if (Schema::hasColumn('perfil_fundaciones', 'banco_nombre') && !Schema::hasColumn('perfil_fundaciones', 'banco')) {
                $table->renameColumn('banco_nombre', 'banco');
            }
            
            if (Schema::hasColumn('perfil_fundaciones', 'nombre_titular') && !Schema::hasColumn('perfil_fundaciones', 'titular_cuenta')) {
                $table->renameColumn('nombre_titular', 'titular_cuenta');
            }
        });
    }
};
