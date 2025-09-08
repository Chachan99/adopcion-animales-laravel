@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold mb-8 text-gray-800">Fundaciones</h1>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($fundaciones as $fundacion)
                <div class="bg-white rounded-xl shadow-md p-6 flex flex-col items-center border-t-4 border-green-500">
                    <img src="{{ $fundacion->imagen_url }}" alt="{{ $fundacion->nombre }}" class="w-32 h-32 object-cover rounded-full mb-4">
                    <h2 class="text-xl font-semibold mb-2">{{ $fundacion->nombre }}</h2>
                    <p class="text-gray-600 mb-1">Ubicación: {{ $fundacion->direccion ?? 'No especificada' }}</p>
                    <p class="text-gray-600 mb-1">Animales en adopción: {{ $fundacion->animales_count }}</p>
                    <p class="text-gray-600 mb-1">Donaciones recibidas: {{ $fundacion->donaciones_count }}</p>
                    <a href="{{ route('publico.fundacion', $fundacion->id) }}" class="mt-3 inline-block bg-green-600 text-white px-4 py-2 rounded hover:bg-green-800">Ver perfil público</a>
                </div>
            @empty
                <div class="col-span-3 text-center text-gray-700 font-semibold">No hay fundaciones registradas.</div>
            @endforelse
        </div>
        <div class="mt-8 flex justify-center">
            {{ $fundaciones->links() }}
        </div>
    </div>
</div>
@endsection
