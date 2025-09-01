@extends('layouts.app')

@section('title', 'Mascotas Perdidas - ' . config('app.name'))

@section('content')
<div class="bg-gradient-to-b from-gray-100 to-white py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-10">
            <h1 class="text-4xl font-extrabold text-gray-900 mb-3 tracking-tight">Mascotas Perdidas</h1>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">Ayudemos a reunir a estas mascotas con sus familias. Cada reporte cuenta.</p>
            <div class="mt-8 flex flex-col sm:flex-row justify-center gap-4">
                <a href="#como-ayudar" class="inline-flex items-center px-6 py-3 border border-gray-300 text-base font-medium rounded-lg shadow-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200">
                    <svg class="-ml-1 mr-3 h-5 w-5 text-indigo-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                    </svg>
                    Cómo puedo ayudar
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="rounded-lg bg-green-50 p-4 mb-8 border border-green-100">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-semibold text-green-800">{{ session('success') }}</h3>
                    </div>
                    <div class="ml-auto pl-3">
                        <button type="button" class="text-green-500 hover:text-green-600 focus:outline-none" onclick="this.parentElement.parentElement.remove()">
                            <span class="sr-only">Cerrar</span>
                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        @endif

        <!-- Filtros -->
        <div class="mb-8 bg-white p-4 rounded-lg shadow-sm border border-gray-200">
            <h2 class="sr-only">Filtros de búsqueda</h2>
            <form method="GET" action="{{ route('animales-perdidos.index') }}" class="space-y-4 sm:space-y-0 sm:flex sm:items-end sm:space-x-4">
                <div class="flex-1">
                    <label for="tipo" class="block text-sm font-medium text-gray-700 mb-1">Tipo de mascota</label>
                    <select id="tipo" name="tipo" class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                        <option value="">Todos los tipos</option>
                        <option value="perro" {{ request('tipo') == 'perro' ? 'selected' : '' }}>Perros</option>
                        <option value="gato" {{ request('tipo') == 'gato' ? 'selected' : '' }}>Gatos</option>
                        <option value="otro" {{ request('tipo') == 'otro' ? 'selected' : '' }}>Otros</option>
                    </select>
                </div>
                <div class="flex-1">
                    <label for="ubicacion" class="block text-sm font-medium text-gray-700 mb-1">Ubicación aproximada</label>
                    <input type="text" id="ubicacion" name="ubicacion" value="{{ request('ubicacion') }}" placeholder="Ej: Ocaña" class="block w-full shadow-sm sm:text-sm focus:ring-indigo-500 focus:border-indigo-500 border-gray-300 rounded-md">
                </div>
                <div>
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                        </svg>
                        Filtrar
                    </button>
                </div>
                @if(request()->has('tipo') || request()->has('ubicacion'))
                    <div>
                        <a href="{{ route('animales-perdidos.index') }}" class="inline-flex items-center px-3 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            Limpiar filtros
                        </a>
                    </div>
                @endif
            </form>
        </div>

        @if($animalesPerdidos->isEmpty())
            <div class="text-center py-16 bg-white rounded-lg shadow-sm border border-gray-200">
                <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <h3 class="mt-4 text-lg font-medium text-gray-900">No hay mascotas perdidas reportadas</h3>
                <p class="mt-2 text-sm text-gray-600 max-w-md mx-auto">Actualmente no hay mascotas reportadas como perdidas. Si has perdido a tu mascota o has encontrado una, por favor repórtala para ayudar a reunirla con su familia.</p>
                <div class="mt-6">
                    <a href="{{ route('animales-perdidos.create') }}" class="inline-flex items-center px-5 py-2.5 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                        </svg>
                        Reportar Mascota Perdida
                    </a>
                </div>
            </div>
        @else
            <div class="mb-4 flex justify-between items-center">
                <p class="text-sm text-gray-600">
                    Mostrando <span class="font-medium">{{ $animalesPerdidos->firstItem() }}</span> a <span class="font-medium">{{ $animalesPerdidos->lastItem() }}</span> de <span class="font-medium">{{ $animalesPerdidos->total() }}</span> mascotas perdidas
                </p>
                <div class="flex items-center">
                    <span class="text-sm text-gray-600 mr-2">Ordenar por:</span>
                    <select onchange="window.location.href = this.value" class="block w-full pl-3 pr-10 py-1.5 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                        <option value="{{ route('animales-perdidos.index', ['sort' => 'recientes', ...request()->except('sort')]) }}" {{ request('sort') == 'recientes' || !request()->has('sort') ? 'selected' : '' }}>Más recientes</option>
                        <option value="{{ route('animales-perdidos.index', ['sort' => 'antiguos', ...request()->except('sort')]) }}" {{ request('sort') == 'antiguos' ? 'selected' : '' }}>Más antiguos</option>
                        <option value="{{ route('animales-perdidos.index', ['sort' => 'recompensa', ...request()->except('sort')]) }}" {{ request('sort') == 'recompensa' ? 'selected' : '' }}>Con recompensa</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                @foreach($animalesPerdidos as $animal)
                    <div class="bg-white overflow-hidden shadow rounded-lg hover:shadow-md transition-shadow duration-200 flex flex-col h-full">
                        <div class="relative pb-48 overflow-hidden">
                            <img class="absolute inset-0 h-full w-full object-cover transition-transform duration-300 hover:scale-105" src="{{ $animal->imagen_url }}" alt="{{ $animal->nombre }}" loading="lazy">
                            <div class="absolute inset-x-0 top-0 p-2 flex justify-between">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    Perdido
                                </span>
                                @if($animal->recompensa)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        <svg class="-ml-0.5 mr-1 h-3 w-3 text-yellow-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                        Recompensa
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="p-4 flex-1 flex flex-col">
                            <div class="flex items-center justify-between mb-2">
                                <h3 class="text-lg font-semibold text-gray-900 truncate">{{ $animal->nombre ?: 'Sin nombre' }}</h3>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800 capitalize">
                                    {{ $animal->tipo ?: 'mascota' }}
                                </span>
                            </div>
                            
                            <div class="mt-1 flex items-center text-sm text-gray-500">
                                <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                                </svg>
                                {{ $animal->ultima_ubicacion ?: 'Ubicación no especificada' }}
                            </div>
                            
                            <div class="mt-1 flex items-center text-sm text-gray-500">
                                <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                                </svg>
                                {{ $animal->created_at->diffForHumans() }}
                            </div>
                            
                            @if($animal->descripcion)
                                <div class="mt-3">
                                    <p class="text-sm text-gray-600 line-clamp-2">{{ $animal->descripcion }}</p>
                                </div>
                            @endif
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 flex justify-between items-center">
                            <span class="text-xs text-gray-500">
                                ID: {{ $animal->id }}
                            </span>
                            <a href="{{ route('animales-perdidos.show', $animal->id) }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-500 flex items-center">
                                Ver detalles
                                <svg class="ml-1 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-8">
                {{ $animalesPerdidos->withQueryString()->links() }}
            </div>
        @endif

        <!-- Sección de cómo ayudar -->
        <div id="como-ayudar" class="mt-16 bg-white rounded-lg shadow-md overflow-hidden border border-gray-200">
            <div class="p-6 sm:p-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">¿Cómo puedo ayudar?</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="flex flex-col">
                        <div class="flex items-center mb-3">
                            <div class="flex-shrink-0 bg-indigo-100 p-2 rounded-full">
                                <svg class="h-6 w-6 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                            </div>
                            <h3 class="ml-3 text-lg font-medium text-gray-900">Comparte en redes</h3>
                        </div>
                        <p class="text-gray-600 flex-1">Comparte los reportes de mascotas perdidas en tus redes sociales para aumentar su visibilidad.</p>
                    </div>
                    
                    <div class="flex flex-col">
                        <div class="flex items-center mb-3">
                            <div class="flex-shrink-0 bg-indigo-100 p-2 rounded-full">
                                <svg class="h-6 w-6 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                </svg>
                            </div>
                            <h3 class="ml-3 text-lg font-medium text-gray-900">Ofrece recompensa</h3>
                        </div>
                        <p class="text-gray-600 flex-1">Si puedes, ofrece una recompensa por información que lleve a encontrar a la mascota.</p>
                    </div>
                    
                    <div class="flex flex-col">
                        <div class="flex items-center mb-3">
                            <div class="flex-shrink-0 bg-indigo-100 p-2 rounded-full">
                                <svg class="h-6 w-6 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z" />
                                </svg>
                            </div>
                            <h3 class="ml-3 text-lg font-medium text-gray-900">Reporta avistamientos</h3>
                        </div>
                        <p class="text-gray-600 flex-1">Si ves una mascota que coincide con algún reporte, contacta al dueño inmediatamente.</p>
                    </div>
                </div>
                <div class="mt-8 pt-6 border-t border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Consejos si encuentras una mascota perdida</h3>
                    <ul class="list-disc pl-5 space-y-2 text-gray-600">
                        <li>Acércate con cuidado y verifica si tiene collar con identificación</li>
                        <li>Si es seguro, lleva la mascota a un veterinario para escanear su microchip</li>
                        <li>Toma fotos claras desde diferentes ángulos</li>
                        <li>Publica en grupos locales de mascotas perdidas</li>
                        <li>No alimentes a la mascota con comida que no conozcas (puede tener alergias)</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
