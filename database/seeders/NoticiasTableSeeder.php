<?php

namespace Database\Seeders;

use App\Models\Noticia;
use App\Models\Usuario;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class NoticiasTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener el primer usuario administrador o crear uno si no existe
        $admin = Usuario::where('rol', 'admin')->first();
        
        if (!$admin) {
            $admin = Usuario::create([
                'nombre' => 'Administrador',
                'email' => 'admin@example.com',
                'password' => bcrypt('password'),
                'rol' => 'admin'
            ]);
        }

        // Obtener la primera fundación o crear una si no existe
        $fundacion = \App\Models\PerfilFundacion::first();
        
        if (!$fundacion) {
            // Crear un usuario fundación si no existe
            $usuarioFundacion = Usuario::where('rol', 'fundacion')->first();
            if (!$usuarioFundacion) {
                $usuarioFundacion = Usuario::create([
                    'nombre' => 'Fundación Ejemplo',
                    'email' => 'fundacion@example.com',
                    'password' => bcrypt('password'),
                    'rol' => 'fundacion'
                ]);
            }
            
            $fundacion = \App\Models\PerfilFundacion::create([
                'usuario_id' => $usuarioFundacion->id,
                'nombre' => 'Fundación Ejemplo',
                'descripcion' => 'Fundación dedicada al rescate y adopción de animales',
                'direccion' => 'Calle Ejemplo 123',
                'telefono' => '123456789',
                'email' => 'fundacion@example.com'
            ]);
        }

        // Datos de ejemplo para noticias
        $noticias = [
            [
                'titulo' => 'Consejos para adoptar una mascota responsablemente',
                'contenido' => '<p>Adoptar una mascota es una decisión importante que requiere compromiso y responsabilidad. En este artículo te ofrecemos consejos para asegurar una adopción exitosa y feliz para ti y tu nueva mascota.</p><p>1. Investiga sobre la raza o tipo de mascota que deseas adoptar.</p><p>2. Asegúrate de tener el tiempo y recursos necesarios para su cuidado.</p><p>3. Prepara tu hogar para recibir a tu nueva mascota.</p>',
                'resumen' => 'Aprende los pasos esenciales para una adopción responsable y cómo prepararte para recibir a tu nueva mascota en casa.',
                'imagen' => 'img/noticias/adopcion-responsable.jpg',
                'autor' => 'Equipo de Adopciones',
                'estado' => 'publicada',
                'destacada' => true,
                'publicado_en' => now()->subDays(5),
                'vistas' => 124,
            ],
            [
                'titulo' => 'Beneficios de la esterilización en mascotas',
                'contenido' => '<p>La esterilización es un procedimiento quirúrgico que ofrece numerosos beneficios para la salud de tu mascota y ayuda a controlar la sobrepoblación animal.</p><p>Entre los beneficios se incluyen:</p><ul><li>Reducción del riesgo de ciertos tipos de cáncer</li><li>Comportamiento más tranquilo</li><li>Prevención de camadas no deseadas</li></ul>',
                'resumen' => 'Descubre por qué la esterilización es una de las decisiones más importantes que puedes tomar por la salud de tu mascota.',
                'imagen' => 'img/noticias/esterilizacion.jpg',
                'autor' => 'Dr. Carlos Mendez',
                'estado' => 'publicada',
                'destacada' => true,
                'publicado_en' => now()->subDays(10),
                'vistas' => 87,
            ],
            [
                'titulo' => 'Historia de superación: Max encuentra un hogar',
                'contenido' => '<p>Max fue rescatado de las calles en condiciones deplorables. Después de meses de cuidados y recuperación, finalmente encontró un hogar amoroso donde es amado y cuidado.</p><p>"Nunca pensé que un perro podría cambiar tanto mi vida", comenta su nueva dueña, María. "Max ha traído tanta alegría a nuestro hogar."</p>',
                'resumen' => 'La conmovedora historia de cómo Max encontró un hogar después de un difícil comienzo en la vida.',
                'imagen' => 'img/noticias/historia-max.jpg',
                'autor' => 'Equipo de Rescate',
                'estado' => 'publicada',
                'destacada' => false,
                'publicado_en' => now()->subDays(3),
                'vistas' => 45,
            ],
            [
                'titulo' => 'Guía de alimentación para perros adultos',
                'contenido' => '<p>Una nutrición adecuada es fundamental para la salud de tu perro. En esta guía te explicamos todo lo que necesitas saber sobre la alimentación canina.</p><h3>Tipos de alimento</h3><p>Existen diferentes tipos de alimentos para perros, cada uno con sus ventajas y desventajas.</p>',
                'resumen' => 'Aprende a elegir la mejor alimentación para tu perro adulto y asegúrate de que reciba todos los nutrientes que necesita.',
                'imagen' => 'img/noticias/alimentacion-perros.jpg',
                'autor' => 'NutriPet',
                'estado' => 'publicada',
                'destacada' => false,
                'publicado_en' => now()->subDays(15),
                'vistas' => 67,
            ],
            [
                'titulo' => 'Evento de adopción este fin de semana',
                'contenido' => '<p>¡No te pierdas nuestro gran evento de adopción este fin de semana! Tendremos más de 50 perros y gatos buscando un hogar amoroso.</p><p>Fecha: 5-6 de agosto, 2023</p><p>Lugar: Parque Central, de 10:00 am a 6:00 pm</p><p>Habrá actividades, concursos y asesoría gratuita sobre tenencia responsable.</p>',
                'resumen' => 'Participa en nuestro evento de adopción y encuentra a tu compañero peludo ideal.',
                'imagen' => 'img/noticias/evento-adopcion.jpg',
                'autor' => 'Equipo de Eventos',
                'estado' => 'publicada',
                'destacada' => true,
                'publicado_en' => now()->subDays(1),
                'vistas' => 32,
            ],
        ];

        // Insertar las noticias
        foreach ($noticias as $noticia) {
            // Generar slug único
            $slug = Str::slug($noticia['titulo']);
            $count = 1;
            
            while (Noticia::where('slug', $slug)->exists()) {
                $slug = Str::slug($noticia['titulo']) . '-' . $count++;
            }
            
            // Crear la noticia
            Noticia::create(array_merge($noticia, [
                'slug' => $slug,
                'usuario_id' => $admin->id,
                'fundacion_id' => $fundacion->id,
                'created_at' => $noticia['publicado_en'] ?? now(),
                'updated_at' => $noticia['publicado_en'] ?? now(),
            ]));
        }
        
        // Crear algunas noticias adicionales
        $titulosAdicionales = [
            'Consejos para el cuidado de gatos recién adoptados',
            '¿Cómo socializar a tu perro con otros animales?',
            'Los beneficios de adoptar mascotas adultas',
            'Guía de primeros auxilios para mascotas',
            'Cómo preparar tu hogar para un gato',
            'Señales de que tu perro está estresado',
            'Alimentos peligrosos para perros que debes evitar',
            'La importancia de la identificación en mascotas',
            'Cómo viajar con tu mascota de forma segura',
            'Actividades para hacer con tu perro en casa',
            'Cuidados especiales para perros mayores',
            'Cómo cepillar correctamente a tu gato',
            'Juguetes caseros para tus mascotas',
            'Señales de que tu gato te quiere',
            'Consejos para pasear a tu perro en días lluviosos'
        ];

        foreach ($titulosAdicionales as $titulo) {
            $slug = Str::slug($titulo);
            $count = 1;
            
            while (Noticia::where('slug', $slug)->exists()) {
                $slug = Str::slug($titulo) . '-' . $count++;
            }
            
            Noticia::create([
                'titulo' => $titulo,
                'slug' => $slug,
                'contenido' => '<p>Contenido de ejemplo para la noticia: ' . $titulo . '</p>',
                'resumen' => 'Resumen de la noticia: ' . $titulo,
                'autor' => 'Equipo de Adopciones',
                'estado' => 'publicada',
                'destacada' => rand(0, 1) === 1,
                'publicado_en' => now()->subDays(rand(1, 60)),
                'vistas' => rand(10, 200),
                'usuario_id' => $admin->id,
                'fundacion_id' => $fundacion->id,
                'created_at' => now()->subDays(rand(1, 60)),
                'updated_at' => now()->subDays(rand(1, 60)),
            ]);
        }
        
        // Crear algunas noticias en borrador
        $titulosBorrador = [
            'Entrevista con un entrenador canino profesional',
            'Guía completa de razas de perros',
            'El impacto emocional de las mascotas en la salud mental',
            'Cómo crear un jardín seguro para gatos',
            'Historias de adopción que te conmoverán'
        ];
        
        foreach ($titulosBorrador as $titulo) {
            $slug = Str::slug($titulo);
            $count = 1;
            
            while (Noticia::where('slug', $slug)->exists()) {
                $slug = Str::slug($titulo) . '-' . $count++;
            }
            
            Noticia::create([
                'titulo' => $titulo,
                'slug' => $slug,
                'contenido' => '<p>Borrador de la noticia: ' . $titulo . '</p>',
                'resumen' => 'Borrador: ' . $titulo,
                'autor' => 'Equipo de Adopciones',
                'estado' => 'borrador',
                'destacada' => false,
                'publicado_en' => null,
                'vistas' => 0,
                'usuario_id' => $admin->id,
                'fundacion_id' => $fundacion->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
