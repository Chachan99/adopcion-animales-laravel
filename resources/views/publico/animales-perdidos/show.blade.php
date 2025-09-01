@extends('layouts.app')

@section('title', $mascota->nombre . ' - Mascota Perdida - ' . config('app.name'))

@section('content')
<div class="bg-gradient-to-b from-gray-50 to-white py-10">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Botón de volver -->
        <div class="mb-6">
            <a href="{{ route('animales-perdidos.index') }}" class="inline-flex items-center text-sm font-medium text-indigo-600 hover:text-indigo-500 transition-colors duration-200">
                <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                </svg>
                Volver al listado
            </a>
        </div>

        <!-- Tarjeta principal -->
        <div class="bg-white shadow-xl rounded-xl overflow-hidden">
            <!-- Header con estado -->
            <div class="px-6 py-5 bg-indigo-600 border-b border-indigo-500">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                    <div class="flex items-center">
                        <h1 class="text-2xl font-bold text-white">{{ $mascota->nombre }}</h1>
                        <span class="ml-3 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $mascota->estado === 'perdido' ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                            {{ ucfirst($mascota->estado) }}
                        </span>
                    </div>
                    <div class="mt-3 sm:mt-0">
                        <p class="text-sm text-indigo-100">
                            <svg class="inline-block h-4 w-4 mr-1 -mt-0.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                            </svg>
                            Reportado el {{ $mascota->created_at->format('d/m/Y') }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Contenido principal -->
            <div class="px-6 py-6 sm:p-8">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Columna izquierda - Imagen y acciones -->
                    <div class="lg:col-span-1 space-y-6">
                        <!-- Imagen principal -->
                        <div class="relative rounded-xl overflow-hidden shadow-md">
                            <img src="{{ $mascota->imagen_url ?? asset('img/default-pet-large.jpg') }}" 
                                 alt="{{ $mascota->nombre }}"
                                 class="w-full h-auto object-cover transition-transform duration-300 hover:scale-105"
                                 onerror="this.onerror=null; this.src='{{ asset('img/default-pet-large.jpg') }}'"
                                 loading="lazy"
                            >
                            @if($mascota->recompensa)
                                <div class="absolute top-3 right-3">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800 shadow-sm">
                                        <svg class="-ml-1 mr-1.5 h-4 w-4 text-yellow-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                        Recompensa: {{ $mascota->recompensa }}
                                    </span>
                                </div>
                            @endif
                        </div>

                        <!-- Acciones para dueño/administrador -->
                        @if(auth()->check() && ($mascota->usuario_id === auth()->id() || $mascota->fundacion_id === auth()->id() || auth()->user()->rol === 'admin'))
                            <div class="bg-gray-50 p-5 rounded-lg border border-gray-200">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                    <svg class="h-5 w-5 text-gray-500 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd" />
                                    </svg>
                                    Acciones
                                </h3>
                                
                                @if($mascota->estado === 'perdido')
                                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-md mb-4">
                                        <div class="flex">
                                            <div class="flex-shrink-0">
                                                <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                                </svg>
                                            </div>
                                            <div class="ml-3">
                                                <p class="text-sm text-yellow-700">
                                                    ¿Ya encontraste a tu mascota? Marca como encontrada para notificar a la comunidad.
                                                </p>
                                                <div class="mt-3">
                                                    <form action="{{ route('animales-perdidos.marcar-encontrado', $mascota->id) }}" method="POST" class="inline-block">
                                                        @csrf
                                                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200">
                                                            <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                                            </svg>
                                                            Marcar como Encontrado
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="rounded-md bg-green-50 p-4 mb-4 border border-green-100">
                                        <div class="flex">
                                            <div class="flex-shrink-0">
                                                <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                                </svg>
                                            </div>
                                            <div class="ml-3">
                                                <h3 class="text-sm font-medium text-green-800">
                                                    ¡Mascota encontrada!
                                                </h3>
                                                <div class="mt-1 text-sm text-green-700">
                                                    <p>Encontrada el {{ $mascota->fecha_encontrado->format('d/m/Y \a \l\a\s H:i') }}.</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                
                                @if($mascota->usuario_id === auth()->id() || $mascota->fundacion_id === auth()->id())
                                    <div class="pt-4 border-t border-gray-200 mt-4">
                                        <a href="{{ route('animales-perdidos.edit', $mascota->id) }}" class="inline-flex items-center text-sm font-medium text-indigo-600 hover:text-indigo-500 transition-colors duration-200">
                                            <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                            </svg>
                                            Editar publicación
                                        </a>
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>

                    <!-- Columna derecha - Detalles -->
                    <div class="lg:col-span-2 space-y-6">
                        <!-- Descripción -->
                        <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
                            <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                                <svg class="h-5 w-5 text-gray-500 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                </svg>
                                Descripción
                            </h2>
                            <div class="prose max-w-none text-gray-700">
                                {!! nl2br(e($mascota->descripcion)) !!}
                            </div>
                        </div>

                        <!-- Detalles de la mascota -->
                        <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
                            <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                                <svg class="h-5 w-5 text-gray-500 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                    <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                                </svg>
                                Detalles de la mascota
                            </h2>
                            <dl class="grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-2">
                                <div class="sm:col-span-1">
                                    <dt class="text-sm font-medium text-gray-500">Tipo</dt>
                                    <dd class="mt-1 text-sm text-gray-900 capitalize">{{ $mascota->tipo }}</dd>
                                </div>
                                @if($mascota->raza)
                                    <div class="sm:col-span-1">
                                        <dt class="text-sm font-medium text-gray-500">Raza</dt>
                                        <dd class="mt-1 text-sm text-gray-900 capitalize">{{ $mascota->raza }}</dd>
                                    </div>
                                @endif
                                <div class="sm:col-span-1">
                                    <dt class="text-sm font-medium text-gray-500">Edad</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $mascota->edad }} {{ $mascota->tipo_edad }}</dd>
                                </div>
                                <div class="sm:col-span-1">
                                    <dt class="text-sm font-medium text-gray-500">Sexo</dt>
                                    <dd class="mt-1 text-sm text-gray-900 capitalize">{{ $mascota->sexo === 'macho' ? 'Macho' : 'Hembra' }}</dd>
                                </div>
                                <div class="sm:col-span-1">
                                    <dt class="text-sm font-medium text-gray-500">Tamaño</dt>
                                    <dd class="mt-1 text-sm text-gray-900 capitalize">{{ $mascota->tamaño ?? 'No especificado' }}</dd>
                                </div>
                                <div class="sm:col-span-1">
                                    <dt class="text-sm font-medium text-gray-500">Color</dt>
                                    <dd class="mt-1 text-sm text-gray-900 capitalize">{{ $mascota->color ?? 'No especificado' }}</dd>
                                </div>
                                <div class="sm:col-span-2">
                                    <dt class="text-sm font-medium text-gray-500">Fecha de desaparición</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $mascota->created_at->format('d/m/Y') }} ({{ $mascota->created_at->diffForHumans() }})</dd>
                                </div>
                            </dl>
                        </div>

                        <!-- Ubicación -->
                        <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
                            <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                                <svg class="h-5 w-5 text-gray-500 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                                </svg>
                                Última ubicación conocida
                            </h2>
                            <div class="space-y-4">
                                <div class="flex items-start">
                                    <svg class="h-5 w-5 text-gray-400 mr-2 mt-0.5 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                                    </svg>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ $mascota->ultima_ubicacion }}</p>
                                        @if($mascota->direccion)
                                            <p class="text-sm text-gray-500 mt-1">{{ $mascota->direccion }}</p>
                                        @endif
                                    </div>
                                </div>
                                
                                @if($mascota->latitud && $mascota->longitud)
                                    <div id="map" class="h-80 w-full rounded-lg border border-gray-200 shadow-sm overflow-hidden mt-4"></div>
                                    <p class="mt-2 text-xs text-gray-500">Ubicación aproximada donde se vio por última vez a la mascota.</p>
                                @else
                                    <div class="bg-gray-50 p-4 rounded-lg text-center">
                                        <svg class="mx-auto h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <p class="mt-2 text-sm text-gray-600">No se ha proporcionado ubicación exacta</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Información de contacto -->
                        <div class="bg-indigo-50 border border-indigo-100 rounded-xl p-6 shadow-sm">
                            <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                                <svg class="h-5 w-5 text-indigo-500 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z" />
                                </svg>
                                Información de Contacto
                            </h2>
                            <div class="space-y-5">
                                @if($mascota->telefono_contacto)
                                    <div class="flex items-start">
                                        <svg class="h-5 w-5 text-indigo-500 flex-shrink-0 mt-0.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z" />
                                        </svg>
                                        <div class="ml-3">
                                            <p class="text-sm font-medium text-gray-700">Teléfono de contacto</p>
                                            <a href="tel:{{ $mascota->telefono_contacto }}" class="text-base text-indigo-600 hover:text-indigo-500 flex items-center transition-colors duration-200">
                                                {{ $mascota->telefono_contacto }}
                                                <svg class="h-4 w-4 ml-1.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                                </svg>
                                            </a>
                                        </div>
                                    </div>
                                @endif

                                @if($mascota->email_contacto)
                                    <div class="flex items-start">
                                        <svg class="h-5 w-5 text-indigo-500 flex-shrink-0 mt-0.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                                            <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                                        </svg>
                                        <div class="ml-3">
                                            <p class="text-sm font-medium text-gray-700">Correo electrónico</p>
                                            <a href="mailto:{{ $mascota->email_contacto }}" class="text-base text-indigo-600 hover:text-indigo-500 flex items-center transition-colors duration-200">
                                                {{ $mascota->email_contacto }}
                                                <svg class="h-4 w-4 ml-1.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                                </svg>
                                            </a>
                                        </div>
                                    </div>
                                @endif

                                @if($mascota->fundacion)
                                    <div class="flex items-start">
                                        <svg class="h-5 w-5 text-indigo-500 flex-shrink-0 mt-0.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a1 1 0 110 2H4a1 1 0 110-2V4zm3 1h2v2H7V5zm2 4H7v2h2V9zm2-4h2v2h-2V5zm2 4h-2v2h2V9z" clip-rule="evenodd" />
                                        </svg>
                                        <div class="ml-3">
                                            <p class="text-sm font-medium text-gray-700">Publicado por</p>
                                            <div class="flex items-center">
                                                <p class="text-base text-gray-900">
                                                    {{ $mascota->fundacion->perfil->nombre ?? 'Fundación' }}
                                                    @if($mascota->usuario_id === $mascota->fundacion_id)
                                                        <span class="text-xs text-gray-500 ml-1">(Dueño original)</span>
                                                    @endif
                                                </p>
                                                @if($mascota->fundacion->perfil->verificado)
                                                    <svg class="h-4 w-4 text-blue-500 ml-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                                    </svg>
                                                @endif
                                            </div>
                                            @if($mascota->fundacion->perfil->telefono)
                                                <p class="text-sm text-gray-500 mt-1">{{ $mascota->fundacion->perfil->telefono }}</p>
                                            @endif
                                        </div>
                                    </div>
                                @elseif($mascota->usuario)
                                    <div class="flex items-start">
                                        <svg class="h-5 w-5 text-indigo-500 flex-shrink-0 mt-0.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                                        </svg>
                                        <div class="ml-3">
                                            <p class="text-sm font-medium text-gray-700">Publicado por</p>
                                            <p class="text-base text-gray-900">{{ $mascota->usuario->name }}</p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@if($mascota->latitud && $mascota->longitud)
    @push('scripts')
    <script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google.maps_api_key') }}&libraries=places&callback=initMap" async defer></script>
    <script>
        function initMap() {
            const location = { lat: {{ $mascota->latitud }}, lng: {{ $mascota->longitud }} };
            const map = new google.maps.Map(document.getElementById("map"), {
                zoom: 14,
                center: location,
                styles: [
                    {
                        featureType: "poi",
                        elementType: "labels",
                        stylers: [{ visibility: "off" }]
                    },
                    {
                        featureType: "transit",
                        elementType: "labels",
                        stylers: [{ visibility: "off" }]
                    }
                ],
                mapTypeControl: false,
                streetViewControl: false,
                fullscreenControl: true,
                zoomControl: true,
                clickableIcons: false
            });
            
            // Crear un marcador personalizado
            const marker = new google.maps.Marker({
                position: location,
                map: map,
                title: "{{ $mascota->nombre }}",
                animation: google.maps.Animation.DROP,
                icon: {
                    url: "{{ asset('img/map-marker-pet.png') }}",
                    scaledSize: new google.maps.Size(50, 50),
                    origin: new google.maps.Point(0, 0),
                    anchor: new google.maps.Point(25, 50)
                }
            });

            // Añadir un círculo para indicar el área aproximada
            new google.maps.Circle({
                strokeColor: '#3B82F6',
                strokeOpacity: 0.3,
                strokeWeight: 2,
                fillColor: '#BFDBFE',
                fillOpacity: 0.2,
                map: map,
                center: location,
                radius: 300 // 300 metros de radio
            });

            // Añadir un info window con más información
            const infoWindow = new google.maps.InfoWindow({
                content: `
                    <div class="p-2">
                        <h3 class="font-bold text-gray-900">{{ $mascota->nombre }}</h3>
                        <p class="text-sm text-gray-600">Visto por última vez el: {{ $mascota->created_at->format('d/m/Y') }}</p>
                        <p class="text-sm text-gray-600">{{ $mascota->ultima_ubicacion }}</p>
                    </div>
                `
            });

            // Mostrar el info window al hacer clic en el marcador
            marker.addListener('click', () => {
                infoWindow.open(map, marker);
            });

            // Ajustar el zoom para asegurar que el marcador sea visible
            const bounds = new google.maps.LatLngBounds();
            bounds.extend(location);
            map.fitBounds(bounds);
            
            // Evitar zoom excesivo
            const currentZoom = map.getZoom();
            if (currentZoom > 16) {
                map.setZoom(16);
            }
        }

        // Manejar errores de carga del mapa
        window.gm_authFailure = function() {
            const mapElement = document.getElementById('map');
            if (mapElement) {
                mapElement.innerHTML = `
                    <div class="bg-red-50 border-l-4 border-red-400 p-4 h-full flex flex-col items-center justify-center text-center">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-red-700">
                                    Error al cargar el mapa. Por favor, intente recargar la página o verifique su conexión a internet.
                                </p>
                            </div>
                        </div>
                    </div>
                `;
            }
        };
    </script>
    @endpush
@endif

@endsection