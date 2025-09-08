@extends('layouts.app')

@section('content')
<div class="container mx-auto py-12">
    <h1 class="text-4xl font-extrabold mb-12 text-center text-blue-900 tracking-wide drop-shadow">Fundaciones</h1>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10 justify-center">
        @forelse($fundaciones as $fundacion)
            <div class="bg-white rounded-2xl shadow-xl p-8 flex flex-col items-center border-2 border-cyan-100 hover:shadow-2xl hover:border-cyan-400 transition-all duration-200">
                <div class="w-32 h-32 mb-4 rounded-full overflow-hidden border-4 border-cyan-300 bg-cyan-50 flex items-center justify-center">
                    <img src="{{ $fundacion->imagen_url ?: asset('img/defaults/fundacion-default.jpg') }}" alt="{{ $fundacion->nombre }}" class="object-cover w-full h-full">
                </div>
                <h2 class="text-2xl font-bold mb-2 text-blue-900 text-center">{{ $fundacion->nombre }}</h2>
                <p class="text-gray-500 mb-1 text-center"><span class="font-semibold">Ubicación:</span> {{ $fundacion->ubicacion ?? 'No especificada' }}</p>
                <p class="text-gray-500 mb-1 text-center"><span class="font-semibold">Animales en adopción:</span> {{ $fundacion->animales_count }}</p>
                <p class="text-gray-500 mb-4 text-center"><span class="font-semibold">Donaciones recibidas:</span> {{ $fundacion->donaciones_count }}</p>
                <a href="/fundacion/{{ $fundacion->id }}" class="mt-3 inline-block bg-gradient-to-r from-blue-600 to-cyan-400 text-white px-6 py-2 rounded-full font-bold shadow hover:from-cyan-600 hover:to-blue-800 transition-all duration-200">Ver perfil</a>
            </div>
        @empty
            <div class="col-span-3 text-center text-gray-700 font-semibold">No hay fundaciones registradas.</div>
        @endforelse
    </div>
    <div class="mt-12 flex justify-center">
        {{ $fundaciones->links() }}
    </div>
</div>
@endsection