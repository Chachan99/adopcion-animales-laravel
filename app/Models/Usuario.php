<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\Access\Authorizable;

class Usuario extends Authenticatable
{
    use HasFactory, Notifiable, Authorizable;

    protected $table = 'usuarios';

    protected $fillable = [
        'nombre',
        'email',
        'password',
        'rol',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $appends = ['imagen_url'];

    public function perfilFundacion()
    {
        return $this->hasOne(PerfilFundacion::class, 'usuario_id');
    }

    public function animales()
    {
        return $this->hasMany(Animal::class, 'usuario_id');
    }


    public function donaciones()
    {
        return $this->hasMany(Donacion::class, 'usuario_id');
    }

    // Donaciones recibidas como fundación
    public function donacionesRecibidas()
    {
        return $this->hasMany(Donacion::class, 'fundacion_id');
    }

    public function solicitudesAdopcion()
    {
        return $this->hasMany(SolicitudAdopcion::class, 'usuario_id');
    }
    
    /**
     * Obtener las noticias creadas por este usuario.
     */
    public function noticias()
    {
        return $this->hasMany(Noticia::class, 'usuario_id');
    }

    /**
     * Obtener la URL completa de la imagen del usuario.
     */
    public function getImagenUrlAttribute()
    {
        if ($this->imagen) {
            // Si estamos usando S3, generar URL directamente
            if (config('filesystems.default') === 's3') {
                return \Illuminate\Support\Facades\Storage::disk('public')->url('usuarios/' . $this->imagen);
            } else {
                // Para almacenamiento local
                return asset('storage/usuarios/' . $this->imagen);
            }
        }
        return null;
    }
}
