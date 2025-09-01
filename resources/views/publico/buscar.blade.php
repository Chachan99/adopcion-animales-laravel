@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100">
    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <!-- Filtros de búsqueda -->
        <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
            <h2 class="text-xl font-semibold mb-4">Filtros de Búsqueda</h2>
            <form action="{{ route('publico.buscar') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Tipo</label>
                    <select name="tipo" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">Todos</option>
                        <option value="perro" @if(request('tipo') === 'perro') selected @endif>Perro</option>
                        <option value="gato" @if(request('tipo') === 'gato') selected @endif>Gato</option>
                        <option value="otro" @if(request('tipo') === 'otro') selected @endif>Otro</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Edad Mínima</label>
                    <input type="number" name="edad_min" value="{{ request('edad_min') }}" 
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Edad Máxima</label>
                    <input type="number" name="edad_max" value="{{ request('edad_max') }}" 
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>
                <div class="col-span-3 mt-4">
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-800 w-full">
                        Actualizar Búsqueda
                    </button>
                </div>
            </form>
        </div>

        <!-- Resultados -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-xl font-semibold mb-6">Resultados de Búsqueda</h2>
            
            @if(count($animales) === 0)
                <div class="text-center py-8">
                    <p class="text-gray-600">No se encontraron animales que coincidan con tus criterios de búsqueda.</p>
                    <a href="{{ route('publico.index') }}" class="mt-4 inline-block bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-800">
                        Ver Todos los Animales
                    </a>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($animales as $animal)
                    <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300 flex flex-col h-full">
                        <div class="aspect-w-1 aspect-h-1 w-full rounded-t-lg overflow-hidden">
                            <img 
                                src="{{ $animal->imagen_url }}"
                                alt="{{ $animal->nombre }}"
                                class="object-cover w-full h-full transition-transform duration-300 hover:scale-105"
                                onerror="this.onerror=null; this.src='{{ asset('img/animal-default.jpg') }}'"
                                loading="lazy"
                            />
                        </div>
                        <div class="p-4 flex flex-col flex-grow">
                            <h3 class="text-xl font-bold text-blue-900 mb-2">{{ $animal->nombre }}</h3>
                            <div class="flex flex-wrap gap-2 mb-3">
                                <span class="px-3 py-1 bg-blue-100 text-blue-800 text-sm font-medium rounded-full">
                                    {{ ucfirst($animal->tipo) }}
                                </span>
                                <span class="px-3 py-1 bg-cyan-100 text-cyan-800 text-sm font-medium rounded-full">
                                    {{ $animal->edad }} {{ $animal->tipo_edad === 'anios' ? 'años' : 'meses' }}
                                </span>
                                <span class="px-3 py-1 bg-green-100 text-green-800 text-sm font-medium rounded-full">
                                    {{ ucfirst($animal->sexo) }}
                                </span>
                            </div>
                            <p class="text-gray-600 mb-4 line-clamp-2 flex-grow">
                                {{ $animal->descripcion }}
                            </p>
                            
                            @auth
                                <form action="{{ route('adopcion.store') }}" method="POST" class="w-full">
                                    @csrf
                                    <input type="hidden" name="animal_id" value="{{ $animal->id }}">
                                    <button type="submit" class="w-full bg-gradient-to-r from-green-500 to-green-700 text-white px-4 py-2 rounded-lg font-semibold hover:from-green-600 hover:to-green-800 transition-all duration-300">
                                        Solicitar Adopción <i class="fas fa-heart ml-1"></i>
                                    </button>
                                </form>
                            @else
                            <a href="{{ route('publico.animal', $animal->id) }}" class="mt-2 block w-full text-center bg-gradient-to-r from-cyan-400 to-blue-900 text-white px-4 py-2 rounded-lg font-semibold hover:from-blue-800 hover:to-cyan-600 transition-all duration-300">
                                Ver detalles <i class="fas fa-arrow-right ml-1"></i>
                            </a>
                            @endauth
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Paginación -->
                {{ $animales->links() }}
            @endif
        </div>
    </div>
</div>
@endsection