<?php

namespace App\Http\Controllers;

use App\Models\Noticia;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Laravel\Facades\Image;

class NoticiaController extends Controller
{
    /**
     * Display a listing of the public news.
     *
     * @return \Illuminate\View\View
     */
    public function publicIndex()
    {
        // Get featured news (destacadas)
        $featuredNews = Noticia::with('fundacion')
            ->where('publicada', true)
            ->where('destacada', true)
            ->where(function($query) {
                $query->whereNull('fecha_publicacion')
                      ->orWhere('fecha_publicacion', '<=', now());
            })
            ->orderBy('fecha_publicacion', 'desc')
            ->take(3)
            ->get();

        // Get regular news
        $noticias = Noticia::with('fundacion')
            ->where('publicada', true)
            ->where(function($query) {
                $query->whereNull('fecha_publicacion')
                      ->orWhere('fecha_publicacion', '<=', now());
            })
            ->where('destacada', false)
            ->orderBy('fecha_publicacion', 'desc')
            ->paginate(9);

        return view('publico.noticias.index', compact('noticias', 'featuredNews'));
    }

    /**
     * Muestra el formulario para crear una nueva noticia.
     */
    public function create()
    {
        $this->authorize('create', Noticia::class);
        return view('admin.noticias.create');
    }

    /**
     * Almacena una nueva noticia en la base de datos.
     */
    public function store(Request $request)
    {
        $this->authorize('create', Noticia::class);
        
        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'contenido' => 'required|string',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'autor' => 'nullable|string|max:255',
            'destacada' => 'sometimes|boolean',
            'estado' => 'required|in:borrador,publicada,archivada',
            'publicado_en' => 'nullable|date',
            'resumen' => 'nullable|string|max:500',
        ]);
        
        // Procesar la imagen si se subió
        if ($request->hasFile('imagen')) {
            $imagen = $request->file('imagen');
            $nombreImagen = time() . '_' . Str::slug(pathinfo($imagen->getClientOriginalName(), PATHINFO_FILENAME)) . '.jpg';
            
            // Crear directorio si no existe
            if (!Storage::exists('public/noticias')) {
                Storage::makeDirectory('public/noticias');
            }
            
            // Optimizar y convertir a WebP
            $imagenProcesada = Image::read($imagen)
                ->cover(1200, 630); // Tamaño estándar para redes sociales
                
            Storage::put('public/noticias/' . $nombreImagen, $imagenProcesada->toJpeg(80));
            $validated['imagen'] = $nombreImagen;
        }
        
        // Asignar el usuario autenticado como autor si no se proporciona un autor
        if (empty($validated['autor']) && Auth::check()) {
            $validated['autor'] = Auth::user()->name;
        }
        
        // Asignar el usuario actual si está autenticado
        if (Auth::check()) {
            $validated['usuario_id'] = Auth::id();
        }
        
        // Si se está publicando y no hay fecha de publicación, usar la fecha actual
        if ($validated['estado'] === 'publicada' && empty($validated['publicado_en'])) {
            $validated['publicado_en'] = now();
        }
        
        // Crear la noticia
        $noticia = Noticia::create($validated);
        
        return redirect()->route('admin.noticias.edit', $noticia)
            ->with('success', 'Noticia creada exitosamente.');
    }

    /**
     * Display the specified news article.
     *
     * @param  \App\Models\Noticia  $noticia
     * @return \Illuminate\View\View
     */
    public function show(Noticia $noticia)
    {
        // Si la noticia no está publicada, solo pueden verla usuarios autorizados
        if (!$noticia->publicada || ($noticia->fecha_publicacion && $noticia->fecha_publicacion > now())) {
            if (!Auth::check() || !Auth::user()->can('view', $noticia)) {
                abort(404, 'La noticia que buscas no existe o no está disponible.');
            }
        }
        
        // Incrementar el contador de vistas
        $noticia->increment('vistas');
        
        // Cargar la relación con la fundación
        $noticia->load('fundacion');
        
        // Obtener noticias relacionadas (misma fundación)
        $noticiasRelacionadas = Noticia::where('publicada', true)
            ->where('id', '!=', $noticia->id)
            ->where(function($query) use ($noticia) {
                $query->where('fundacion_id', $noticia->fundacion_id);
            })
            ->where(function($query) {
                $query->whereNull('fecha_publicacion')
                      ->orWhere('fecha_publicacion', '<=', now());
            })
            ->orderBy('fecha_publicacion', 'desc')
            ->take(3)
            ->get();
            
        // Si no hay suficientes noticias relacionadas, completar con noticias recientes
        if ($noticiasRelacionadas->count() < 3) {
            $noticiasAdicionales = Noticia::where('publicada', true)
                ->where('id', '!=', $noticia->id)
                ->whereNotIn('id', $noticiasRelacionadas->pluck('id'))
                ->where(function($query) {
                    $query->whereNull('fecha_publicacion')
                          ->orWhere('fecha_publicacion', '<=', now());
                })
                ->orderBy('fecha_publicacion', 'desc')
                ->take(3 - $noticiasRelacionadas->count())
                ->get();
                
            $noticiasRelacionadas = $noticiasRelacionadas->merge($noticiasAdicionales);
        }
            
        // Obtener noticias recientes para la barra lateral
        $noticiasRecientes = Noticia::where('estado', 'publicada')
            ->where('id', '!=', $noticia->id)
            ->where(function($query) use ($noticia) {
                $query->whereNull('publicado_en')
                      ->orWhere('publicado_en', '<=', now());
            })
            ->orderBy('publicado_en', 'desc')
            ->take(5)
            ->get();
            
        return view('publico.noticias.show', compact('noticia', 'noticiasRelacionadas', 'noticiasRecientes'));
    }

    /**
     * Muestra el formulario para editar una noticia.
     */
    public function edit(Noticia $noticia)
    {
        $this->authorize('update', $noticia);
        return view('admin.noticias.edit', compact('noticia'));
    }

    /**
     * Actualiza una noticia existente.
     */
    public function update(Request $request, Noticia $noticia)
    {
        $this->authorize('update', $noticia);
        
        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'contenido' => 'required|string',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'autor' => 'nullable|string|max:255',
            'destacada' => 'sometimes|boolean',
            'estado' => 'required|in:borrador,publicada,archivada',
            'publicado_en' => 'nullable|date',
            'resumen' => 'nullable|string|max:500',
            'eliminar_imagen' => 'sometimes|boolean',
        ]);
        
        // Procesar la imagen si se subió una nueva
        if ($request->hasFile('imagen')) {
            // Eliminar la imagen anterior si existe
            if ($noticia->imagen) {
                Storage::delete('public/noticias/' . $noticia->imagen);
            }
            
            $imagen = $request->file('imagen');
            $nombreImagen = time() . '_' . Str::slug(pathinfo($imagen->getClientOriginalName(), PATHINFO_FILENAME)) . '.jpg';
            
            // Optimizar y convertir a WebP
            $imagenProcesada = Image::read($imagen)
                ->cover(1200, 630);
                
            Storage::put('public/noticias/' . $nombreImagen, $imagenProcesada->toJpeg(80));
            $validated['imagen'] = $nombreImagen;
        } elseif ($request->has('eliminar_imagen') && $request->boolean('eliminar_imagen') && $noticia->imagen) {
            // Eliminar la imagen si se marcó la opción de eliminar
            Storage::delete('public/noticias/' . $noticia->imagen);
            $validated['imagen'] = null;
        } else {
            // Mantener la imagen existente si no se proporciona una nueva
            unset($validated['imagen']);
        }
        
        // Si se está publicando y no hay fecha de publicación, usar la fecha actual
        if ($validated['estado'] === 'publicada' && empty($validated['publicado_en']) && $noticia->estado !== 'publicada') {
            $validated['publicado_en'] = now();
        }
        
        // Actualizar la noticia
        $noticia->update($validated);
        
        return redirect()->route('admin.noticias.edit', $noticia)
            ->with('success', 'Noticia actualizada exitosamente.');
    }

    /**
     * Elimina una noticia.
     */
    public function destroy(Noticia $noticia)
    {
        $this->authorize('delete', $noticia);
        
        // Eliminar la imagen si existe
        if ($noticia->imagen) {
            Storage::delete('public/noticias/' . $noticia->imagen);
        }
        
        $noticia->delete();
        
        return redirect()->route('admin.noticias.index')
            ->with('success', 'Noticia eliminada exitosamente.');
    }
    
    /**
     * Cambia el estado de una noticia.
     */
    public function cambiarEstado(Request $request, Noticia $noticia)
    {
        $this->authorize('update', $noticia);
        
        $request->validate([
            'estado' => 'required|in:borrador,publicada,archivada',
        ]);
        
        $noticia->estado = $request->estado;
        
        // Si se está publicando y no tiene fecha de publicación, establecerla
        if ($request->estado === 'publicada' && !$noticia->publicado_en) {
            $noticia->publicado_en = now();
        }
        
        $noticia->save();
        
        return response()->json([
            'success' => true,
            'message' => 'Estado de la noticia actualizado correctamente',
            'estado' => $noticia->estado,
            'estado_texto' => ucfirst($noticia->estado),
        ]);
    }
}