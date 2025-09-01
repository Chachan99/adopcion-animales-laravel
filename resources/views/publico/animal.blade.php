@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-cyan-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-6xl mx-auto">
        <!-- Improved Breadcrumb Navigation -->
        <nav class="mb-8" aria-label="Breadcrumb">
            <ol class="flex items-center space-x-2 text-sm">
                <li>
                    <a href="{{ route('publico.index') }}" class="text-cyan-600 hover:text-cyan-800 transition-colors duration-200 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
                        </svg>
                        Inicio
                    </a>
                </li>
                <li>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    </svg>
                </li>
                <li>
                    <a href="{{ route('publico.buscar') }}" class="text-cyan-600 hover:text-cyan-800 transition-colors duration-200">
                        Animales
                    </a>
                </li>
                <li>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    </svg>
                </li>
                <li class="text-blue-900 font-semibold truncate max-w-xs">
                    {{ $animal->nombre }}
                </li>
            </ol>
        </nav>

        <!-- Main Animal Card - Enhanced with better spacing and structure -->
        <div class="bg-white rounded-3xl shadow-2xl overflow-hidden border border-white/20 backdrop-blur-sm">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-0">
                <!-- Image Gallery with multiple image support -->
                <div class="relative group h-full min-h-[400px] bg-gray-100">
                    <!-- Main Image -->
                    <div class="w-full h-full overflow-hidden rounded-l-3xl relative">
                        <img 
                            src="{{ $animal->imagen_url }}" 
                            alt="{{ $animal->nombre }}" 
                            class="w-full h-full object-cover transition-all duration-500 group-hover:scale-105"
                            onerror="this.onerror=null; this.src='{{ asset('img/defaults/animal-default.jpg') }}'"
                            loading="lazy"
                            id="mainAnimalImage"
                        >
                        
                        <!-- Image Gallery Navigation (if multiple images exist) -->
                        @if(isset($animal->imagenes) && count($animal->imagenes) > 1)
                        <div class="absolute bottom-4 left-0 right-0 flex justify-center space-x-2">
                            @foreach($animal->imagenes as $index => $imagen)
                            <button 
                                onclick="changeMainImage('{{ $imagen->url }}')"
                                class="w-3 h-3 rounded-full bg-white/80 hover:bg-white transition-colors focus:outline-none focus:ring-2 focus:ring-cyan-500 {{ $index === 0 ? 'bg-white' : '' }}"
                                aria-label="Ver imagen {{ $index + 1 }}"
                            ></button>
                            @endforeach
                        </div>
                        @endif
                    </div>
                    
                    <!-- Badges -->
                    <div class="absolute top-4 left-4">
                        <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-bold bg-gradient-to-r from-blue-800 to-cyan-500 text-white shadow-lg">
                            <i class="fas fa-paw mr-2"></i>
                            {{ ucfirst($animal->tipo) }}
                        </span>
                    </div>
                    <div class="absolute bottom-4 right-4">
                        <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-bold bg-white/90 text-blue-900 backdrop-blur-sm shadow-lg">
                            <i class="fas fa-birthday-cake mr-2"></i>
                            {{ $animal->edad }} {{ $animal->tipo_edad === 'anios' ? 'años' : 'meses' }}
                        </span>
                    </div>
                </div>

                <!-- Information Section - Better organized -->
                <div class="p-8 flex flex-col">
                    <!-- Header with name and location -->
                    <div class="mb-6">
                        <div class="flex justify-between items-start">
                            <h1 class="text-4xl font-bold text-blue-900 mb-2">{{ $animal->nombre }}</h1>
                            <!-- Favorite button -->
                            <button 
                                id="favoriteBtn"
                                class="text-2xl focus:outline-none transition-colors duration-200"
                                aria-label="Añadir a favoritos"
                                data-animal-id="{{ $animal->id }}"
                            >
                                <i class="far fa-heart text-gray-300 hover:text-red-500"></i>
                            </button>
                        </div>
                        
                        <div class="flex items-center space-x-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-cyan-500" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                            </svg>
                            <span class="text-cyan-600 font-medium">
                                @if(!empty($animal->direccion))
                                    <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($animal->direccion) }}" target="_blank" class="underline hover:text-cyan-800">
                                        <i class="fas fa-map-marker-alt mr-1"></i>{{ $animal->direccion }}
                                    </a>
                                @elseif($animal->latitud && $animal->longitud)
                                    <a href="https://www.google.com/maps?q={{ $animal->latitud }},{{ $animal->longitud }}" target="_blank" class="underline hover:text-cyan-800">
                                        <i class="fas fa-map-marker-alt mr-1"></i>Ver ubicación en mapa
                                    </a>
                                @elseif($animal->fundacion && !empty($animal->fundacion->ubicacion))
                                    <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($animal->fundacion->ubicacion) }}" target="_blank" class="underline hover:text-cyan-800">
                                        <i class="fas fa-map-marker-alt mr-1"></i>{{ $animal->fundacion->ubicacion }} (Ubicación del refugio)
                                    </a>
                                @else
                                    <span class="text-amber-600">
                                        <i class="fas fa-info-circle mr-1"></i>Ubicación no especificada. Contacta al refugio para más detalles.
                                    </span>
                                @endif
                            </span>
                        </div>
                    </div>

                    <!-- Quick Facts - Improved layout -->
                    <div class="grid grid-cols-2 gap-4 mb-8">
                        <!-- Species -->
                        <div class="bg-blue-50/50 p-4 rounded-xl border border-blue-100 hover:bg-blue-100/50 transition-colors">
                            <h3 class="text-xs font-semibold text-blue-900 uppercase tracking-wider mb-1">Especie</h3>
                            <p class="text-blue-800 font-medium flex items-center">
                                @if($animal->tipo === 'perro')
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M9 12H5.414l1.293-1.293a1 1 0 00-1.414-1.414l-3 3a1 1 0 000 1.414l3 3a1 1 0 001.414-1.414L5.414 14H9a5 5 0 005-5V7.414l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 001.414 1.414L12 7.414V9a3 3 0 01-3 3z" />
                                </svg>
                                @elseif($animal->tipo === 'gato')
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M9.504 1.132a1 1 0 01.992 0l1.75 1a1 1 0 11-.992 1.736L10 3.152l-1.254.716a1 1 0 11-.992-1.736l1.75-1zM5.618 4.504a1 1 0 01-.372 1.364L5.016 6l.23.132a1 1 0 11-.992 1.736L4 7.723V8a1 1 0 01-2 0V6a.996.996 0 01.52-.878l1.734-.99a1 1 0 011.364.372zm8.764 0a1 1 0 011.364-.372l1.733.99A1.002 1.002 0 0118 6v2a1 1 0 11-2 0v-.277l-.254.145a1 1 0 11-.992-1.736l.23-.132-.23-.132a1 1 0 01-.372-1.364zm-7 4a1 1 0 011.364-.372L10 8.848l1.254-.716a1 1 0 11.992 1.736L11 10.58V12a1 1 0 11-2 0v-1.42l-1.246-.712a1 1 0 01-.372-1.364zM3 11a1 1 0 011 1v1.42l1.246.712a1 1 0 11-.992 1.736l-1.75-1A1 1 0 012 14v-2a1 1 0 011-1zm14 0a1 1 0 011 1v2a1 1 0 01-.504.868l-1.75 1a1 1 0 11-.992-1.736L16 13.42V12a1 1 0 011-1zm-9.618 5.504a1 1 0 011.364-.372l.254.145V16a1 1 0 112 0v.277l.254-.145a1 1 0 11.992 1.736l-1.735.992a.995.995 0 01-1.022 0l-1.735-.992a1 1 0 01-.372-1.364z" clip-rule="evenodd" />
                                </svg>
                                @else
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd" />
                                </svg>
                                @endif
                                {{ ucfirst($animal->tipo) }}
                            </p>
                        </div>

                        <!-- Age -->
                        <div class="bg-blue-50/50 p-4 rounded-xl border border-blue-100 hover:bg-blue-100/50 transition-colors">
                            <h3 class="text-xs font-semibold text-blue-900 uppercase tracking-wider mb-1">Edad</h3>
                            <p class="text-blue-800 font-medium flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                                </svg>
                                {{ $animal->edad }} {{ $animal->tipo_edad === 'anios' ? 'años' : 'meses' }}
                            </p>
                        </div>

                        @if($animal->tamano)
                        <!-- Size -->
                        <div class="bg-blue-50/50 p-4 rounded-xl border border-blue-100 hover:bg-blue-100/50 transition-colors">
                            <h3 class="text-xs font-semibold text-blue-900 uppercase tracking-wider mb-1">Tamaño</h3>
                            <p class="text-blue-800 font-medium flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M5 4a1 1 0 00-2 0v7.268a2 2 0 000 3.464V16a1 1 0 102 0v-1.268a2 2 0 000-3.464V4zM11 4a1 1 0 10-2 0v1.268a2 2 0 000 3.464V16a1 1 0 102 0V8.732a2 2 0 000-3.464V4zM16 3a1 1 0 011 1v7.268a2 2 0 010 3.464V16a1 1 0 11-2 0v-1.268a2 2 0 010-3.464V4a1 1 0 011-1z" />
                                </svg>
                                {{ ucfirst($animal->tamano) }}
                            </p>
                        </div>
                        @endif

                        @if($animal->genero)
                        <!-- Gender -->
                        <div class="bg-blue-50/50 p-4 rounded-xl border border-blue-100 hover:bg-blue-100/50 transition-colors">
                            <h3 class="text-xs font-semibold text-blue-900 uppercase tracking-wider mb-1">Género</h3>
                            <p class="text-blue-800 font-medium flex items-center">
                                @if($animal->genero === 'macho')
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M9.707 14.707a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 1.414L7.414 9H15a1 1 0 110 2H7.414l2.293 2.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                                </svg>
                                @else
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L12.586 11H5a1 1 0 110-2h7.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                                @endif
                                {{ ucfirst($animal->genero) }}
                            </p>
                        </div>
                        @endif

                        @if($animal->raza)
                        <!-- Breed -->
                        <div class="bg-blue-50/50 p-4 rounded-xl border border-blue-100 hover:bg-blue-100/50 transition-colors">
                            <h3 class="text-xs font-semibold text-blue-900 uppercase tracking-wider mb-1">Raza</h3>
                            <p class="text-blue-800 font-medium flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd" />
                                </svg>
                                {{ $animal->raza }}
                            </p>
                        </div>
                        @endif

                        @if($animal->salud)
                        <!-- Health Status -->
                        <div class="bg-blue-50/50 p-4 rounded-xl border border-blue-100 hover:bg-blue-100/50 transition-colors">
                            <h3 class="text-xs font-semibold text-blue-900 uppercase tracking-wider mb-1">Salud</h3>
                            <p class="text-blue-800 font-medium flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                {{ $animal->salud }}
                            </p>
                        </div>
                        @endif
                    </div>

                    <!-- Description with expand/collapse functionality -->
                    @if($animal->descripcion)
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold text-blue-900 mb-3 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-cyan-500" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z" />
                            </svg>
                            Sobre {{ $animal->nombre }}
                        </h3>
                        <div class="prose prose-blue max-w-none text-blue-800">
                            <div id="animalDescription" class="line-clamp-4">
                                {!! nl2br(e($animal->descripcion)) !!}
                            </div>
                            <button 
                                id="readMoreBtn"
                                class="text-cyan-600 hover:text-cyan-800 text-sm font-medium mt-2 focus:outline-none"
                            >
                                Leer más
                            </button>
                        </div>
                    </div>
                    @endif

                    <!-- Personality Traits -->
                    @if($animal->personalidad)
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold text-blue-900 mb-3 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-cyan-500" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                            </svg>
                            Personalidad
                        </h3>
                        <div class="flex flex-wrap gap-2">
                            @foreach(explode(',', $animal->personalidad) as $trait)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-cyan-100 text-cyan-800">
                                {{ trim($trait) }}
                            </span>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Shelter Info - Enhanced with social proof -->
                    @if($animal->fundacion)
                    <div class="mb-8 bg-white rounded-2xl shadow-md overflow-hidden border border-blue-50">
                        <div class="flex flex-col md:flex-row">
                            <!-- Shelter Image -->
                            <div class="md:w-1/3 p-6 flex items-center justify-center bg-blue-50">
                                @if($animal->fundacion->imagen)
                                    <img src="{{ asset($animal->fundacion->imagen) }}" 
                                         alt="{{ $animal->fundacion->nombre }}"
                                         class="w-full h-48 md:h-full object-cover rounded-lg shadow">
                                @else
                                    <div class="w-full h-48 md:h-full bg-gray-200 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-paw text-gray-400 text-5xl"></i>
                                    </div>
                                @endif
                            </div>
                            
                            <!-- Shelter Information -->
                            <div class="md:w-2/3 p-6">
                                <h3 class="text-2xl font-bold text-blue-900 mb-3 flex items-center">
                                    <i class="fas fa-home text-cyan-500 mr-2"></i>
                                    {{ $animal->fundacion->nombre }}
                                </h3>
                                
                                <p class="text-blue-800 mb-4">{{ $animal->fundacion->descripcion ?? 'Esta fundación se dedica al cuidado y protección de animales en situación de calle.' }}</p>
                                
                                <!-- Contact Info -->
                                <div class="space-y-3">
                                    @if($animal->fundacion->telefono)
                                    <div class="flex items-center">
                                        <div class="bg-blue-50 p-2 rounded-full mr-3">
                                            <i class="fas fa-phone-alt text-cyan-500"></i>
                                        </div>
                                        <div>
                                            <p class="text-sm text-gray-500">Teléfono</p>
                                            <a href="tel:{{ $animal->fundacion->telefono }}" class="text-blue-700 hover:text-cyan-600 font-medium">
                                                {{ $animal->fundacion->telefono }}
                                            </a>
                                        </div>
                                    </div>
                                    @endif
                                    
                                    @if($animal->fundacion->email)
                                    <div class="flex items-center">
                                        <div class="bg-blue-50 p-2 rounded-full mr-3">
                                            <i class="fas fa-envelope text-cyan-500"></i>
                                        </div>
                                        <div>
                                            <p class="text-sm text-gray-500">Correo</p>
                                            <a href="mailto:{{ $animal->fundacion->email }}" class="text-blue-700 hover:text-cyan-600 font-medium">
                                                {{ $animal->fundacion->email }}
                                            </a>
                                        </div>
                                    </div>
                                    @endif
                                    
                                    @if($animal->fundacion->sitio_web)
                                    <div class="flex items-center">
                                        <div class="bg-blue-50 p-2 rounded-full mr-3">
                                            <i class="fas fa-globe text-cyan-500"></i>
                                        </div>
                                        <div>
                                            <p class="text-sm text-gray-500">Sitio web</p>
                                            <a href="{{ $animal->fundacion->sitio_web }}" target="_blank" class="text-blue-700 hover:text-cyan-600 font-medium">
                                                {{ parse_url($animal->fundacion->sitio_web, PHP_URL_HOST) }}
                                            </a>
                                        </div>
                                    </div>
                                    @endif
                                    
                                    <!-- Social Media -->
                                    @if($animal->fundacion->facebook || $animal->fundacion->instagram)
                                    <div class="flex items-center pt-2">
                                        <div class="bg-blue-50 p-2 rounded-full mr-3">
                                            <i class="fas fa-share-alt text-cyan-500"></i>
                                        </div>
                                        <div class="flex space-x-3">
                                            @if($animal->fundacion->facebook)
                                            <a href="{{ $animal->fundacion->facebook }}" target="_blank" class="text-blue-600 hover:text-blue-800 transition-colors">
                                                <i class="fab fa-facebook-f text-xl"></i>
                                            </a>
                                            @endif
                                            @if($animal->fundacion->instagram)
                                            <a href="{{ $animal->fundacion->instagram }}" target="_blank" class="text-pink-600 hover:text-pink-800 transition-colors">
                                                <i class="fab fa-instagram text-xl"></i>
                                            </a>
                                            @endif
                                            @if($animal->fundacion->twitter)
                                            <a href="{{ $animal->fundacion->twitter }}" target="_blank" class="text-blue-400 hover:text-blue-600 transition-colors">
                                                <i class="fab fa-twitter text-xl"></i>
                                            </a>
                                            @endif
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Action Buttons - More prominent and better organized -->
                    <div class="mt-auto space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Adoption Button -->
                            <a href="{{ route('adopcion.form', $animal->id) }}" 
                               class="flex items-center justify-center px-6 py-3 border border-transparent text-base font-bold rounded-full shadow-sm text-white bg-gradient-to-r from-blue-700 to-cyan-500 hover:from-blue-800 hover:to-cyan-600 transition-all duration-200 group">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 group-hover:animate-bounce" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd" />
                                </svg>
                                Solicitar Adopción
                            </a>

                            <!-- Contact Button -->
                            <button 
                                id="contactBtn"
                                class="flex items-center justify-center px-6 py-3 border border-transparent text-base font-bold rounded-full shadow-sm text-white bg-gradient-to-r from-purple-600 to-blue-500 hover:from-purple-700 hover:to-blue-600 transition-all duration-200 group"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 group-hover:animate-pulse" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                                    <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                                </svg>
                                Contactar
                            </button>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @if($animal->fundacion)
                            <!-- Shelter Button -->
                            <a href="{{ route('publico.fundacion', $animal->fundacion->id) }}" 
                               class="flex items-center justify-center px-6 py-3 border border-transparent text-base font-bold rounded-full shadow-sm text-white bg-gradient-to-r from-cyan-500 to-blue-700 hover:from-cyan-600 hover:to-blue-800 transition-all duration-200 group">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd" />
                                </svg>
                                Conoce el Refugio
                            </a>
                            @endif

                            <!-- More Animals Button -->
                            <a href="{{ route('publico.buscar') }}" 
                               class="flex items-center justify-center px-6 py-3 border-2 border-cyan-400 text-base font-bold rounded-full text-cyan-600 hover:bg-cyan-50 transition-all duration-200 group">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                                </svg>
                                Ver Más Animales
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Related Animals Section - Improved with carousel option -->
        @if(isset($relatedAnimals) && $relatedAnimals->count() > 0)
        <section class="mt-16">
            <h2 class="text-3xl font-extrabold text-blue-900 mb-8 text-center relative">
                <span class="relative z-10 px-4 bg-gradient-to-r from-blue-50 to-cyan-50">
                    Otros amigos que buscan hogar
                </span>
                <span class="absolute top-1/2 left-0 right-0 h-0.5 bg-gradient-to-r from-transparent via-cyan-300 to-transparent -z-0"></span>
            </h2>
            
            <div class="relative">
                <!-- Carousel Navigation (for mobile) -->
                <button class="md:hidden absolute left-0 top-1/2 transform -translate-y-1/2 z-10 bg-white rounded-full p-2 shadow-md ml-2 focus:outline-none" id="prevAnimal">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-cyan-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8 overflow-x-auto md:overflow-visible" id="relatedAnimalsContainer">
                    @foreach($relatedAnimals as $relatedAnimal)
                    <div class="group bg-white rounded-2xl shadow-lg overflow-hidden border border-white/20 hover:shadow-xl hover:border-cyan-300 transition-all duration-300 transform hover:-translate-y-1 min-w-[280px]">
                        <div class="relative h-48 overflow-hidden">
                            <img src="{{ $relatedAnimal->imagen ? asset('storage/' . $relatedAnimal->imagen) : asset('images/animal-default.jpg') }}" 
                                 alt="{{ $relatedAnimal->nombre }}" 
                                 class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                            <div class="absolute top-4 left-4">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-bold bg-white/90 text-blue-900 backdrop-blur-sm shadow">
                                    {{ ucfirst($relatedAnimal->tipo) }}
                                </span>
                            </div>
                        </div>
                        <div class="p-6">
                            <h3 class="text-xl font-bold text-blue-900 mb-1">{{ $relatedAnimal->nombre }}</h3>
                            <div class="flex items-center space-x-3 text-sm text-blue-700 mb-3">
                                <span>{{ $relatedAnimal->edad }} {{ $relatedAnimal->tipo_edad === 'anios' ? 'años' : 'meses' }}</span>
                                <span>•</span>
                                <span>{{ ucfirst($relatedAnimal->genero) }}</span>
                            </div>
                            <a href="{{ route('publico.animal', $relatedAnimal->id) }}" 
                               class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-full shadow-sm text-white bg-gradient-to-r from-blue-600 to-cyan-500 hover:from-blue-700 hover:to-cyan-600 transition-all duration-200 group">
                                Conocer más
                                <svg xmlns="http://www.w3.org/2000/svg" class="ml-2 -mr-1 h-4 w-4 transform group-hover:translate-x-1 transition-transform" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L12.586 11H5a1 1 0 110-2h7.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
                
                <button class="md:hidden absolute right-0 top-1/2 transform -translate-y-1/2 z-10 bg-white rounded-full p-2 shadow-md mr-2 focus:outline-none" id="nextAnimal">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-cyan-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>
            </div>
            
            <div class="text-center mt-8">
                <a href="{{ route('publico.buscar') }}" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-full shadow-sm text-white bg-gradient-to-r from-blue-600 to-cyan-500 hover:from-blue-700 hover:to-cyan-600 transition-all duration-200">
                    Ver todos los animales disponibles
                </a>
            </div>
        </section>
        @endif
    </div>
