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

        // Solo ejecutar DemoDataSeeder en desarrollo/testing
        if (!app()->environment('production')) {
            $this->call([
                DemoDataSeeder::class,
            ]);
        } else {
            echo "⚠️ DemoDataSeeder omitido en producción\n";
        }
    }
}
