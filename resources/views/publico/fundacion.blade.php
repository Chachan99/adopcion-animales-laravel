@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100">
    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg shadow-lg p-6">
            @if(Auth::check() && Auth::id() == $fundacion->usuario_id)
                <a href="{{ route('fundacion.perfil.editar') }}" class="bg-yellow-500 text-white px-4 py-2 rounded-lg hover:bg-yellow-700 mb-4 inline-block">
                    Editar mi perfil
                </a>
            @endif
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Información de la fundación -->
                <div>
                    <img src="{{ $fundacion->imagen ? asset('fundaciones/' . $fundacion->imagen) : asset('images/default-fundacion.jpg') }}"
                         alt="{{ $fundacion->nombre }}"
                         class="w-full h-64 object-cover rounded-lg mb-4">
                    <h1 class="text-3xl font-bold mb-4">{{ $fundacion->nombre }}</h1>
                    <div class="space-y-4">
                        <div class="flex items-center">
                            <span class="text-gray-600">Descripción:</span>
                            <span class="ml-2">{{ $fundacion->descripcion }}</span>
                        </div>
                        <div class="flex items-center">
                            <span class="text-gray-600">Dirección:</span>
                            <span class="ml-2">{{ $fundacion->direccion }}</span>
                        </div>
                        <div class="flex items-center">
                            <span class="text-gray-600">Teléfono:</span>
                            <span class="ml-2">{{ $fundacion->telefono }}</span>
                        </div>
                        <div class="flex items-center">
                            <span class="text-gray-600">Email:</span>
                            <span class="ml-2">{{ $fundacion->email }}</span>
                        </div>
                        @if($fundacion->sitio_web)
                        <div class="flex items-center">
                            <span class="text-gray-600">Sitio Web:</span>
                            <a href="{{ $fundacion->sitio_web }}" target="_blank" class="ml-2 text-blue-600 hover:text-blue-800">
                                {{ $fundacion->sitio_web }}
                            </a>
                        </div>
                        @endif
                        @if($fundacion->facebook)
                        <div class="flex items-center">
                            <span class="text-gray-600">Facebook:</span>
                            <a href="{{ $fundacion->facebook }}" target="_blank" class="ml-2 text-blue-600 hover:text-blue-800">
                                Facebook
                            </a>
                        </div>
                        @endif
                        @if($fundacion->instagram)
                        <div class="flex items-center">
                            <span class="text-gray-600">Instagram:</span>
                            <a href="{{ $fundacion->instagram }}" target="_blank" class="ml-2 text-blue-600 hover:text-blue-800">
                                Instagram
                            </a>
                        </div>
                        @endif
                        
                        <!-- Mapa de ubicación -->
                        <div class="mt-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Ubicación</h3>
                            <div id="map" class="h-64 w-full rounded-lg border border-gray-300"></div>
                            <p class="mt-2 text-sm text-gray-500">
                                {{ $fundacion->direccion }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Animales de la fundación -->
                <div>
                    <h2 class="text-2xl font-bold mb-6">Animales Disponibles</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($animales as $animal)
                        <div class="bg-white rounded-lg shadow p-4 flex flex-col items-center">
                            <div class="w-24 h-24 mb-2 rounded-full overflow-hidden border-4 border-cyan-300 bg-cyan-50 flex items-center justify-center">
                                <img src="{{ asset('test/' . $animal->imagen) }}" alt="{{ $animal->nombre }}" class="object-cover w-full h-full">
                            </div>
                            <h3 class="text-lg font-semibold mb-2 text-center">{{ $animal->nombre }}</h3>
                            <p class="text-gray-600 mb-2 text-center">{{ $animal->tipo }}</p>
                            <p class="text-gray-600 mb-4 text-center">{{ $animal->edad }} {{ $animal->tipo_edad === 'anios' ? 'años' : 'meses' }}</p>
                            <div class="flex justify-between items-center w-full">
                                <a href="{{ route('publico.animal', $animal->id) }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-800">Ver Detalles</a>
                                @auth
                                <form action="{{ route('adopcion.store') }}" method="POST" class="inline">
                                    @csrf
                                    <input type="hidden" name="animal_id" value="{{ $animal->id }}">
                                    <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-800">Solicitar Adopción</button>
                                </form>
                                @else
                                <a href="{{ route('publico.animal', $animal->id) }}" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-800">adoptar</a>
                                @endauth
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- Paginación -->
                    {{ $animales->links() }}
                </div>
            </div>

            <!-- Botón de donación -->
            <div class="mt-8 flex justify-center">
                <a href="{{ route('publico.donar', ['fundacion_id' => $fundacion->usuario_id]) }}" 
                   class="bg-green-600 text-white px-8 py-3 rounded-lg hover:bg-green-800">
                    Donar a {{ $fundacion->nombre }}
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
