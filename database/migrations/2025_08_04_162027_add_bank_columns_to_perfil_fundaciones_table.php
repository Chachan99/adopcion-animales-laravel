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
            $table->string('cuenta_bancaria')->nullable()->after('imagen');
            $table->string('banco', 100)->nullable()->after('cuenta_bancaria');
            $table->string('tipo_cuenta', 20)->nullable()->after('banco');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('perfil_fundaciones', function (Blueprint $table) {
            $table->dropColumn(['cuenta_bancaria', 'banco', 'tipo_cuenta']);
        });
    }
};
