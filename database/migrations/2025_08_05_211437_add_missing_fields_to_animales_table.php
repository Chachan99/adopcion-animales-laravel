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
        Schema::table('animales', function (Blueprint $table) {
            if (!Schema::hasColumn('animales', 'tipo_edad')) {
                $table->enum('tipo_edad', ['meses', 'años', 'anios'])->after('edad')->default('meses');
            }
            
            if (!Schema::hasColumn('animales', 'latitud')) {
                $table->decimal('latitud', 10, 8)->nullable()->after('imagen');
            }
            
            if (!Schema::hasColumn('animales', 'longitud')) {
                $table->decimal('longitud', 11, 8)->nullable()->after('latitud');
            }
            
            if (!Schema::hasColumn('animales', 'direccion')) {
                $table->string('direccion', 500)->nullable()->after('longitud');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('animales', function (Blueprint $table) {
            $table->dropColumn([
                'tipo_edad',
                'latitud',
                'longitud',
                'direccion'
            ]);
        });
    }
};
