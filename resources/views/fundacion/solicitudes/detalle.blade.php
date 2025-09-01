@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-cyan-100 py-12 px-4 lg:px-0">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-3xl shadow-xl p-8 border-2 border-cyan-200 mb-10">
            <h1 class="text-3xl font-extrabold text-blue-900 mb-8 text-center">Detalle de Solicitud de Adopción</h1>
            
            <!-- Estado de la solicitud -->
            @php
                $estadoClases = [
                    'pendiente' => 'bg-yellow-100 text-yellow-800',
                    'en_revision' => 'bg-blue-100 text-blue-800',
                    'aprobada' => 'bg-green-100 text-green-800',
                    'rechazada' => 'bg-red-100 text-red-800'
                ][$solicitud->estado] ?? 'bg-gray-100 text-gray-800';
            @endphp
            <div class="mb-8 text-center">
                <span class="px-4 py-2 rounded-full text-sm font-semibold {{ $estadoClases }}">
                    {{ ucfirst(str_replace('_', ' ', $solicitud->estado)) }}
                </span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Datos del Solicitante -->
                <div class="bg-gray-50 p-6 rounded-xl">
                    <h2 class="text-xl font-bold text-cyan-700 mb-4 border-b pb-2">
                        <i class="fas fa-user-circle mr-2"></i>Datos del Solicitante
                    </h2>
                    <ul class="space-y-3">
                        <li class="flex items-start">
                            <span class="font-medium text-gray-700 w-36">Nombre:</span>
                            <span class="text-gray-800">{{ $solicitud->nombre_solicitante }}</span>
                        </li>
                        <li class="flex items-start">
                            <span class="font-medium text-gray-700 w-36">Identificación:</span>
                            <span class="text-gray-800">{{ $solicitud->identificacion ?? 'No especificada' }}</span>
                        </li>
                        <li class="flex items-start">
                            <span class="font-medium text-gray-700 w-36">Email:</span>
                            <a href="mailto:{{ $solicitud->email_solicitante }}" class="text-blue-600 hover:underline">
                                {{ $solicitud->email_solicitante }}
                            </a>
                        </li>
                        <li class="flex items-start">
                            <span class="font-medium text-gray-700 w-36">Teléfono:</span>
                            <a href="tel:{{ $solicitud->telefono_solicitante }}" class="text-blue-600 hover:underline">
                                {{ $solicitud->telefono_solicitante }}
                            </a>
                        </li>
                        <li class="flex items-start">
                            <span class="font-medium text-gray-700 w-36">Edad:</span>
                            <span class="text-gray-800">{{ $solicitud->edad_solicitante }} años</span>
                        </li>
                        <li class="flex items-start">
                            <span class="font-medium text-gray-700 w-36">Ocupación:</span>
                            <span class="text-gray-800">{{ $solicitud->ocupacion ?? 'No especificada' }}</span>
                        </li>
                    </ul>
                </div>

                <!-- Información del Hogar -->
                <div class="bg-gray-50 p-6 rounded-xl">
                    <h2 class="text-xl font-bold text-cyan-700 mb-4 border-b pb-2">
                        <i class="fas fa-home mr-2"></i>Información del Hogar
                    </h2>
                    <ul class="space-y-3">
                        <li class="flex items-start">
                            <span class="font-medium text-gray-700 w-36">Dirección:</span>
                            <span class="text-gray-800">{{ $solicitud->direccion_solicitante }}</span>
                        </li>
                        <li class="flex items-start">
                            <span class="font-medium text-gray-700 w-36">Barrio:</span>
                            <span class="text-gray-800">{{ $solicitud->barrio ?? 'No especificado' }}</span>
                        </li>
                        <li class="flex items-start">
                            <span class="font-medium text-gray-700 w-36">Referencia:</span>
                            <span class="text-gray-800">{{ $solicitud->referencia ?? 'No especificada' }}</span>
                        </li>
                        <li class="flex items-start">
                            <span class="font-medium text-gray-700 w-36">Tipo de vivienda:</span>
                            <span class="text-gray-800">{{ ucfirst($solicitud->tipo_vivienda) }}</span>
                        </li>
                        <li class="flex items-start">
                            <span class="font-medium text-gray-700 w-36">¿Tiene patio/jardín?:</span>
                            <span class="text-gray-800">{{ $solicitud->tiene_patio == 'si' ? 'Sí' : 'No' }}</span>
                        </li>
                        <li class="flex items-start">
                            <span class="font-medium text-gray-700 w-36">Otras mascotas:</span>
                            <span class="text-gray-800">{{ $solicitud->otros_mascotas == 'si' ? 'Sí' : 'No' }}</span>
                        </li>
                        @if($solicitud->otros_mascotas == 'si' && !empty($solicitud->descripcion_mascotas))
                        <li class="mt-2 p-3 bg-yellow-50 rounded-lg border border-yellow-100">
                            <span class="font-medium text-yellow-700">Descripción de otras mascotas:</span>
                            <p class="text-yellow-800 mt-1">{{ $solicitud->descripcion_mascotas }}</p>
                        </li>
                        @endif
                    </ul>
                </div>

                <!-- Experiencia y Motivación -->
                <div class="bg-gray-50 p-6 rounded-xl">
                    <h2 class="text-xl font-bold text-cyan-700 mb-4 border-b pb-2">
                        <i class="fas fa-paw mr-2"></i>Experiencia y Motivación
                    </h2>
                    <ul class="space-y-3">
                        <li class="flex items-start">
                            <span class="font-medium text-gray-700 w-36">Experiencia con mascotas:</span>
                            <span class="text-gray-800">
                                @switch($solicitud->experiencia_mascotas)
                                    @case('nada')
                                        Ninguna experiencia
                                        @break
                                    @case('poca')
                                        Poca experiencia
                                        @break
                                    @case('moderada')
                                        Experiencia moderada
                                        @break
                                    @case('mucha')
                                        Mucha experiencia
                                        @break
                                    @default
                                        {{ ucfirst($solicitud->experiencia_mascotas) }}
                                @endswitch
                            </span>
                        </li>
                        <li class="flex items-start">
                            <span class="font-medium text-gray-700 w-36">Tiempo disponible:</span>
                            <span class="text-gray-800">
                                @switch($solicitud->tiempo_disponible)
                                    @case('poco')
                                        Poco tiempo
                                        @break
                                    @case('moderado')
                                        Tiempo moderado
                                        @break
                                    @case('mucho')
                                        Mucho tiempo
                                        @break
                                    @default
                                        {{ ucfirst($solicitud->tiempo_disponible) }}
                                @endswitch
                            </span>
                        </li>
                        <li class="flex items-start">
                            <span class="font-medium text-gray-700 w-36">Bienestar de la mascota:</span>
                            <span class="text-gray-800">{{ $solicitud->bienestar_mascota ?? 'No especificado' }}</span>
                        </li>
                        <li class="flex items-start">
                            <span class="font-medium text-gray-700 w-36">Conocimientos de cuidados:</span>
                            <span class="text-gray-800">{{ $solicitud->conocimiento_cuidados ?? 'No especificados' }}</span>
                        </li>
                        <li class="flex items-start">
                            <span class="font-medium text-gray-700 w-36">Compromiso de esterilización:</span>
                            <span class="text-gray-800">
                                @if($solicitud->compromiso_esterilizacion == 'si')
                                    Sí, está dispuesto/a a esterilizar
                                @elseif($solicitud->compromiso_esterilizacion == 'no')
                                    No está dispuesto/a a esterilizar
                                @else
                                    Prefiere consultar
                                @endif
                            </span>
                        </li>
                    </ul>
                </div>

                <!-- Preguntas de Seguridad -->
                @if($solicitud->preguntas_seguridad)
                <div class="bg-gray-50 p-6 rounded-xl">
                    <h2 class="text-xl font-bold text-cyan-700 mb-4 border-b pb-2">
                        <i class="fas fa-shield-alt mr-2"></i>Preguntas de Seguridad
                    </h2>
                    <ul class="space-y-4">
                        @if(isset($solicitud->preguntas_seguridad['experiencia']))
                        <li>
                            <p class="font-medium text-gray-700">¿Qué harías si el animal tiene problemas de comportamiento?</p>
                            <p class="text-gray-800 mt-1">{{ $solicitud->preguntas_seguridad['experiencia'] }}</p>
                        </li>
                        @endif
                        @if(isset($solicitud->preguntas_seguridad['emergencias']))
                        <li>
                            <p class="font-medium text-gray-700">¿Cómo manejarías una emergencia veterinaria?</p>
                            <p class="text-gray-800 mt-1">{{ $solicitud->preguntas_seguridad['emergencias'] }}</p>
                        </li>
                        @endif
                        @if(isset($solicitud->preguntas_seguridad['perdida']))
                        <li>
                            <p class="font-medium text-gray-700">¿Qué harías si el animal se pierde?</p>
                            <p class="text-gray-800 mt-1">{{ $solicitud->preguntas_seguridad['perdida'] }}</p>
                        </li>
                        @endif
                    </ul>
                </div>
                @endif

                <!-- Motivo de Adopción -->
                <div class="md:col-span-2 bg-gray-50 p-6 rounded-xl">
                    <h2 class="text-xl font-bold text-cyan-700 mb-4 border-b pb-2">
                        <i class="fas fa-heart mr-2"></i>Motivo de la Adopción
                    </h2>
                    <div class="prose max-w-none">
                        <p class="text-gray-800">{{ $solicitud->motivo_adopcion }}</p>
                    </div>
                </div>

                <!-- Mensaje Adicional -->
                @if($solicitud->mensaje)
                <div class="md:col-span-2 bg-blue-50 p-6 rounded-xl border border-blue-100">
                    <h2 class="text-xl font-bold text-blue-700 mb-4">
                        <i class="fas fa-comment-alt mr-2"></i>Mensaje Adicional
                    </h2>
                    <div class="prose max-w-none">
                        <p class="text-blue-800">{{ $solicitud->mensaje }}</p>
                    </div>
                </div>
                @endif

                <!-- Información del Animal -->
                <div class="md:col-span-2 bg-indigo-50 p-6 rounded-xl border border-indigo-100">
                    <h2 class="text-xl font-bold text-indigo-700 mb-4">
                        <i class="fas fa-paw mr-2"></i>Animal Solicitado
                    </h2>
                    <div class="flex flex-col md:flex-row items-start gap-6">
                        @if($solicitud->animal->imagen)
                        <div class="w-full md:w-48 h-48 rounded-lg overflow-hidden flex-shrink-0">
                            <img src="{{ asset('storage/' . $solicitud->animal->imagen) }}" 
                                 alt="{{ $solicitud->animal->nombre }}"
                                 class="w-full h-full object-cover">
                        </div>
                        @endif
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 flex-grow">
                            <div>
                                <h3 class="text-lg font-semibold text-indigo-800">{{ $solicitud->animal->nombre }}</h3>
                                <p class="text-indigo-700">{{ ucfirst($solicitud->animal->tipo) }} • {{ $solicitud->animal->raza ?? 'Raza mixta' }}</p>
                                <p class="text-indigo-700">{{ $solicitud->animal->edad }} años • {{ ucfirst($solicitud->animal->genero) }}</p>
                            </div>
                            <div class="space-y-2">
                                <p class="text-indigo-800"><span class="font-medium">Tamaño:</span> {{ ucfirst($solicitud->animal->tamano) }}</p>
                                <p class="text-indigo-800"><span class="font-medium">Nivel de energía:</span> {{ ucfirst($solicitud->animal->nivel_energia) }}</p>
                                <p class="text-indigo-800"><span class="font-medium">Nivel de sociabilidad:</span> {{ ucfirst($solicitud->animal->nivel_sociabilidad) }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Acciones -->
            <div class="flex flex-col sm:flex-row justify-between items-center mt-10 pt-6 border-t border-gray-200">
                <a href="{{ route('fundacion.solicitudes') }}" 
                   class="flex items-center text-blue-700 hover:text-blue-900 font-medium mb-4 sm:mb-0">
                    <i class="fas fa-arrow-left mr-2"></i> Volver al listado
                </a>
                
                @if(in_array($solicitud->estado, ['pendiente', 'en_revision']))
                <div class="flex flex-col sm:flex-row gap-4">
                    @if($solicitud->estado == 'pendiente')
                    <form action="{{ route('fundacion.solicitudes.estado', ['id' => $solicitud->id, 'estado' => 'en_revision']) }}" 
                          method="POST" 
                          class="w-full sm:w-auto">
                        @csrf
                        @method('PATCH')
                        <button type="submit" 
                                class="w-full flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-semibold transition-colors">
                            <i class="fas fa-search"></i> En Revisión
                        </button>
                    </form>
                    @endif
                    
                    <form action="{{ route('fundacion.solicitudes.estado', ['id' => $solicitud->id, 'estado' => 'aprobada']) }}" 
                          method="POST" 
                          class="w-full sm:w-auto">
                        @csrf
                        @method('PATCH')
                        <button type="submit" 
                                class="w-full flex items-center justify-center gap-2 bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg font-semibold transition-colors">
                            <i class="fas fa-check"></i> Aprobar
                        </button>
                    </form>
                    
                    <form action="{{ route('fundacion.solicitudes.estado', ['id' => $solicitud->id, 'estado' => 'rechazada']) }}" 
                          method="POST" 
                          class="w-full sm:w-auto">
                        @csrf
                        @method('PATCH')
                        <button type="submit" 
                                class="w-full flex items-center justify-center gap-2 bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-lg font-semibold transition-colors">
                            <i class="fas fa-times"></i> Rechazar
                        </button>
                    </form>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .prose {
        max-width: 100%;
    }
    .prose p {
        margin-bottom: 0.75em;
        line-height: 1.6;
    }
    .prose p:last-child {
        margin-bottom: 0;
    }
</style>
@endpush

@push('scripts')
<script>
    // Scripts adicionales si son necesarios
    document.addEventListener('DOMContentLoaded', function() {
        // Aquí puedes agregar interactividad si es necesario
    });
</script>
@endpush
@endsection