</div>

<!-- Contact Modal -->
<div id="contactModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>
        
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        
        <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                        <h3 class="text-2xl leading-6 font-bold text-blue-900 mb-4" id="modalTitle">
                            Contactar sobre {{ $animal->nombre }}
                        </h3>
                        
                        <div class="mt-2">
                            @if($animal->fundacion)
                            <div class="space-y-4">
                                <div>
                                    <p class="text-sm text-gray-500 mb-1">Puedes contactar al refugio directamente:</p>
                                    <div class="bg-blue-50 p-4 rounded-lg">
                                        <p class="font-medium text-blue-800">{{ $animal->fundacion->nombre }}</p>
                                        @if($animal->fundacion->telefono)
                                        <p class="mt-2">
                                            <a href="tel:{{ $animal->fundacion->telefono }}" class="text-cyan-600 hover:text-cyan-800 flex items-center">
                                                <i class="fas fa-phone-alt mr-2"></i>
                                                {{ $animal->fundacion->telefono }}
                                            </a>
                                        </p>
                                        @endif
                                        @if($animal->fundacion->email)
                                        <p class="mt-1">
                                            <a href="mailto:{{ $animal->fundacion->email }}" class="text-cyan-600 hover:text-cyan-800 flex items-center">
                                                <i class="fas fa-envelope mr-2"></i>
                                                {{ $animal->fundacion->email }}
                                            </a>
                                        </p>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="border-t border-gray-200 pt-4">
                                    <p class="text-sm text-gray-500 mb-3">O envíanos un mensaje y nos pondremos en contacto contigo:</p>
                                    <form id="contactForm">
                                        @csrf
                                        <input type="hidden" name="animal_id" value="{{ $animal->id }}">
                                        <div class="mb-4">
                                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nombre</label>
                                            <input type="text" name="name" id="name" required class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-cyan-500 focus:border-cyan-500">
                                        </div>
                                        <div class="mb-4">
                                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Correo electrónico</label>
                                            <input type="email" name="email" id="email" required class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-cyan-500 focus:border-cyan-500">
                                        </div>
                                        <div class="mb-4">
                                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Teléfono (opcional)</label>
                                            <input type="tel" name="phone" id="phone" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-cyan-500 focus:border-cyan-500">
                                        </div>
                                        <div class="mb-4">
                                            <label for="message" class="block text-sm font-medium text-gray-700 mb-1">Mensaje</label>
                                            <textarea name="message" id="message" rows="4" required class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-cyan-500 focus:border-cyan-500">Estoy interesado en {{ $animal->nombre }} y me gustaría obtener más información.</textarea>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            @else
                            <div>
                                <p class="text-sm text-gray-500 mb-3">Por favor, completa el siguiente formulario y nos pondremos en contacto contigo:</p>
                                <form id="contactForm">
                                    @csrf
                                    <input type="hidden" name="animal_id" value="{{ $animal->id }}">
                                    <div class="mb-4">
                                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nombre</label>
                                        <input type="text" name="name" id="name" required class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-cyan-500 focus:border-cyan-500">
                                    </div>
                                    <div class="mb-4">
                                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Correo electrónico</label>
                                        <input type="email" name="email" id="email" required class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-cyan-500 focus:border-cyan-500">
                                    </div>
                                    <div class="mb-4">
                                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Teléfono (opcional)</label>
                                        <input type="tel" name="phone" id="phone" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-cyan-500 focus:border-cyan-500">
                                    </div>
                                    <div class="mb-4">
                                        <label for="message" class="block text-sm font-medium text-gray-700 mb-1">Mensaje</label>
                                        <textarea name="message" id="message" rows="4" required class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-cyan-500 focus:border-cyan-500">Estoy interesado en {{ $animal->nombre }} y me gustaría obtener más información.</textarea>
                                    </div>
                                </form>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" id="submitContactForm" class="w-full inline-flex justify-center rounded-full border border-transparent shadow-sm px-4 py-2 bg-gradient-to-r from-blue-600 to-cyan-500 text-base font-bold text-white hover:from-blue-700 hover:to-cyan-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-cyan-500 sm:ml-3 sm:w-auto sm:text-sm transition-all duration-200">
                    Enviar mensaje
                </button>
                <button type="button" id="closeModal" class="mt-3 w-full inline-flex justify-center rounded-full border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-bold text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-cyan-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-all duration-200">
                    Cancelar
                </button>
            </div>
        </div>
    </div>
