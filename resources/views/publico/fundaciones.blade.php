@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8">
    <h1 class="text-3xl font-bold mb-8 text-center">Fundaciones</h1>
    <div class="mb-6 flex justify-center">
        <form action="{{ route('publico.fundaciones') }}" method="GET" class="flex gap-2">
            <input type="text" name="nombre" placeholder="Buscar por nombre" value="{{ request('nombre') }}" class="border rounded px-3 py-1">
            <input type="text" name="ubicacion" placeholder="Buscar por ubicación" value="{{ request('ubicacion') }}" class="border rounded px-3 py-1">
            <button type="submit" class="bg-blue-600 text-white px-4 py-1 rounded">Buscar</button>
        </form>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($fundaciones as $fundacion)
            <div class="bg-white rounded shadow p-4 flex flex-col items-center">
                <img src="{{ $fundacion->imagen_url }}" alt="{{ $fundacion->nombre }}" class="w-32 h-32 object-cover rounded-full mb-4">
                <h2 class="text-xl font-semibold mb-2">{{ $fundacion->nombre }}</h2>
                <p class="text-gray-600 mb-1">Ubicación: {{ $fundacion->ubicacion ?? 'No especificada' }}</p>
                <p class="text-gray-600 mb-1">Animales en adopción: {{ $fundacion->animales_count }}</p>
                <p class="text-gray-600 mb-1">Donaciones recibidas: {{ $fundacion->donaciones_count }}</p>
                <a href="{{ route('publico.fundacion', $fundacion->id) }}" class="mt-3 inline-block bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-700">Ver perfil</a>
            </div>
        @empty
            <div class="col-span-3 text-center text-gray-700 font-semibold">No hay fundaciones registradas.</div>
        @endforelse
    </div>
    <div class="mt-8 flex justify-center">
        {{ $fundaciones->links() }}
    </div>
</div>
@endsection
