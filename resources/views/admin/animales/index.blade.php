@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100">
    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h1 class="text-2xl font-bold mb-6">Animales Registrados</h1>
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    {{ session('success') }}
                </div>
            @endif
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($animales as $animal)
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <img src="{{ $animal->imagen_url }}" 
                         alt="{{ $animal->nombre }}"
                         class="w-full h-48 object-cover rounded-lg mb-4">
                    <h3 class="text-xl font-semibold mb-2">{{ $animal->nombre }}</h3>
                    <p class="text-gray-600 mb-2">{{ $animal->tipo }}</p>
                    <p class="text-gray-600 mb-2">{{ $animal->edad }} años</p>
                    <p class="text-sm mb-2">
                        <span class="font-semibold">Estado:</span>
                        <span class="{{ $animal->estado === 'adoptado' ? 'text-green-600' : ($animal->estado === 'disponible' ? 'text-blue-600' : 'text-yellow-600') }}">
                            {{ ucfirst($animal->estado) }}
                        </span>
                    </p>
                    <p class="text-sm mb-2">
                        <span class="font-semibold">Fundación:</span>
                        <span class="text-cyan-700">{{ $animal->fundacion->nombre ?? '-' }}</span>
                    </p>
                    <div class="flex justify-between items-center mt-4">
                        <a href="{{ route('admin.animales.editar', $animal->id) }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-800">Editar</a>
                        <form action="{{ route('admin.animales.eliminar', $animal->id) }}" method="POST" class="inline" onsubmit="return confirm('¿Seguro que deseas eliminar este animal?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-800">Eliminar</button>
                        </form>
                    </div>
                </div>
                @empty
                <div class="col-span-3 text-center text-blue-900 font-semibold">No hay animales registrados.</div>
                @endforelse
            </div>
            <div class="mt-6 flex justify-center">
                {{ $animales->links() }}
            </div>
        </div>
    </div>
</div>
@endsection