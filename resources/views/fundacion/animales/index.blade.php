@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-xl shadow-md overflow-hidden p-6">
            <!-- Header y botón de acción -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">Mis Animales</h1>
                    <p class="text-gray-600 mt-1">Administra los animales de tu fundación</p>
                </div>
                <a href="{{ route('fundacion.animales.crear') }}" 
                   class="bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700 transition-colors duration-200 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                    </svg>
                    Agregar Animal
                </a>
            </div>

            <!-- Filtros y mensajes -->
            <div class="mb-8">
                <!-- Filtro por estado -->
                <form method="GET" class="mb-6">
                    <div class="flex flex-col sm:flex-row items-start sm:items-center gap-3">
                        <label for="estado" class="font-medium text-gray-700">Filtrar por estado:</label>
                        <select name="estado" id="estado" onchange="this.form.submit()" 
                                class="border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">Todos los estados</option>
                            <option value="disponible" {{ request('estado') == 'disponible' ? 'selected' : '' }}>Disponible</option>
                            <option value="adoptado" {{ request('estado') == 'adoptado' ? 'selected' : '' }}>Adoptado</option>
                            <option value="en_adopcion" {{ request('estado') == 'en_adopcion' ? 'selected' : '' }}>En proceso de adopción</option>
                        </select>
                    </div>
                </form>

                @if(session('success'))
                    <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-green-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-green-700">{{ session('success') }}</p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Listado de animales -->
            @if($animales->isEmpty())
                <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-blue-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-blue-700">No hay animales registrados. ¡Agrega tu primer animal!</p>
                        </div>
                    </div>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($animales as $animal)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden border border-gray-100 hover:shadow-lg transition-shadow duration-200">
                        <!-- Imagen del animal -->
                        <div class="relative h-48 w-full overflow-hidden bg-gray-100">
                            <img src="{{ $animal->imagen_url }}" 
                                 alt="{{ $animal->nombre }}"
                                 class="object-cover w-full h-full transition-transform duration-500 hover:scale-105"
                                 onerror="this.onerror=null; this.src='{{ asset('img/defaults/animal-default.jpg') }}'"
                                 loading="lazy" />
                            <div class="absolute top-2 right-2">
                                <span class="{{ 
                                    $animal->estado === 'adoptado' ? 'bg-green-100 text-green-800' : 
                                    ($animal->estado === 'disponible' ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800')
                                }} text-xs font-semibold px-2.5 py-0.5 rounded-full">
                                    {{ ucfirst($animal->estado) }}
                                </span>
                            </div>
                        </div>

                        <!-- Información del animal -->
                        <div class="p-5">
                            <h3 class="text-xl font-bold text-gray-800 mb-2">{{ $animal->nombre }}</h3>
                            
                            <div class="flex items-center text-gray-600 mb-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                                </svg>
                                <span>{{ $animal->edad }} {{ $animal->tipo_edad === 'anios' ? 'años' : 'meses' }}</span>
                            </div>
                            
                            <div class="flex items-center text-gray-600 mb-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M3 5a2 2 0 012-2h10a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V5zm7 1a1 1 0 10-2 0v3H5a1 1 0 100 2h3v3a1 1 0 102 0v-3h3a1 1 0 100-2h-3V6z" clip-rule="evenodd" />
                                </svg>
                                <span class="capitalize">{{ $animal->tipo }}</span>
                            </div>

                            <!-- Acciones -->
                            <div class="flex flex-wrap gap-2 mt-6">
                                <a href="/panel-fundacion/animales/{{ $animal->id }}/editar" 
                                   class="flex-1 min-w-[120px] text-center bg-indigo-100 text-indigo-700 px-4 py-2 rounded-md hover:bg-indigo-200 transition-colors duration-200 flex items-center justify-center gap-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                    </svg>
                                    Editar
                                </a>
                                
                                @if($animal->estado === 'disponible')
                                <form action="{{ route('fundacion.animales.adoptado', $animal->id) }}" method="POST" class="flex-1 min-w-[120px]">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" 
                                            class="w-full bg-green-100 text-green-700 px-4 py-2 rounded-md hover:bg-green-200 transition-colors duration-200 flex items-center justify-center gap-1"
                                            onclick="return confirm('¿Marcar este animal como adoptado?')">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                        </svg>
                                        Adoptado
                                    </button>
                                </form>
                                @endif
                                
                                <form action="{{ route('fundacion.animales.eliminar', $animal->id) }}" method="POST" class="flex-1 min-w-[120px]">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="w-full bg-red-100 text-red-700 px-4 py-2 rounded-md hover:bg-red-200 transition-colors duration-200 flex items-center justify-center gap-1"
                                            onclick="return confirm('¿Estás seguro de eliminar este animal? Esta acción no se puede deshacer.')">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                        </svg>
                                        Eliminar
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Paginación -->
                <div class="mt-8">
                    {{ $animales->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection