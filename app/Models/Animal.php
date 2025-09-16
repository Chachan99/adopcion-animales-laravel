<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;

/**
 * Modelo que representa un animal en el sistema de adopción.
 * 
 * @property int $id
 * @property string $nombre
 * @property string $tipo
 * @property string $raza
 * @property int $edad
 * @property string $tipo_edad
 * @property string $sexo
 * @property string $descripcion
 * @property string $imagen
 * @property int $fundacion_id
 * @property string $estado
 * @property string|null $latitud
 * @property string|null $longitud
 * @property string|null $direccion
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\PerfilFundacion $fundacion
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Donacion[] $donaciones
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\SolicitudAdopcion[] $solicitudesAdopcion
 */
class Animal extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'animales';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'fundacion_id',
        'nombre',
        'tipo',
        'raza',
        'edad',
        'tipo_edad',
        'sexo',
        'descripcion',
        'imagen',
        'estado',
        'tipo_publicacion',
        'telefono_contacto',
        'email_contacto',
        'recompensa',
        'ultima_ubicacion',
        'latitud',
        'longitud',
        'direccion'
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['imagen_url'];



    /**
     * Obtiene la fundación a la que pertenece el animal.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function fundacion()
    {
        return $this->belongsTo(PerfilFundacion::class, 'fundacion_id');
    }

    /**
     * Obtiene las donaciones asociadas al animal.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function donaciones()
    {
        return $this->hasMany(Donacion::class, 'animal_id');
    }

    /**
     * Obtiene las solicitudes de adopción del animal.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function solicitudesAdopcion()
    {
        return $this->hasMany(SolicitudAdopcion::class, 'animal_id');
    }

    /**
     * Obtiene la URL completa de la imagen del animal.
     * Estandariza el manejo de imágenes usando Laravel Storage.
     *
     * @return string
     */
    public function getImagenUrlAttribute()
    {
        // Si no hay imagen, devolver imagen por defecto
        if (empty($this->imagen)) {
            return asset('img/defaults/animal-default.jpg');
        }

        // Si la imagen ya es una URL completa, la devolvemos directamente
        if (filter_var($this->imagen, FILTER_VALIDATE_URL)) {
            return $this->imagen;
        }

        // Limpiar la ruta de la imagen
        $imagenPath = ltrim($this->imagen, '/');
        
        // Si estamos usando S3, generar URL directamente
        if (config('filesystems.default') === 's3') {
            // Verificar si la imagen existe en S3
            if (Storage::disk('public')->exists($imagenPath)) {
                return Storage::disk('public')->url($imagenPath);
            }
            
            // Verificar si la imagen está en animales/ dentro del storage
            if (Storage::disk('public')->exists('animales/' . $imagenPath)) {
                return Storage::disk('public')->url('animales/' . $imagenPath);
            }
        } else {
            // Para almacenamiento local, verificar rutas en public/img PRIMERO
            $publicPaths = [
                'img/animales/' . $imagenPath,
                'img/test/' . $imagenPath,
                'test/' . $imagenPath,
                $imagenPath
            ];
            
            foreach ($publicPaths as $path) {
                if (file_exists(public_path($path))) {
                    return asset($path);
                }
            }
            
            // Si la imagen está en el storage público de Laravel
            if (Storage::disk('public')->exists($imagenPath)) {
                return asset('storage/' . $imagenPath);
            }
            
            // Verificar si la imagen está en animales/ dentro del storage
            if (Storage::disk('public')->exists('animales/' . $imagenPath)) {
                return asset('storage/animales/' . $imagenPath);
            }
        }

        // Si no se encuentra la imagen, devolver imagen por defecto
        return asset('img/defaults/animal-default.jpg');
    }
}
