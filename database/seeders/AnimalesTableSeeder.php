<?php

namespace Database\Seeders;

use App\Models\Animal;
use App\Models\PerfilFundacion;
use App\Models\Usuario;
use Illuminate\Database\Seeder;

class AnimalesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        // Obtener todas las fundaciones existentes
        $fundaciones = PerfilFundacion::pluck('id')->toArray();
        
        // Si no hay fundaciones, crear una para las pruebas
        if (empty($fundaciones)) {
            // Crear usuario si no existe
            $usuario = Usuario::firstOrCreate(
                ['email' => 'fundacion@ejemplo.com'],
                [
                    'nombre' => 'Fundación',
                    'apellido' => 'Ejemplo',
                    'password' => bcrypt('password'),
                    'tipo_usuario' => 'fundacion',
                    'email_verified_at' => now(),
                ]
            );
            
            $fundacion = PerfilFundacion::create([
                'nombre' => 'Fundación Ejemplo',
                'descripcion' => 'Fundación de ejemplo para pruebas',
                'telefono' => '+34 123 456 789',
                'direccion' => 'Calle Ejemplo 123, Madrid, España',
                'email' => 'contacto@fundacionejemplo.org',
                'sitio_web' => 'https://www.fundacionejemplo.org',
                'facebook' => 'https://facebook.com/ejemplo',
                'instagram' => 'https://instagram.com/ejemplo',
                'usuario_id' => $usuario->id,
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

        // Datos de ejemplo para animales
        $animalesEjemplo = [
            ['nombre' => 'Max', 'tipo' => 'Perro', 'raza' => 'Labrador', 'edad' => 3, 'sexo' => 'macho', 'descripcion' => 'Max es un perro muy cariñoso y juguetón. Le encanta correr en el parque y jugar con otros perros. Es ideal para familias con niños.', 'estado' => 'disponible'],
            ['nombre' => 'Luna', 'tipo' => 'Gato', 'raza' => 'Siamés', 'edad' => 2, 'sexo' => 'hembra', 'descripcion' => 'Luna es una gata muy elegante y tranquila. Le gusta descansar en lugares altos y observar por la ventana. Es perfecta para apartamentos.', 'estado' => 'disponible'],
            ['nombre' => 'Rocky', 'tipo' => 'Perro', 'raza' => 'Pastor Alemán', 'edad' => 5, 'sexo' => 'macho', 'descripcion' => 'Rocky es un perro leal y protector. Está bien entrenado y es excelente como perro guardián. Necesita ejercicio diario.', 'estado' => 'disponible'],
            ['nombre' => 'Mimi', 'tipo' => 'Gato', 'raza' => 'Persa', 'edad' => 4, 'sexo' => 'hembra', 'descripcion' => 'Mimi es una gata de pelo largo muy dulce. Le encanta que la cepillen y es muy cariñosa con sus dueños.', 'estado' => 'en_adopcion'],
            ['nombre' => 'Buddy', 'tipo' => 'Perro', 'raza' => 'Golden Retriever', 'edad' => 1, 'sexo' => 'macho', 'descripcion' => 'Buddy es un cachorro muy energético y amigable. Le encanta aprender trucos nuevos y jugar con pelotas.', 'estado' => 'disponible'],
            ['nombre' => 'Coco', 'tipo' => 'Conejo', 'raza' => 'Holandés', 'edad' => 2, 'sexo' => 'hembra', 'descripcion' => 'Coco es una conejita muy activa y curiosa. Le gusta explorar y necesita espacio para saltar y correr.', 'estado' => 'disponible'],
            ['nombre' => 'Pipo', 'tipo' => 'Pájaro', 'raza' => 'Canario', 'edad' => 1, 'sexo' => 'macho', 'descripcion' => 'Pipo es un canario que canta hermosamente por las mañanas. Es muy colorido y alegre.', 'estado' => 'disponible'],
            ['nombre' => 'Nemo', 'tipo' => 'Tortuga', 'raza' => 'De Agua', 'edad' => 8, 'sexo' => 'macho', 'descripcion' => 'Nemo es una tortuga tranquila que necesita un acuario espacioso. Es fácil de cuidar y muy longeva.', 'estado' => 'disponible']
        ];
        
        // Crear animales de ejemplo
        foreach ($animalesEjemplo as $index => $animalData) {
            Animal::create([
                'fundacion_id' => $fundaciones[array_rand($fundaciones)],
                'nombre' => $animalData['nombre'],
                'tipo' => $animalData['tipo'],
                'raza' => $animalData['raza'],
                'edad' => $animalData['edad'],
                'sexo' => $animalData['sexo'],
                'descripcion' => $animalData['descripcion'],
                'imagen' => 'img/animales/' . strtolower($animalData['tipo']) . ($index % 5 + 1) . '.jpg',
                'estado' => $animalData['estado'],
            ]);
        }
    }
}
