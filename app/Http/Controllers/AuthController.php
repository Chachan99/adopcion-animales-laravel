<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Animal;
use Illuminate\Support\Facades\Auth;

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
        Animal::destroy($id);
        return back();
    }
}
