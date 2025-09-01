<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Donacion extends Model
{
    use HasFactory;

    protected $table = 'donaciones';


    protected $fillable = [
        'usuario_id',
        'animal_id',
        'fundacion_id',
        'monto',
        'metodo',
        'descripcion',
    ];


    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    public function fundacion()
    {
        // Asumiendo que fundacion_id es el usuario_id de la fundación
        return $this->belongsTo(Usuario::class, 'fundacion_id');
    }

    public function animal()
    {
        return $this->belongsTo(Animal::class, 'animal_id');
    }
}
