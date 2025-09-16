<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use App\Models\PerfilFundacion;
use App\Models\Animal;
use App\Models\Donacion;
use App\Models\SolicitudAdopcion;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class FundacionController extends Controller
{
    /**
     * Muestra el perfil de la fundación del usuario autenticado
     */
    public function perfil()
    {
        $fundacion = PerfilFundacion::firstOrCreate(
            ['usuario_id' => Auth::id()],
            [
                'nombre' => 'Fundación ' . Auth::user()->name,
                'descripcion' => 'Descripción de la fundación',
                'direccion' => 'Dirección no especificada',
                'telefono' => 'Sin teléfono',
                'email' => Auth::user()->email,
            ]
        );
        
        return view('fundacion.perfil.perfil', compact('fundacion'));
    }

    /**
     * Actualiza el perfil de la fundación
     */
    public function actualizarPerfil(Request $request)
    {
        $request->validate([
            // Información básica
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'direccion' => 'required|string',
            'telefono' => 'required|string',
            'email' => 'required|email',
            'sitio_web' => 'nullable|url',
            'facebook' => 'nullable|url',
            'instagram' => 'nullable|url',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            
            // Información bancaria
            'banco_nombre' => 'required|string|max:255',
            'tipo_cuenta' => 'required|in:ahorros,corriente',
            'numero_cuenta' => 'required|string|max:50',
            'nombre_titular' => 'required|string|max:255',
            'identificacion_titular' => 'required|string|max:50',
            'email_contacto_pagos' => 'nullable|email',
            'otros_metodos_pago' => 'nullable|string',
            
            // Ubicación
            'latitud' => 'required|numeric',
            'longitud' => 'required|numeric',
        ]);

        try {
            DB::transaction(function () use ($request) {
                $data = $request->only([
                    'nombre', 'descripcion', 'direccion', 
                    'telefono', 'email', 'sitio_web', 
                    'facebook', 'instagram', 'latitud', 'longitud',
                    // Campos bancarios
                    'banco_nombre', 'tipo_cuenta', 'numero_cuenta',
                    'nombre_titular', 'identificacion_titular',
                    'email_contacto_pagos', 'otros_metodos_pago'
                ]);
                
                // Limpiar URLs de redes sociales si están vacías
                $data['facebook'] = $data['facebook'] ? 'https://facebook.com/' . ltrim(parse_url($data['facebook'], PHP_URL_PATH), '/') : null;
                $data['instagram'] = $data['instagram'] ? 'https://instagram.com/' . ltrim(parse_url($data['instagram'], PHP_URL_PATH), '/') : null;
                
                $fundacion = PerfilFundacion::updateOrCreate(
                    ['usuario_id' => Auth::id()],
                    $data
                );

                if ($request->hasFile('imagen')) {
                    $this->actualizarImagenPerfil($fundacion, $request->file('imagen'));
                }
            });

            return redirect()->route('fundacion.perfil')
                ->with('success', 'Perfil actualizado exitosamente');
                
        } catch (\Exception $e) {
            Log::error('Error al actualizar perfil: ' . $e->getMessage() . ' - ' . $e->getTraceAsString());
            return back()->with('error', 'Error al actualizar el perfil. Por favor, intente nuevamente.');
        }
    }

    /**
     * Muestra el formulario para editar el perfil
     */
    public function editarPerfil()
    {
        $fundacion = PerfilFundacion::firstOrCreate(
            ['usuario_id' => Auth::id()],
            [
                'nombre' => 'Fundación ' . Auth::user()->name,
                'descripcion' => 'Descripción de la fundación',
                'direccion' => 'Dirección no especificada',
                'telefono' => 'Sin teléfono',
                'email' => Auth::user()->email,
            ]
        );
        
        return view('fundacion.perfil.perfil-editar', compact('fundacion'));
    }

    /**
     * Muestra el listado de animales de la fundación
     */
    public function animales(Request $request)
    {
        $fundacion = PerfilFundacion::where('usuario_id', Auth::id())->firstOrFail();
        
        $animales = Animal::where('fundacion_id', $fundacion->id)
            ->when($request->has('estado'), function ($query) use ($request) {
                $query->where('estado', $request->estado);
            })
            ->withCount('solicitudesAdopcion')
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();
                         
        return view('fundacion.animales.index', compact('animales', 'fundacion'));
    }

    /**
     * Muestra el formulario para crear un nuevo animal
     */
    public function crearAnimal()
    {
        return view('fundacion.animales.create');
    }

    /**
     * Almacena un nuevo animal en la base de datos
     */
    public function guardarAnimal(Request $request)
    {
        // Enable query logging
        \DB::enableQueryLog();
        
        // Log the request data (excluding file content)
        Log::info('Guardar animal - Datos recibidos:', $request->except(['imagen']));

        // Validar los campos del formulario
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'tipo' => 'required|string',
            'edad' => 'required|integer|min:0',
            'tipo_edad' => 'required|in:meses,años,anios',
            'sexo' => 'required|string|in:macho,hembra',
            'descripcion' => 'required|string',
            'imagen' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'latitud' => 'required|numeric',
            'longitud' => 'required|numeric',
            'direccion' => 'required|string|max:500'
        ]);

        try {
            return DB::transaction(function () use ($request) {
                // Obtener la fundación del usuario autenticado
                $fundacion = PerfilFundacion::where('usuario_id', Auth::id())->firstOrFail();
                
                // Log fundación encontrada
                Log::info('Fundación encontrada:', ['id' => $fundacion->id, 'usuario_id' => $fundacion->usuario_id]);
                
                // Crear una nueva instancia de Animal con los datos del formulario
                $animal = new Animal();
                $animal->nombre = $request->nombre;
                $animal->tipo = $request->tipo;
                $animal->edad = $request->edad;
                $animal->tipo_edad = $request->tipo_edad;
                $animal->sexo = $request->sexo;
                $animal->descripcion = $request->descripcion;
                $animal->latitud = $request->latitud;
                $animal->longitud = $request->longitud;
                $animal->direccion = $request->direccion;
                $animal->fundacion_id = $fundacion->id;
                $animal->estado = 'disponible';
                $animal->fecha_ingreso = now();

                // Log datos del animal antes de guardar
                Log::info('Datos del animal antes de guardar:', $animal->toArray());

                // Manejar la carga de la imagen
                if ($request->hasFile('imagen')) {
                    try {
                        $imagenPath = $this->guardarImagen($request->file('imagen'), 'animales');
                        $animal->imagen = $imagenPath;
                        Log::info('Imagen guardada', ['ruta' => $imagenPath]);
                    } catch (\Exception $e) {
                        Log::error('Error al guardar la imagen: ' . $e->getMessage());
                        throw new \Exception('Error al procesar la imagen del animal');
                    }
                }

                // Guardar el animal en la base de datos
                $animal->save();
                
                // Log the successful save
                Log::info('Animal guardado exitosamente', ['id' => $animal->id]);
                
                // Log all executed queries
                $queries = \DB::getQueryLog();
                Log::info('Queries ejecutadas', ['queries' => $queries]);

                return redirect()->route('fundacion.animales')
                    ->with('success', 'Animal agregado exitosamente');
            });
            
        } catch (\Exception $e) {
            // Get all executed queries before the error
            $queries = \DB::getQueryLog();
            
            // Get the actual SQL query that caused the error
            $sql = '';
            $bindings = [];
            
            if ($e instanceof \Illuminate\Database\QueryException) {
                $sql = $e->getSql();
                $bindings = $e->getBindings();
                
                // Replace the ? placeholders with the actual values for better debugging
                $fullSql = $sql;
                foreach ($bindings as $binding) {
                    $value = is_numeric($binding) ? $binding : "'" . $binding . "'";
                    $fullSql = preg_replace('/\?/', $value, $fullSql, 1);
                }
            }
            
            // Log the full error details
            Log::error('Error al guardar animal:', [
                'exception' => get_class($e),
                'message' => $e->getMessage(),
                'sql' => $sql,
                'bindings' => $bindings,
                'full_sql' => $fullSql ?? 'N/A',
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'input' => $request->except(['imagen']), // Exclude image data from logs
                'previous' => $e->getPrevious() ? [
                    'message' => $e->getPrevious()->getMessage(),
                    'file' => $e->getPrevious()->getFile(),
                    'line' => $e->getPrevious()->getLine(),
                ] : null,
                'queries' => $queries
            ]);
            
            $errorMessage = 'Error al guardar el animal';
            
            // Provide more specific error messages based on the exception type
            if ($e instanceof \Illuminate\Database\QueryException) {
                $errorMessage = 'Error en la base de datos: ' . $e->getMessage();
                
                // Check for specific SQL errors
                if (str_contains($e->getMessage(), 'Integrity constraint violation')) {
                    if (str_contains($e->getMessage(), 'foreign key constraint fails')) {
                        $errorMessage = 'Error de integridad de datos. Verifica que la fundación exista y que todos los campos requeridos sean correctos. Detalles: ' . 
                                      ($fullSql ?? 'consulta no disponible');
                    } else if (str_contains($e->getMessage(), 'Duplicate entry')) {
                        $errorMessage = 'Error: Ya existe un registro con estos datos. Por favor, verifica la información e inténtalo de nuevo.';
                    } else {
                        $errorMessage = 'Error de integridad de datos. Por favor, verifica la información e inténtalo de nuevo. Detalles: ' . 
                                      ($fullSql ?? 'consulta no disponible');
                    }
                }
            } elseif ($e instanceof \ErrorException) {
                $errorMessage = 'Error en el servidor: ' . $e->getMessage();
            }
            
            return back()->withInput()->with('error', $errorMessage);
        } finally {
            // Disable query logging
            \DB::disableQueryLog();
        }
    }

    /**
     * Muestra el listado de donaciones recibidas
     */
    public function donaciones()
    {
        // Obtener la fundación del usuario autenticado
        $fundacion = PerfilFundacion::where('usuario_id', Auth::id())->firstOrFail();
        
        $donaciones = Donacion::where('fundacion_id', $fundacion->id)
            ->with('usuario')
            ->latest()
            ->paginate(10);
            
        return view('fundacion.donaciones.index', compact('donaciones'));
    }

    /**
     * Muestra las solicitudes de adopción
     */
    public function solicitudesAdopcion()
    {
        // Obtener la fundación del usuario autenticado
        $fundacion = PerfilFundacion::where('usuario_id', Auth::id())->firstOrFail();
        
        $solicitudes = SolicitudAdopcion::where('fundacion_id', $fundacion->id)
            ->with(['usuario', 'animal'])
            ->latest()
            ->paginate(10);
            
        return view('fundacion.solicitudes.index', compact('solicitudes'));
    }

    /**
     * Actualiza el estado de una solicitud de adopción
     */
    public function actualizarSolicitud(Request $request, $id)
    {
        $request->validate([
            'estado' => 'required|in:pendiente,aceptado,rechazado',
            'respuesta' => 'required_if:estado,aceptado,rechazado|string'
        ]);

        try {
            // Obtener la fundación del usuario autenticado
            $fundacion = PerfilFundacion::where('usuario_id', Auth::id())->firstOrFail();
            
            $solicitud = SolicitudAdopcion::where('id', $id)
                ->where('fundacion_id', $fundacion->id)
                ->firstOrFail();
                
            $solicitud->update($request->only(['estado', 'respuesta']));

            return redirect()->route('fundacion.solicitudes')
                ->with('success', 'Solicitud actualizada');
                
        } catch (\Exception $e) {
            Log::error('Error al actualizar solicitud: ' . $e->getMessage());
            return back()->with('error', 'Error al actualizar la solicitud');
        }
    }
    
    /**
     * Cambia el estado de una solicitud de adopción
     * 
     * @param int $id ID de la solicitud
     * @param string $estado Nuevo estado (pendiente, en_revision, aprobada, rechazada)
     * @return \Illuminate\Http\Response
     */
    public function cambiarEstadoSolicitud($id, $estado)
    {
        try {
            // Validar que el estado sea válido
            if (!in_array($estado, ['pendiente', 'en_revision', 'aprobada', 'rechazada'])) {
                return back()->with('error', 'Estado no válido');
            }
            
            // Obtener la fundación del usuario autenticado
            $fundacion = PerfilFundacion::where('usuario_id', Auth::id())->firstOrFail();
            
            // Buscar la solicitud
            $solicitud = SolicitudAdopcion::where('id', $id)
                ->where('fundacion_id', $fundacion->id)
                ->firstOrFail();
                
            // Actualizar el estado
            $solicitud->estado = $estado;
            $solicitud->save();
            
            // Mensaje de éxito
            $mensajes = [
                'pendiente' => 'Solicitud marcada como pendiente',
                'en_revision' => 'Solicitud puesta en revisión',
                'aprobada' => '¡Solicitud aprobada exitosamente!',
                'rechazada' => 'Solicitud rechazada'
            ];
            
            return back()->with('success', $mensajes[$estado] ?? 'Estado actualizado');
            
        } catch (\Exception $e) {
            Log::error('Error al cambiar estado de solicitud: ' . $e->getMessage());
            return back()->with('error', 'Error al actualizar el estado de la solicitud');
        }
    }

    /**
     * Muestra el detalle de una solicitud
     */
    public function detalleSolicitud($id)
    {
        // Obtener la fundación del usuario autenticado
        $fundacion = PerfilFundacion::where('usuario_id', Auth::id())->firstOrFail();
        
        $solicitud = SolicitudAdopcion::with(['usuario', 'animal', 'fundacion'])
            ->where('fundacion_id', $fundacion->id)
            ->findOrFail($id);
            
        return view('fundacion.solicitudes.detalle', compact('solicitud'));
    }

    /**
     * Marca un animal como adoptado
     */
    public function marcarComoAdoptado($id)
    {
        try {
            // Obtener la fundación del usuario autenticado
            $fundacion = PerfilFundacion::where('usuario_id', Auth::id())->firstOrFail();
            
            $animal = Animal::where('id', $id)
                ->where('fundacion_id', $fundacion->id)
                ->firstOrFail();
                
            $animal->update(['estado' => 'adoptado']);

            return back()->with('success', 'Animal marcado como adoptado');
            
        } catch (\Exception $e) {
            Log::error('Error al marcar como adoptado: ' . $e->getMessage());
            return back()->with('error', 'Error al actualizar el estado');
        }
    }

    /**
     * Muestra el formulario para editar un animal
     */
    public function editarAnimal($id)
    {
        // Obtener la fundación del usuario autenticado
        $fundacion = PerfilFundacion::where('usuario_id', Auth::id())->firstOrFail();
        
        $animal = Animal::where('id', $id)
            ->where('fundacion_id', $fundacion->id)
            ->firstOrFail();
            
        return view('fundacion.animales.edit', compact('animal'));
    }

    /**
     * Actualiza los datos de un animal
     */
    public function actualizarAnimal(Request $request, $id)
    {
        // Habilitar logging de consultas para debugging
        \DB::enableQueryLog();
        
        $request->validate([
            'nombre' => 'required|string|max:255',
            'tipo' => 'required|string',
            'edad' => 'required|integer|min:0',
            'tipo_edad' => 'required|in:meses,años,anios',
            'sexo' => 'required|string',
            'descripcion' => 'required|string',
            'estado' => 'required|in:disponible,adoptado,en_adopcion',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        try {
            return DB::transaction(function () use ($request, $id) {
                // Obtener la fundación del usuario autenticado
                $fundacion = PerfilFundacion::where('usuario_id', Auth::id())->firstOrFail();
                
                Log::info('Actualizando animal', [
                    'animal_id' => $id,
                    'fundacion_id' => $fundacion->id,
                    'user_id' => Auth::id(),
                    'input_data' => $request->except(['imagen', '_token'])
                ]);
                
                $animal = Animal::where('id', $id)
                    ->where('fundacion_id', $fundacion->id)
                    ->firstOrFail();

                // Actualizar datos básicos del animal
                $datosActualizacion = $request->only([
                    'nombre', 'tipo', 'edad', 'tipo_edad', 
                    'sexo', 'descripcion', 'estado',
                    'direccion', 'latitud', 'longitud'
                ]);
                
                $animal->update($datosActualizacion);
                
                Log::info('Datos básicos actualizados', [
                    'animal_id' => $id,
                    'datos_actualizados' => $datosActualizacion
                ]);

                // Manejar imagen si se proporciona
                if ($request->hasFile('imagen')) {
                    try {
                        Log::info('Procesando nueva imagen', [
                            'animal_id' => $id,
                            'imagen_anterior' => $animal->imagen
                        ]);
                        
                        // Eliminar imagen anterior si existe
                        if ($animal->imagen) {
                            try {
                                Storage::disk('public')->delete($animal->imagen);
                                Log::info('Imagen anterior eliminada', ['ruta' => $animal->imagen]);
                            } catch (\Exception $e) {
                                Log::warning('No se pudo eliminar imagen anterior', [
                                    'ruta' => $animal->imagen,
                                    'error' => $e->getMessage()
                                ]);
                            }
                        }
                        
                        // Guardar nueva imagen
                        $rutaImagen = $this->guardarImagen($request->file('imagen'), 'animales');
                        $animal->imagen = $rutaImagen;
                        $animal->save();
                        
                        Log::info('Nueva imagen guardada', [
                            'animal_id' => $id,
                            'ruta_imagen' => $rutaImagen
                        ]);
                        
                    } catch (\Exception $e) {
                        Log::error('Error al procesar imagen durante actualización', [
                            'animal_id' => $id,
                            'error' => $e->getMessage(),
                            'trace' => $e->getTraceAsString()
                        ]);
                        
                        // En caso de error con imagen, continuar con la actualización sin imagen
                        // pero informar al usuario
                        session()->flash('warning', 'El animal se actualizó correctamente, pero hubo un problema con la imagen. Intenta subirla nuevamente.');
                    }
                }

                // Log de consultas ejecutadas
                $queries = \DB::getQueryLog();
                Log::info('Queries ejecutadas en actualización', ['queries' => $queries]);

                return redirect()->route('fundacion.animales')
                    ->with('success', 'Animal actualizado exitosamente');
            });
                
        } catch (\Exception $e) {
            // Get all executed queries before the error
            $queries = \DB::getQueryLog();
            
            // Log detallado del error
            Log::error('Error al actualizar animal:', [
                'exception' => get_class($e),
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'animal_id' => $id,
                'user_id' => Auth::id(),
                'input' => $request->except(['imagen', '_token']),
                'queries' => $queries
            ]);
            
            $errorMessage = 'Error al actualizar el animal';
            
            // Proporcionar mensajes de error más específicos
            if ($e instanceof \Illuminate\Database\QueryException) {
                $errorMessage = 'Error en la base de datos al actualizar: ' . $e->getMessage();
            } elseif ($e instanceof \Illuminate\Database\Eloquent\ModelNotFoundException) {
                $errorMessage = 'No se encontró el animal o no tienes permisos para editarlo';
            } elseif (str_contains($e->getMessage(), 'imagen')) {
                $errorMessage = 'Error al procesar la imagen: ' . $e->getMessage();
            }
            
            return back()->withInput()->with('error', $errorMessage);
        } finally {
            // Deshabilitar logging de consultas
            \DB::disableQueryLog();
        }
    }

    /**
     * Elimina un animal y sus relaciones
     */
    public function eliminarAnimal($id)
    {
        try {
            return DB::transaction(function () use ($id) {
                // Obtener la fundación del usuario autenticado
                $fundacion = PerfilFundacion::where('usuario_id', Auth::id())->firstOrFail();
                
                $animal = Animal::where('id', $id)
                    ->where('fundacion_id', $fundacion->id)
                    ->with(['solicitudesAdopcion', 'donaciones'])
                    ->firstOrFail();

                // Eliminar documentos de solicitudes
                $animal->solicitudesAdopcion->each(function ($solicitud) {
                    if ($solicitud->documentos) {
                        $documentos = json_decode($solicitud->documentos, true);
                        collect($documentos)->each(function ($doc) {
                            Storage::disk('public')->delete($doc['ruta']);
                        });
                    }
                });

                // Eliminar relaciones
                $animal->solicitudesAdopcion()->delete();
                $animal->donaciones()->delete();

                // Eliminar imagen
                if ($animal->imagen) {
                    Storage::disk('public')->delete($animal->imagen);
                }

                $animal->delete();

                return redirect()->route('fundacion.animales')
                    ->with('success', 'Animal eliminado correctamente');
            });
            
        } catch (\Exception $e) {
            Log::error('Error al eliminar animal: ' . $e->getMessage());
            return back()->with('error', 'Error al eliminar el animal');
        }
    }

    /**
     * Muestra el panel de control
     */
    public function panel()
    {
        $fundacion = PerfilFundacion::where('usuario_id', Auth::id())->first();
        
        // Check if the fundación exists
        if (!$fundacion) {
            // Redirect to profile setup or show an error
            return redirect()->route('fundacion.perfil.editar')
                ->with('error', 'Por favor complete el perfil de su fundación antes de continuar.');
        }
        
        $estadisticas = [
            'animales' => Animal::where('fundacion_id', $fundacion->id)->count(),
            'disponibles' => Animal::where('fundacion_id', $fundacion->id)
                ->where('estado', 'disponible')->count(),
            'solicitudes' => SolicitudAdopcion::where('fundacion_id', $fundacion->id)->count(),
            'donaciones' => Donacion::where('fundacion_id', $fundacion->id)->sum('monto')
        ];
        
        return view('fundacion.panel', compact('estadisticas', 'fundacion'));
    }

    /**
     * Muestra el listado público de fundaciones
     */
    public function publico()
    {
        $fundaciones = PerfilFundacion::with('usuario')
            ->withCount([
                'animales' => fn($query) => $query->where('estado', 'disponible'),
                'donaciones'
            ])
            ->paginate(6);
            
        return view('fundacion.publico', compact('fundaciones'));
    }

    /**
     * Guarda una imagen en el almacenamiento
     */
    protected function guardarImagen($imagen, $directorio)
    {
        try {
            // Verificar que el archivo sea válido
            if (!$imagen || !$imagen->isValid()) {
                throw new \Exception('El archivo de imagen no es válido');
            }
            
            // Verificar el tamaño del archivo (máximo 2MB)
            if ($imagen->getSize() > 2048 * 1024) {
                throw new \Exception('La imagen es demasiado grande. Máximo 2MB permitido');
            }
            
            // Verificar el tipo MIME
            $tiposPermitidos = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
            if (!in_array($imagen->getMimeType(), $tiposPermitidos)) {
                throw new \Exception('Tipo de archivo no permitido. Solo se permiten: JPEG, PNG, JPG, GIF');
            }
            
            Log::info('Iniciando guardado de imagen', [
                'directorio' => $directorio,
                'nombre_original' => $imagen->getClientOriginalName(),
                'tamaño' => $imagen->getSize(),
                'tipo_mime' => $imagen->getMimeType()
            ]);
            
            // Crear el directorio si no existe
            $rutaDirectorio = storage_path('app/public/' . $directorio);
            if (!file_exists($rutaDirectorio)) {
                Log::info('Creando directorio', ['ruta' => $rutaDirectorio]);
                if (!mkdir($rutaDirectorio, 0755, true)) {
                    throw new \Exception('No se pudo crear el directorio para guardar la imagen: ' . $rutaDirectorio);
                }
            }
            
            // Verificar permisos de escritura
            if (!is_writable($rutaDirectorio)) {
                throw new \Exception('No hay permisos de escritura en el directorio: ' . $rutaDirectorio);
            }
            
            // Generar un nombre único para la imagen
            $extension = $imagen->getClientOriginalExtension();
            $nombreArchivo = uniqid() . '_' . time() . '.' . $extension;
            
            Log::info('Guardando imagen', [
                'nombre_archivo' => $nombreArchivo,
                'ruta_completa' => $rutaDirectorio . '/' . $nombreArchivo
            ]);
            
            // Guardar la imagen en el almacenamiento
            $ruta = $imagen->storeAs($directorio, $nombreArchivo, 'public');
            
            if (!$ruta) {
                throw new \Exception('Error al guardar la imagen en el almacenamiento');
            }
            
            // Verificar que el archivo se guardó correctamente
            $rutaCompleta = storage_path('app/public/' . $ruta);
            if (!file_exists($rutaCompleta)) {
                throw new \Exception('La imagen no se guardó correctamente en: ' . $rutaCompleta);
            }
            
            Log::info('Imagen guardada exitosamente', [
                'ruta_relativa' => $ruta,
                'ruta_completa' => $rutaCompleta,
                'tamaño_final' => filesize($rutaCompleta)
            ]);
            
            return $ruta;
            
        } catch (\Exception $e) {
            Log::error('Error en guardarImagen: ' . $e->getMessage(), [
                'directorio' => $directorio,
                'archivo_original' => $imagen ? $imagen->getClientOriginalName() : 'null',
                'trace' => $e->getTraceAsString()
            ]);
            throw new \Exception('Error al procesar la imagen: ' . $e->getMessage());
        }
    }

    /**
     * Actualiza la imagen del perfil
     */
    protected function actualizarImagenPerfil($fundacion, $imagen)
    {
        // Eliminar la imagen anterior si existe
        if ($fundacion->imagen) {
            $rutaImagenAnterior = str_replace('storage/', '', $fundacion->imagen);
            if (Storage::disk('public')->exists($rutaImagenAnterior)) {
                Storage::disk('public')->delete($rutaImagenAnterior);
            }
        }
        
        // Guardar la nueva imagen en public/img/fundaciones
        $nombreArchivo = 'fundacion_' . time() . '.' . $imagen->getClientOriginalExtension();
        $rutaCarpeta = public_path('img/fundaciones');
        
        // Crear el directorio si no existe
        if (!File::exists($rutaCarpeta)) {
            File::makeDirectory($rutaCarpeta, 0755, true, true);
        }
        
        // Mover la imagen a la carpeta de destino
        $imagen->move($rutaCarpeta, $nombreArchivo);
        
        // Guardar la ruta relativa en la base de datos
        $rutaRelativa = 'img/fundaciones/' . $nombreArchivo;
        $fundacion->imagen = $rutaRelativa;
        $fundacion->save();
    }
}