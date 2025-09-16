<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Siempre ejecutar AdminSeeder (es condicional internamente)
        $this->call([
            AdminSeeder::class,
        ]);

        // DemoDataSeeder deshabilitado - No se ejecuta automáticamente
        echo "⚠️ DemoDataSeeder deshabilitado para evitar problemas en build\n";
        echo "💡 Usar /ejecutar-seeders desde el navegador si necesitas datos de prueba\n";
        
        // Código comentado para referencia
        /*
        // Solo ejecutar DemoDataSeeder en desarrollo/testing
        if (!app()->environment('production')) {
            $this->call([
                DemoDataSeeder::class,
            ]);
        } else {
            echo "⚠️ DemoDataSeeder omitido en producción\n";
        }
        */
    }
}
