<?php

namespace App\Http\Controllers;

use App\Models\Animal;
use App\Models\PerfilFundacion;
use App\Models\Donacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PublicoController extends Controller
{
    public function index()
    {
        // Obtener animales disponibles con su fundación relacionada
        $animales = Animal::with('fundacion')
            ->where('estado', 'disponible')
            ->orderBy('created_at', 'desc')
            ->paginate(9);
            
        // Obtener fundaciones con conteo de animales disponibles
        $fundaciones = PerfilFundacion::with(['usuario', 'animales' => function($query) {
                $query->where('estado', 'disponible');
            }])
            ->withCount(['animales' => function($query) {
                $query->where('estado', 'disponible');
            }])
            ->orderBy('created_at', 'desc')
            ->paginate(6);
            
        return view('publico.index', compact('animales', 'fundaciones'));
    }
            
    /**
     * Muestra un listado de todos los animales disponibles para adopción
     */
    public function animales()
    {
        $animales = Animal::with('fundacion')
            ->where('estado', 'disponible')
            ->orderBy('created_at', 'desc')
            ->paginate(12);
            
        return view('publico.animales', compact('animales'));
    }

    public function animal($id)
    {
        $animal = Animal::findOrFail($id);
        return view('publico.animal', compact('animal'));
    }

    public function fundacion($id)
    {
        $fundacion = PerfilFundacion::with('usuario')->findOrFail($id);
        $animales = Animal::where('fundacion_id', $fundacion->usuario_id)
            ->where('estado', 'disponible')
            ->paginate(6);
        return view('publico.fundacion', compact('fundacion', 'animales'));
    }

    public function donar(Request $request)
    {
        $request->validate([
            'fundacion_id' => 'required|exists:usuarios,id',
            'monto' => 'required|numeric|min:1',
            'metodo' => 'required|string',
            'descripcion' => 'nullable|string'
        ]);

        $donacion = new Donacion();
        $donacion->fundacion_id = $request->fundacion_id;
        $donacion->monto = $request->monto;
        $donacion->metodo = $request->metodo;
        $donacion->descripcion = $request->descripcion;
        $donacion->usuario_id = Auth::id();
        $donacion->save();

        return redirect()->route('publico.index')->with('success', 'Donación registrada exitosamente');
    }

    public function buscar(Request $request)
    {
        $query = Animal::with('fundacion')
            ->where('estado', 'disponible');

        // Filtros de búsqueda
        if ($request->tipo) {
            $query->where('tipo', $request->tipo);
        }

        if ($request->edad_min) {
            $query->where('edad', '>=', $request->edad_min);
        }

        if ($request->edad_max) {
            $query->where('edad', '<=', $request->edad_max);
        }

        // Ordenar por fecha de creación (más recientes primero)
        $animales = $query->orderBy('created_at', 'desc')
            ->paginate(12)
            ->withQueryString(); // Mantener los parámetros de búsqueda en la paginación
            
        return view('publico.buscar', compact('animales'));
    }

    public function fundaciones(Request $request)
    {
        $query = PerfilFundacion::with('usuario')
            ->withCount(['animales' => function($query) {
                $query->where('estado', 'disponible');
            }, 'donaciones']);

        // Filtrar por nombre
        if ($request->nombre) {
            $query->where('nombre', 'like', '%' . $request->nombre . '%');
        }

        // Filtrar por ubicación
        if ($request->ubicacion) {
            $query->where('ubicacion', 'like', '%' . $request->ubicacion . '%');
        }

        $fundaciones = $query->paginate(6);
        return view('publico.fundaciones', compact('fundaciones'));
    }

    public function donaciones()
    {
        // Log all fundaciones for debugging
        $allFundaciones = PerfilFundacion::with('usuario')->get();
        \Log::info('Todas las fundaciones:', $allFundaciones->toArray());
        
        // Get fundaciones with either banco_nombre or nombre_titular filled
        $fundaciones = PerfilFundacion::with('usuario')
            ->where(function($query) {
                $query->where(function($q) {
                    $q->whereNotNull('banco_nombre')
                      ->where('banco_nombre', '!=', '');
                })->orWhere(function($q) {
                    $q->whereNotNull('nombre_titular')
                      ->where('nombre_titular', '!=', '');
                });
            })
            ->get()
            ->filter(function($fundacion) {
                // Ensure at least banco_nombre or nombre_titular is not empty
                return !empty($fundacion->banco_nombre) || !empty($fundacion->nombre_titular);
            });
        
        // Log the filtered results
        \Log::info('Fundaciones con información bancaria:', $fundaciones->toArray());
        
        // For debugging, show all fundaciones
        $fundaciones = $allFundaciones;
            
        return view('publico.donaciones', compact('fundaciones'));
    }
    
    /**
     * Muestra información para adoptantes primerizos
     */
    public function informacionAdopcion()
    {
        return view('publico.informacion-adopcion');
    }

    public function informate()
    {
        return view('publico.informate');
    }
    
    public function noticias()
    {
        $noticias = [
            [
                'titulo' => 'Rescate de 15 perros en situación de calle',
                'imagen' => asset('img/noticias/rescate-perros.jpg'),
                'fecha' => '01/08/2025',
                'resumen' => 'Nuestro equipo rescató a 15 perros que vivían en condiciones precarias en el sector sur de la ciudad.'
            ],
            [
                'titulo' => 'Campaña de esterilización masiva',
                'imagen' => asset('img/noticias/esterilizacion.jpg'),
                'fecha' => '25/07/2025',
                'resumen' => 'Realizamos más de 100 esterilizaciones en nuestra última jornada de salud animal.'
            ],
            [
                'titulo' => 'Éxito en adopciones del mes',
                'imagen' => asset('img/noticias/adopciones.jpg'),
                'fecha' => '15/07/2025',
                'resumen' => 'Este mes logramos que 25 animales encontraran un hogar amoroso gracias a su adopción responsable.'
            ]
        ];
        
        return view('publico.noticias', compact('noticias'));
    }

    /**
     * Muestra el formulario de contacto
     *
     * @return \Illuminate\View\View
     */
    public function contacto()
    {
        return view('publico.contacto');
    }

    /**
     * Procesa el envío del formulario de contacto
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function contactar(Request $request)
    {
        // Validar los datos del formulario
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'message' => 'required|string',
            'animal_id' => 'nullable|exists:animals,id'
        ]);

        try {
            // Aquí puedes implementar el envío de correo electrónico
            // Por ejemplo, usando Mail::to() con una vista de correo
            
            // O guardar el mensaje en la base de datos si es necesario
            // $contacto = new Contacto($validated);
            // $contacto->save();

            return response()->json([
                'success' => true,
                'message' => '¡Gracias por contactarnos! Te responderemos lo antes posible.'
            ]);
        } catch (\Exception $e) {
            \Log::error('Error al procesar el formulario de contacto: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Ocurrió un error al enviar el mensaje. Por favor, inténtalo de nuevo más tarde.'
            ], 500);
        }
    }

    /**
     * Maneja la acción de agregar o quitar un animal de favoritos
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function toggleFavorito(Request $request)
    {
        // Verificar si el usuario está autenticado
        if (!auth()->check()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Debes iniciar sesión para guardar favoritos',
                'login_required' => true
            ], 401);
        }

        $request->validate([
            'animal_id' => 'required|exists:animals,id',
        ]);

        $user = auth()->user();
        $animalId = $request->input('animal_id');
        
        try {
            // Verificar si el animal ya está en favoritos
            $exists = $user->favoritos()->where('animal_id', $animalId)->exists();
            
            if ($exists) {
                // Si ya existe, lo quitamos de favoritos
                $user->favoritos()->detach($animalId);
                $status = 'removed';
                $message = 'Animal eliminado de tus favoritos';
            } else {
                // Si no existe, lo agregamos a favoritos
                $user->favoritos()->attach($animalId);
                $status = 'added';
                $message = 'Animal agregado a tus favoritos';
            }
            
            return response()->json([
                'status' => $status,
                'message' => $message,
                'is_favorite' => $status === 'added'
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error al actualizar favoritos: ' . $e->getMessage());
            
            return response()->json([
                'status' => 'error',
                'message' => 'Ocurrió un error al actualizar tus favoritos. Por favor, inténtalo de nuevo.'
            ], 500);
        }
    }
}
