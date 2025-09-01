<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\PerfilFundacion;
use Illuminate\Support\Facades\Storage;

class ActualizarRutasImagenesFundaciones extends Command
{
    protected $signature = 'fundaciones:actualizar-rutas-imagenes';
    protected $description = 'Actualiza las rutas de las imágenes de las fundaciones al nuevo formato de almacenamiento';

    public function handle()
    {
        $fundaciones = PerfilFundacion::whereNotNull('imagen')->get();
        $actualizadas = 0;

        foreach ($fundaciones as $fundacion) {
            // Verificar si la ruta ya está en el nuevo formato
            if (strpos($fundacion->imagen, 'fundaciones/') === 0) {
                continue;
            }

            // Construir la ruta antigua (en public/fundaciones)
            $rutaAntigua = public_path('fundaciones/' . $fundacion->imagen);
            
            // Verificar si el archivo existe en la ruta antigua
            if (file_exists($rutaAntigua)) {
                // Mover el archivo al almacenamiento de Laravel
                $nuevaRuta = 'fundaciones/' . $fundacion->imagen;
                
                // Copiar el archivo al almacenamiento
                if (Storage::disk('public')->put($nuevaRuta, file_get_contents($rutaAntigua))) {
                    // Actualizar la ruta en la base de datos
                    $fundacion->imagen = $nuevaRuta;
                    $fundacion->save();
                    $actualizadas++;
                    $this->info("Actualizada: " . $fundacion->nombre . " - " . $nuevaRuta);
                } else {
                    $this->error("Error al copiar: " . $fundacion->nombre);
                }
            } else {
                $this->warn("Archivo no encontrado: " . $rutaAntigua);
            }
        }

        $this->info("\nProceso completado. Se actualizaron $actualizadas fundaciones.");
    }
}
