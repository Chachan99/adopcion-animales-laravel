<?php

namespace Database\Factories;

use App\Models\Noticia;
use App\Models\Usuario;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Noticia>
 */
class NoticiaFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Noticia::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $titulo = $this->faker->sentence(6);
        $contenido = collect($this->faker->paragraphs(rand(3, 10)))
            ->map(fn($p) => "<p>$p</p>")
            ->implode('');
        
        // Asegurarse de que haya al menos un usuario administrador
        $usuario = Usuario::where('rol', 'admin')->first() ?? 
                  Usuario::factory()->create(['rol' => 'admin']);
        
        return [
            'titulo' => $titulo,
            'slug' => Str::slug($titulo),
            'contenido' => $contenido,
            'resumen' => $this->faker->paragraph(2),
            'autor' => $this->faker->name,
            'usuario_id' => $usuario->id,
            'destacada' => $this->faker->boolean(20), // 20% de probabilidad de ser destacada
            'estado' => $this->faker->randomElement(['borrador', 'publicada', 'archivada']),
            'publicado_en' => $this->faker->optional(0.8, null)->dateTimeBetween('-1 year', '+1 month'),
            'vistas' => $this->faker->numberBetween(0, 1000),
            'created_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'updated_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];
    }
    
    /**
     * Indica que la noticia está publicada.
     */
    public function publicada()
    {
        return $this->state(function (array $attributes) {
            return [
                'estado' => 'publicada',
                'publicado_en' => $this->faker->dateTimeBetween('-1 year', 'now'),
            ];
        });
    }
    
    /**
     * Indica que la noticia está en borrador.
     */
    public function borrador()
    {
        return $this->state(function (array $attributes) {
            return [
                'estado' => 'borrador',
                'publicado_en' => null,
            ];
        });
    }
    
    /**
     * Indica que la noticia está archivada.
     */
    public function archivada()
    {
        return $this->state(function (array $attributes) {
            return [
                'estado' => 'archivada',
                'publicado_en' => $this->faker->dateTimeBetween('-1 year', 'now'),
            ];
        });
    }
    
    /**
     * Indica que la noticia es destacada.
     */
    public function destacada()
    {
        return $this->state(function (array $attributes) {
            return [
                'destacada' => true,
                'estado' => 'publicada', // Las destacadas deben estar publicadas
                'publicado_en' => $this->faker->dateTimeBetween('-1 year', 'now'),
            ];
        });
    }
}
