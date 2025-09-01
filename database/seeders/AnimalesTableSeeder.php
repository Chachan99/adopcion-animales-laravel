<?php

namespace Database\Seeders;

use App\Models\Animal;
use App\Models\PerfilFundacion;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class AnimalesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('es_ES');
        
        // Obtener todas las fundaciones existentes
        $fundaciones = PerfilFundacion::pluck('id')->toArray();
        
        // Si no hay fundaciones, crear una para las pruebas
        if (empty($fundaciones)) {
            $fundacion = PerfilFundacion::create([
                'nombre' => 'Fundación Ejemplo',
                'descripcion' => 'Fundación de ejemplo para pruebas',
                'telefono' => $faker->phoneNumber,
                'direccion' => $faker->address,
                'email' => $faker->companyEmail,
                'sitio_web' => $faker->url,
                'facebook' => 'https://facebook.com/ejemplo',
                'instagram' => 'https://instagram.com/ejemplo',
                'usuario_id' => 1, // Asegúrate de que exista un usuario con este ID
                'imagen' => 'fundaciones/ejemplo.jpg',
            ]);
            $fundaciones = [$fundacion->id];
        }

        // Tipos de animales
        $tipos = ['Perro', 'Gato', 'Conejo', 'Hámster', 'Pájaro', 'Tortuga'];
        
        // Razas comunes por tipo
        $razas = [
            'Perro' => ['Labrador', 'Pastor Alemán', 'Bulldog', 'Golden Retriever', 'Chihuahua', 'Poodle'],
            'Gato' => ['Siamés', 'Persa', 'Maine Coon', 'Bengalí', 'Esfinge', 'Común Europeo'],
            'Conejo' => ['Holandés', 'Cabeza de León', 'Angora', 'Mini Rex', 'Enano', 'Belier'],
            'Hámster' => ['Sirio', 'Ruso', 'Roborovski', 'Chino', 'Campo'],
            'Pájaro' => ['Canario', 'Periquito', 'Agapornis', 'Ninfa', 'Diamante Mandarín'],
            'Tortuga' => ['Rusa', 'De Agua', 'De Tierra', 'Mordedora', 'De Orejas Rojas']
        ];

        // Generar 20 animales de ejemplo
        for ($i = 0; $i < 20; $i++) {
            $tipo = $faker->randomElement($tipos);
            $raza = $faker->randomElement($razas[$tipo]);
            
            Animal::create([
                'fundacion_id' => $faker->randomElement($fundaciones),
                'nombre' => $faker->firstName,
                'tipo' => $tipo,
                'raza' => $raza,
                'edad' => $faker->numberBetween(1, 15),
                'sexo' => $faker->randomElement(['macho', 'hembra']),
                'descripcion' => $faker->paragraph(3),
                'imagen' => 'img/animales/' . strtolower($tipo) . $faker->numberBetween(1, 5) . '.jpg',
                'estado' => $faker->randomElement(['disponible', 'adoptado', 'en_adopcion']),
            ]);
        }
    }
}
