<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SolicitudAdopcion extends Model
{
    use HasFactory;

    protected $table = 'solicitudes_adopcion';

    protected $fillable = [
        'usuario_id',
        'animal_id',
        'fundacion_id',
        'nombre_solicitante',
        'email_solicitante',
        'telefono_solicitante',
        'identificacion',
        'edad_solicitante',
        'ocupacion',
        'barrio',
        'direccion_solicitante',
        'tipo_vivienda',
        'tiene_patio',
        'otros_mascotas',
        'experiencia_mascotas',
        'motivo_adopcion',
        'tiempo_disponible',
        'bienestar_mascota',
        'conocimiento_cuidados',
        'compromiso_esterilizacion',
        'preguntas_seguridad',
        'referencia',
        'mensaje',
        'estado',
    ];

    protected $casts = [
        'edad_solicitante' => 'integer',
        'tiene_patio' => 'boolean',
        'otros_mascotas' => 'boolean',
        'preguntas_seguridad' => 'array',
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id')->withDefault([
            'nombre' => 'Usuario no registrado',
            'email' => 'N/A'
        ]);
    }

    public function animal()
    {
        return $this->belongsTo(Animal::class, 'animal_id');
    }

    public function fundacion()
    {
        return $this->belongsTo(PerfilFundacion::class, 'fundacion_id');
    }

    public function getEstadoColorAttribute()
    {
        return match($this->estado) {
            'pendiente' => 'yellow',
            'en_revision' => 'blue',
            'aprobada' => 'green',
            'rechazada' => 'red',
            default => 'gray'
        };
    }

    public function getEstadoTextoAttribute()
    {
        return match($this->estado) {
            'pendiente' => 'Pendiente',
            'en_revision' => 'En Revisión',
            'aprobada' => 'Aprobada',
            'rechazada' => 'Rechazada',
            default => 'Desconocido'
        };
    }
}
