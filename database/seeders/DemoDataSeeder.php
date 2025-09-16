<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Usuario;

class DemoDataSeeder extends Seeder
{
    public function run()
    {
        // SEEDER DESHABILITADO - No ejecutar automáticamente
        echo "⚠️ DemoDataSeeder deshabilitado - No se ejecutará automáticamente\n";
        echo "💡 Para crear datos de prueba manualmente, usar el controlador de diagnóstico\n";
        return;
        
        // Código comentado para referencia futura
        /*
        // ADVERTENCIA: Este seeder es solo para datos de prueba/desarrollo
        // NO debe ejecutarse automáticamente en producción
        if (app()->environment('production')) {
            echo "⚠️ DemoDataSeeder omitido en producción por seguridad\n";
            return;
        }

        // Verificar si ya existen datos de prueba
        if (Usuario::where('email', 'fundacion@patitasfelices.com')->exists()) {
            echo "⚠️ Los datos de prueba ya existen, omitiendo DemoDataSeeder\n";
            return;
        }

        echo "🔧 Ejecutando DemoDataSeeder (solo desarrollo/testing)...\n";
        */
        // Crear usuarios fundación de prueba
        $fundacionUserId = DB::table('usuarios')->insertGetId([
            'nombre' => 'Fundación Patitas Felices',
            'email' => 'fundacion@patitasfelices.com',
            'password' => Hash::make('fundacion123'),
            'rol' => 'fundacion',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Crear perfil de fundación
        $fundacionId = DB::table('perfil_fundaciones')->insertGetId([
            'usuario_id' => $fundacionUserId,
            'nombre' => 'Fundación Patitas Felices',
            'descripcion' => 'Fundación dedicada al rescate y cuidado de animales abandonados.',
            'direccion' => 'Calle Principal 123, Ciudad',
            'telefono' => '+1234567890',
            'email' => 'fundacion@patitasfelices.com',
            'sitio_web' => 'https://patitasfelices.com',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Crear usuario adoptante de prueba
        $adoptanteUserId = DB::table('usuarios')->insertGetId([
            'nombre' => 'María González',
            'email' => 'maria@ejemplo.com',
            'password' => Hash::make('usuario123'),
            'rol' => 'publico',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Crear animales de prueba
        $animales = [
            [
                'nombre' => 'Max',
                'tipo' => 'Perro',
                'raza' => 'Labrador',
                'edad' => 3,
                'tipo_edad' => 'años',
                'sexo' => 'macho',
                'descripcion' => 'Perro muy cariñoso y juguetón, ideal para familias con niños.',
                'estado' => 'disponible',
                'tipo_publicacion' => 'adopcion',
                'direccion' => 'Refugio Central',
                'fundacion_id' => $fundacionId,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Luna',
                'tipo' => 'Gato',
                'raza' => 'Siamés',
                'edad' => 2,
                'tipo_edad' => 'años',
                'sexo' => 'hembra',
                'descripcion' => 'Gata muy tranquila y cariñosa, perfecta para apartamentos.',
                'estado' => 'disponible',
                'tipo_publicacion' => 'adopcion',
                'direccion' => 'Refugio Central',
                'fundacion_id' => $fundacionId,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Rocky',
                'tipo' => 'Perro',
                'raza' => 'Mestizo',
                'edad' => 5,
                'tipo_edad' => 'años',
                'sexo' => 'macho',
                'descripcion' => 'Perro muy leal y protector, busca una familia amorosa.',
                'estado' => 'disponible',
                'tipo_publicacion' => 'adopcion',
                'direccion' => 'Refugio Central',
                'fundacion_id' => $fundacionId,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ];

        foreach ($animales as $animal) {
            DB::table('animales')->insert($animal);
        }

        // Crear noticias de prueba
        $noticias = [
            [
                'fundacion_id' => $fundacionId,
                'titulo' => 'Campaña de Adopción de Fin de Año',
                'slug' => 'campana-adopcion-fin-ano',
                'contenido' => 'Este fin de año queremos encontrar hogares para todos nuestros peluditos. Únete a nuestra campaña especial de adopción.',
                'autor' => 'Fundación Patitas Felices',
                'estado' => 'publicada',
                'publicada' => true,
                'fecha_publicacion' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'fundacion_id' => $fundacionId,
                'titulo' => 'Consejos para el Cuidado de Mascotas en Invierno',
                'slug' => 'consejos-cuidado-mascotas-invierno',
                'contenido' => 'El invierno puede ser una época difícil para nuestras mascotas. Aquí te damos algunos consejos para mantenerlas seguras y cómodas.',
                'autor' => 'Equipo Veterinario',
                'estado' => 'publicada',
                'publicada' => true,
                'fecha_publicacion' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ];

        foreach ($noticias as $noticia) {
            DB::table('noticias')->insert($noticia);
        }

        // Crear donaciones de prueba
        $donaciones = [
            [
                'monto' => 50.00,
                'metodo' => 'transferencia',
                'descripcion' => 'Donación para alimento de los animales',
                'fundacion_id' => $fundacionId,
                'usuario_id' => $adoptanteUserId,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'monto' => 100.00,
                'metodo' => 'efectivo',
                'descripcion' => 'Donación para gastos veterinarios',
                'fundacion_id' => $fundacionId,
                'usuario_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ];

        foreach ($donaciones as $donacion) {
            DB::table('donaciones')->insert($donacion);
        }
    }
}