<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Animal;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function index()
    {
        $animales = Animal::where('usuario_id', Auth::id())->get();
        return view('fundacion.animales.index', compact('animales'));
    }

    public function create()
    {
        return view('fundacion.animales.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:255',
            'especie' => 'required|string',
            'descripcion' => 'nullable|string',
        ]);

        $data['usuario_id'] = Auth::id();

        Animal::create($data);

        return redirect()->route('fundacion.animales.index');
    }

    public function edit($id)
    {
        $animal = Animal::findOrFail($id);
        return view('fundacion.animales.edit', compact('animal'));
    }

    public function update(Request $request, $id)
    {
        $animal = Animal::findOrFail($id);

        $animal->update($request->all());

        return redirect()->route('fundacion.animales.index');
    }

    public function destroy($id)
    {
        try {
            return \Illuminate\Support\Facades\DB::transaction(function () use ($id) {
                $animal = Animal::with(['solicitudesAdopcion', 'donaciones'])->findOrFail($id);

                // Eliminar documentos de solicitudes
                $animal->solicitudesAdopcion->each(function ($solicitud) {
                    if ($solicitud->documentos) {
                        $documentos = json_decode($solicitud->documentos, true);
                        if (is_array($documentos)) {
                            collect($documentos)->each(function ($doc) {
                                if (isset($doc['ruta'])) {
                                    \Illuminate\Support\Facades\Storage::disk('public')->delete($doc['ruta']);
                                }
                            });
                        }
                    }
                });

                // Eliminar relaciones
                $animal->solicitudesAdopcion()->delete();
                $animal->donaciones()->delete();

                // Eliminar imagen
                if ($animal->imagen) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($animal->imagen);
                }

                $animal->delete();

                return back()->with('success', 'Animal eliminado exitosamente');
            });
            
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error al eliminar animal: ' . $e->getMessage());
            return back()->with('error', 'Error al eliminar el animal: ' . $e->getMessage());
        }
    }
}
