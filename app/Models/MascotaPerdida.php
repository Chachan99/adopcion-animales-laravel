<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MascotaPerdida extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'mascotas_perdidas';

    protected $fillable = [
        'nombre',
        'tipo',
        'raza',
        'edad',
        'tipo_edad',
        'sexo',
        'descripcion',
        'imagen',
        'telefono_contacto',
        'email_contacto',
        'recompensa',
        'ultima_ubicacion',
        'latitud',
        'longitud',
        'direccion',
        'estado',
        'fecha_encontrado',
        'usuario_id',
        'fundacion_id',
    ];

    protected $casts = [
        'fecha_encontrado' => 'datetime',
        'edad' => 'integer',
        'latitud' => 'float',
        'longitud' => 'float',
    ];

    protected $dates = [
        'fecha_encontrado',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    // Relaciones
    public function usuario()
    {
        return $this->belongsTo(Usuario::class);
    }

    public function fundacion()
    {
        return $this->belongsTo(Usuario::class, 'fundacion_id');
    }

    /**
     * Obtiene la URL completa de la imagen de la mascota.
     * Estandariza el manejo de imágenes usando Laravel Storage.
     *
     * @return string
     */
    public function getImagenUrlAttribute()
    {
        // Si no hay imagen, devolver imagen por defecto
        if (empty($this->imagen)) {
            return asset('img/defaults/default-pet.jpg');
        }

        // Si la imagen ya es una URL completa, la devolvemos directamente
        if (filter_var($this->imagen, FILTER_VALIDATE_URL)) {
            return $this->imagen;
        }

        // Limpiar la ruta de la imagen
        $imagenPath = ltrim($this->imagen, '/');
        
        // Si la imagen está en el storage público de Laravel
        if (\Illuminate\Support\Facades\Storage::disk('public')->exists($imagenPath)) {
            return asset('storage/' . $imagenPath);
        }
        
        // Verificar si la imagen está en animales-perdidos/ dentro del storage
        if (\Illuminate\Support\Facades\Storage::disk('public')->exists('animales-perdidos/' . $imagenPath)) {
            return asset('storage/animales-perdidos/' . $imagenPath);
        }
        
        // Verificar rutas legacy en public/img
        $legacyPaths = [
            'img/animales-perdidos/' . $imagenPath,
            'animales-perdidos/' . $imagenPath,
            $imagenPath
        ];
        
        foreach ($legacyPaths as $path) {
            if (file_exists(public_path($path))) {
                return asset($path);
            }
        }

        // Si no se encuentra la imagen, devolver imagen por defecto
        return asset('img/defaults/default-pet.jpg');
    }

    // Scopes
    public function scopePerdidos($query)
    {
        return $query->where('estado', 'perdido');
    }

    public function scopeEncontrados($query)
    {
        return $query->where('estado', 'encontrado');
    }
}
