<?php

namespace App\Http\Controllers\Fundacion;

use App\Http\Controllers\Controller;
use App\Models\Noticia;
use App\Models\PerfilFundacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Intervention\Image\Laravel\Facades\Image;
use Illuminate\Support\Facades\Storage;

class NoticiaController extends Controller
{
    public function __construct()
    {
        // El middleware 'auth' y 'role:fundacion' ya se aplican en las rutas
    }

    public function index()
    {
        // Obtener el perfil de la fundación del usuario autenticado
        $perfilFundacion = PerfilFundacion::where('usuario_id', Auth::id())->firstOrFail();
        
        // Obtener las noticias de la fundación
        $noticias = Noticia::where('fundacion_id', $perfilFundacion->id)
            ->latest('fecha_publicacion')
            ->paginate(10);

        return view('fundacion.noticias.index', compact('noticias'));
    }

    public function create()
    {
        return view('fundacion.noticias.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'contenido' => 'required|string',
            'resumen' => 'nullable|string|max:500',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'publicada' => 'boolean',
            'fecha_publicacion' => 'nullable|date|after_or_equal:now',
        ]);

        // Obtener el perfil de la fundación del usuario autenticado
        $perfilFundacion = PerfilFundacion::where('usuario_id', Auth::id())->firstOrFail();

        // Crear la noticia con los datos validados
        $noticiaData = [
            'titulo' => $validated['titulo'],
            'contenido' => $validated['contenido'],
            'resumen' => $validated['resumen'] ?? Str::limit(strip_tags($validated['contenido']), 200),
            'publicada' => (bool)($validated['publicada'] ?? false),
            'fecha_publicacion' => $validated['fecha_publicacion'] ?? now(),
            'fundacion_id' => $perfilFundacion->id,
        ];

        // Procesar la imagen si se subió
        $imagenNombre = null;
        if ($request->hasFile('imagen')) {
            $imagen = $request->file('imagen');
            $imagenNombre = time() . '_' . Str::slug(pathinfo($imagen->getClientOriginalName(), PATHINFO_FILENAME)) . '.webp';
            
            // Crear directorio si no existe
            $directorio = public_path('img/noticias');
            if (!file_exists($directorio)) {
                if (!mkdir($directorio, 0755, true) && !is_dir($directorio)) {
                    return redirect()->back()
                        ->with('error', 'No se pudo crear el directorio para guardar la imagen.');
                }
            }
            
            try {
                // Crear una instancia de la imagen usando la nueva API v4
                $imagenProcesada = Image::read($imagen);
                
                // Redimensionar manteniendo la relación de aspecto
                $imagenProcesada->resize(1200, 630);
                
                // Recortar al tamaño exacto si es necesario
                $imagenProcesada->cover(1200, 630);
                
                // Guardar la imagen procesada en formato WebP
                $imagenProcesada->toWebp(85)->save($directorio . '/' . $imagenNombre);
                
            } catch (\Exception $e) {
                \Log::error('Error al procesar la imagen: ' . $e->getMessage());
                return redirect()->back()
                    ->with('error', 'Ocurrió un error al procesar la imagen. Por favor, inténtalo de nuevo.');
            }
        }

        // Agregar la imagen solo si se subió
        if ($imagenNombre) {
            $noticiaData['imagen'] = $imagenNombre;
        }
        
        // Crear la noticia
        $noticia = Noticia::create($noticiaData);
        
        // Redirigir con mensaje de éxito
        return redirect()->route('fundacion.noticias.index')
            ->with('success', 'Noticia creada exitosamente.');
    }

    public function edit(Noticia $noticia)
    {
        $this->authorize('update', $noticia);
        return view('fundacion.noticias.edit', compact('noticia'));
    }

    public function update(Request $request, Noticia $noticia)
    {
        $this->authorize('update', $noticia);

        $request->validate([
            'titulo' => 'required|string|max:255',
            'contenido' => 'required|string',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'publicada' => 'boolean',
            'fecha_publicacion' => 'nullable|date',
        ]);

        // Obtener el perfil de la fundación del usuario autenticado
        $perfilFundacion = PerfilFundacion::where('usuario_id', Auth::id())->firstOrFail();

        // Procesar la imagen si se subió
        $imagenNombre = null;
        if ($request->hasFile('imagen')) {
            $imagen = $request->file('imagen');
            $imagenNombre = time() . '_' . Str::slug(pathinfo($imagen->getClientOriginalName(), PATHINFO_FILENAME)) . '.webp';
            
            // Crear directorio si no existe
            $directorio = public_path('img/noticias');
            if (!file_exists($directorio)) {
                if (!mkdir($directorio, 0755, true) && !is_dir($directorio)) {
                    return redirect()->back()
                        ->with('error', 'No se pudo crear el directorio para guardar la imagen.');
                }
            }
            
            try {
                // Crear una instancia de la imagen usando la nueva API v4
                $imagenProcesada = Image::read($imagen);
                
                // Redimensionar y recortar al tamaño exacto
                $imagenProcesada->cover(1200, 630);
                
                // Guardar la imagen procesada en formato WebP
                $imagenProcesada->toWebp(85)->save($directorio . '/' . $imagenNombre);
                
            } catch (\Exception $e) {
                \Log::error('Error al procesar la imagen: ' . $e->getMessage());
                return redirect()->back()
                    ->with('error', 'Ocurrió un error al procesar la imagen. Por favor, inténtalo de nuevo.');
            }
        }

        // Actualizar la noticia
        $noticia->titulo = $request->titulo;
        $noticia->contenido = $request->contenido;
        $noticia->publicada = $request->has('publicada');
        $noticia->fecha_publicacion = $request->fecha_publicacion;

        // Agregar la imagen solo si se subió
        if ($imagenNombre) {
            // Eliminar la imagen anterior si existe
            if ($noticia->imagen && file_exists(public_path('img/noticias/' . $noticia->imagen))) {
                @unlink(public_path('img/noticias/' . $noticia->imagen));
            }
            $noticia->imagen = $imagenNombre;
        }

        $noticia->save();

        return redirect()->route('fundacion.noticias.index')
            ->with('success', 'Noticia actualizada exitosamente.');
    }

    public function destroy(Noticia $noticia)
    {
        $this->authorize('delete', $noticia);
        
        // Eliminar la imagen si existe
        if ($noticia->imagen && file_exists(public_path('img/noticias/' . $noticia->imagen))) {
            unlink(public_path('img/noticias/' . $noticia->imagen));
        }
        
        $noticia->delete();

        return redirect()->route('fundacion.noticias.index')
            ->with('success', 'Noticia eliminada exitosamente.');
    }
}
