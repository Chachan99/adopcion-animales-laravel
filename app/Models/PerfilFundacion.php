<?php                                           
        
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PerfilFundacion extends Model
{
    use HasFactory;

    protected $table = 'perfil_fundaciones';

    protected $fillable = [
        'usuario_id',
        'nombre',
        'descripcion',
        'direccion',
        'telefono',
        'email',
        'sitio_web',
        'facebook',
        'instagram',
        'imagen',
        'banco_nombre',
        'tipo_cuenta',
        'numero_cuenta',
        'nombre_titular',
        'identificacion_titular',
        'tipo_identificacion',
        'email_contacto_pagos',
        'otros_metodos_pago'
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    public function animales()
    {
        return $this->hasMany(Animal::class, 'fundacion_id', 'usuario_id');
    }

    public function donaciones()
    {
        return $this->hasMany(Donacion::class, 'usuario_id', 'usuario_id');
    }

    /**
     * Obtiene la URL completa de la imagen del perfil de la fundación.
     * Estandariza el manejo de imágenes usando Laravel Storage.
     *
     * @return string
     */
    public function getImagenUrlAttribute()
    {
        // Si no hay imagen, devolver imagen por defecto
        if (empty($this->imagen)) {
            return asset('img/defaults/fundacion-default.jpg');
        }

        // Si la imagen ya es una URL completa, la devolvemos directamente
        if (filter_var($this->imagen, FILTER_VALIDATE_URL)) {
            return $this->imagen;
        }

        // Limpiar la ruta de la imagen
        $imagenPath = ltrim($this->imagen, '/');
        
        // Verificar rutas en public/img PRIMERO (donde están las imágenes reales)
        $publicPaths = [
            'img/fundaciones/' . $imagenPath,
            'fundaciones/' . $imagenPath,
            $imagenPath
        ];
        
        foreach ($publicPaths as $path) {
            if (file_exists(public_path($path))) {
                return asset($path);
            }
        }
        
        // Si la imagen está en el storage público de Laravel
        if (\Illuminate\Support\Facades\Storage::disk('public')->exists($imagenPath)) {
            return asset('storage/' . $imagenPath);
        }
        
        // Verificar si la imagen está en fundaciones/ dentro del storage
        if (\Illuminate\Support\Facades\Storage::disk('public')->exists('fundaciones/' . $imagenPath)) {
            return asset('storage/fundaciones/' . $imagenPath);
        }

        // Si no se encuentra la imagen, devolver imagen por defecto
        return asset('img/defaults/fundacion-default.jpg');
    }
}