</div>

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" 
      integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tF/miZyoHS5obTRR9BMY=" 
      crossorigin=""/>
<style>
    #animal-map {
        border-radius: 0.75rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }
    .leaflet-container {
        height: 100%;
        width: 100%;
        border-radius: 0.75rem;
    }
    .line-clamp-4 {
        display: -webkit-box;
        -webkit-line-clamp: 4;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .prose p {
        margin-bottom: 0.5em;
    }
    #relatedAnimalsContainer {
        scrollbar-width: none; /* Firefox */
        -ms-overflow-style: none; /* IE and Edge */
    }
    #relatedAnimalsContainer::-webkit-scrollbar {
        display: none; /* Chrome, Safari, Opera */
    }
</style>
@endpush

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
        crossorigin=""></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize map if coordinates exist
        @if($animal->latitud && $animal->longitud)
        var lat = {{ $animal->latitud }};
        var lng = {{ $animal->longitud }};
        
        var map = L.map('animal-map').setView([lat, lng], 13);
        
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
            maxZoom: 18,
        }).addTo(map);
        
        var customIcon = L.icon({
            iconUrl: '{{ asset("img/paw-map-marker.png") }}',
            iconSize: [32, 32],
            iconAnchor: [16, 32],
            popupAnchor: [0, -32]
        });
        
        var marker = L.marker([lat, lng], {icon: customIcon}).addTo(map);
        marker.bindPopup("<b>{{ $animal->nombre }}</b><br>Ubicación actual").openPopup();
        
        // Adjust map size after load
        setTimeout(function() {
            map.invalidateSize();
        }, 100);
        @endif
        
        // Read more/less functionality for description
        const description = document.getElementById('animalDescription');
        const readMoreBtn = document.getElementById('readMoreBtn');
        
        if (description && readMoreBtn) {
            const fullText = description.innerHTML;
            const truncatedText = fullText.substring(0, 200) + (fullText.length > 200 ? '...' : '');
            
            if (fullText.length > 200) {
                description.innerHTML = truncatedText;
                readMoreBtn.style.display = 'inline-block';
                
                readMoreBtn.addEventListener('click', function() {
                    if (description.classList.contains('line-clamp-4')) {
                        description.innerHTML = fullText;
                        description.classList.remove('line-clamp-4');
                        readMoreBtn.textContent = 'Leer menos';
                    } else {
                        description.innerHTML = truncatedText;
                        description.classList.add('line-clamp-4');
                        readMoreBtn.textContent = 'Leer más';
                    }
                });
            } else {
                readMoreBtn.style.display = 'none';
            }
        }
        
        // Contact modal functionality
        const contactBtn = document.getElementById('contactBtn');
        const contactModal = document.getElementById('contactModal');
        const closeModal = document.getElementById('closeModal');
        const submitContactForm = document.getElementById('submitContactForm');
        
        if (contactBtn && contactModal) {
            contactBtn.addEventListener('click', function() {
                contactModal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            });
            
            closeModal.addEventListener('click', function() {
                contactModal.classList.add('hidden');
                document.body.style.overflow = 'auto';
            });
            
            // Close modal when clicking outside
            contactModal.addEventListener('click', function(e) {
                if (e.target === contactModal) {
                    contactModal.classList.add('hidden');
                    document.body.style.overflow = 'auto';
                }
            });
            
            // Submit contact form
            submitContactForm.addEventListener('click', function() {
                const form = document.getElementById('contactForm');
                const formData = new FormData(form);
                
                fetch('{{ route("publico.contactar") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Mensaje enviado con éxito. Nos pondremos en contacto contigo pronto.');
                        contactModal.classList.add('hidden');
                        document.body.style.overflow = 'auto';
                    } else {
                        alert('Hubo un error al enviar el mensaje. Por favor, inténtalo de nuevo.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Hubo un error al enviar el mensaje. Por favor, inténtalo de nuevo.');
                });
            });
        }
        
        // Favorite button functionality
        const favoriteBtn = document.getElementById('favoriteBtn');
        if (favoriteBtn) {
            favoriteBtn.addEventListener('click', function() {
                const animalId = this.getAttribute('data-animal-id');
                const icon = this.querySelector('i');
                
                fetch('{{ route("publico.favorito") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ animal_id: animalId })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'added') {
                        icon.classList.remove('far', 'text-gray-300');
                        icon.classList.add('fas', 'text-red-500');
                        // Optional: show a toast notification
                    } else if (data.status === 'removed') {
                        icon.classList.remove('fas', 'text-red-500');
                        icon.classList.add('far', 'text-gray-300');
                        // Optional: show a toast notification
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
            });
        }
        
        // Related animals carousel for mobile
        const prevBtn = document.getElementById('prevAnimal');
        const nextBtn = document.getElementById('nextAnimal');
        const animalsContainer = document.getElementById('relatedAnimalsContainer');
        
        if (prevBtn && nextBtn && animalsContainer) {
            const scrollAmount = 300; // Adjust based on card width + gap
            
            prevBtn.addEventListener('click', () => {
                animalsContainer.scrollBy({ left: -scrollAmount, behavior: 'smooth' });
            });
            
            nextBtn.addEventListener('click', () => {
                animalsContainer.scrollBy({ left: scrollAmount, behavior: 'smooth' });
            });
        }
        
        // Change main image function for image gallery
        window.changeMainImage = function(newSrc) {
            const mainImage = document.getElementById('mainAnimalImage');
            if (mainImage) {
                mainImage.src = newSrc;
            }
        };
    });
</script>
@endpush
@endsection