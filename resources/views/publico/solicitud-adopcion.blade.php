@extends('layouts.app')
@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-cyan-100 py-8 px-4 lg:px-0">
    <div class="max-w-3xl mx-auto">
        <!-- Breadcrumb -->
        <nav class="mb-8" aria-label="Navegación">
            <ol class="flex flex-wrap items-center gap-2 text-sm">
                <li><a href="{{ route('publico.index') }}" class="text-cyan-600 hover:text-cyan-800 hover:underline transition-colors">Inicio</a></li>
                <li><span class="text-gray-400">→</span></li>
                <li><a href="{{ route('publico.animal', $animal->id) }}" class="text-cyan-600 hover:text-cyan-800 hover:underline transition-colors">{{ $animal->nombre }}</a></li>
                <li><span class="text-gray-400">→</span></li>
                <li class="text-blue-900 font-semibold">Solicitar Adopción</li>
            </ol>
        </nav>

        <!-- Animal Info Card -->
        <div class="bg-white rounded-2xl shadow-lg p-6 mb-8 border-2 border-cyan-200 transition-all hover:shadow-xl">
            <div class="flex flex-col sm:flex-row items-center gap-4">
                <div class="relative">
                    <img src="{{ $animal->imagen ? asset('storage/' . $animal->imagen) : asset('img/defaults/animal-default.jpg') }}" 
                         alt="{{ $animal->nombre }}" 
                         class="w-24 h-24 sm:w-32 sm:h-32 rounded-xl object-cover border-2 border-cyan-200 shadow-sm">
                    <span class="absolute -bottom-2 -right-2 bg-cyan-100 text-cyan-800 text-xs font-bold px-2 py-1 rounded-full border border-cyan-300">
                        {{ strtoupper($animal->tipo) }}
                    </span>
                </div>
                <div class="text-center sm:text-left">
                    <h2 class="text-2xl font-bold text-blue-900">{{ $animal->nombre }}</h2>
                    <p class="text-cyan-700 font-semibold">{{ $animal->edad }} {{ $animal->tipo_edad === 'anios' ? 'años' : 'meses' }}</p>
                    @if($animal->fundacion)
                    <div class="mt-2 flex items-center justify-center sm:justify-start gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                        <span class="text-gray-600 text-sm">Fundación: {{ $animal->fundacion->nombre }}</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Solicitud Form -->
        <div class="bg-white rounded-2xl shadow-xl p-6 lg:p-8 border-2 border-cyan-200">
            <div class="text-center mb-8">
                <h1 class="text-3xl font-extrabold text-blue-900 mb-2">Solicitar Adopción</h1>
                <p class="text-cyan-700 max-w-lg mx-auto">Completa el formulario para solicitar la adopción de <span class="font-semibold text-blue-900">{{ $animal->nombre }}</span>. Revisaremos tu solicitud y nos pondremos en contacto contigo.</p>
                
                @guest
                    <div class="mt-4 bg-blue-50 border border-blue-200 rounded-lg p-4 max-w-lg mx-auto">
                        <div class="flex items-start">
                            <svg class="h-5 w-5 text-blue-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h2a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                            </svg>
                            <div class="ml-3">
                                <p class="text-blue-800 text-sm">
                                    <strong>Nota importante:</strong> Puedes enviar esta solicitud sin estar registrado. 
                                    Para hacer seguimiento de tus solicitudes, 
                                    <a href="{{ route('register') }}" class="text-cyan-600 hover:text-cyan-800 underline font-medium">regístrate</a> o 
                                    <a href="{{ route('login') }}" class="text-cyan-600 hover:text-cyan-800 underline font-medium">inicia sesión</a>.
                                </p>
                            </div>
                        </div>
                    </div>
                @endguest
            </div>

            @if($errors->any())
                <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-lg" role="alert">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">Hay {{ $errors->count() }} error(es) en tu solicitud</h3>
                            <div class="mt-2 text-sm text-red-700">
                                <ul class="list-disc pl-5 space-y-1">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <form action="{{ route('adopcion.store') }}" method="POST" class="space-y-8">
                @csrf
                <input type="hidden" name="animal_id" value="{{ $animal->id }}">

                <!-- Información Personal -->
                <div class="space-y-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-cyan-100 p-2 rounded-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-cyan-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <h3 class="ml-3 text-xl font-bold text-blue-900">Información Personal</h3>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="nombre" class="block text-sm font-medium text-gray-700 mb-1">Nombre Completo *</label>
                            <input type="text" id="nombre" name="nombre" value="{{ old('nombre', auth()->user()->nombre ?? '') }}" 
                                   class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-cyan-500 focus:ring-2 focus:ring-cyan-200 transition-colors"
                                   placeholder="Ej: María González" required>
                        </div>
                        
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                            <input type="email" id="email" name="email" value="{{ old('email', auth()->user()->email ?? '') }}" 
                                   class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-cyan-500 focus:ring-2 focus:ring-cyan-200 transition-colors"
                                   placeholder="tu@email.com" required>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="telefono" class="block text-sm font-medium text-gray-700 mb-1">Teléfono *</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500">+57</span>
                                </div>
                                <input type="tel" id="telefono" name="telefono" value="{{ old('telefono') }}" 
                                       class="pl-12 w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-cyan-500 focus:ring-2 focus:ring-cyan-200 transition-colors"
                                       placeholder="300 1234567" required>
                            </div>
                        </div>
                        
                        <div>
                            <label for="identificacion" class="block text-sm font-medium text-gray-700 mb-1">Documento de Identidad *</label>
                            <input type="text" id="identificacion" name="identificacion" value="{{ old('identificacion') }}" 
                                   class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-cyan-500 focus:ring-2 focus:ring-cyan-200 transition-colors"
                                   placeholder="Número de cédula o pasaporte" required>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="edad" class="block text-sm font-medium text-gray-700 mb-1">Edad *</label>
                            <div class="relative">
                                <input type="number" id="edad" name="edad" value="{{ old('edad') }}" min="18" max="100"
                                       class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-cyan-500 focus:ring-2 focus:ring-cyan-200 transition-colors"
                                       placeholder="25" required>
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500">años</span>
                                </div>
                            </div>
                        </div>
                        
                        <div>
                            <label for="ocupacion" class="block text-sm font-medium text-gray-700 mb-1">Ocupación *</label>
                            <input type="text" id="ocupacion" name="ocupacion" value="{{ old('ocupacion') }}" 
                                   class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-cyan-500 focus:ring-2 focus:ring-cyan-200 transition-colors"
                                   placeholder="Ej: Estudiante, Ingeniero, Comerciante" required>
                        </div>
                    </div>

                    <div>
                        <label for="direccion_solicitante" class="block text-sm font-medium text-gray-700 mb-1">Dirección Completa *</label>
                        <input type="text" id="direccion_solicitante" name="direccion_solicitante" value="{{ old('direccion_solicitante') }}" 
                               class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-cyan-500 focus:ring-2 focus:ring-cyan-200 transition-colors"
                               placeholder="Calle, número, ciudad, departamento" required>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="barrio" class="block text-sm font-medium text-gray-700 mb-1">Barrio/Sector *</label>
                            <input type="text" id="barrio" name="barrio" value="{{ old('barrio') }}" 
                                   class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-cyan-500 focus:ring-2 focus:ring-cyan-200 transition-colors"
                                   placeholder="Ej: El Poblado, La Candelaria, etc." required>
                        </div>
                        
                        <div>
                            <label for="referencia" class="block text-sm font-medium text-gray-700 mb-1">Teléfono de Referencia *</label>
                            <input type="text" id="referencia" name="referencia" value="{{ old('referencia') }}" 
                                   class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-cyan-500 focus:ring-2 focus:ring-cyan-200 transition-colors"
                                   placeholder="Persona que pueda confirmar tu información" required>
                        </div>
                    </div>
                </div>

                <!-- Información del Hogar -->
                <div class="space-y-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-cyan-100 p-2 rounded-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-cyan-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                        </div>
                        <h3 class="ml-3 text-xl font-bold text-blue-900">Información del Hogar</h3>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="tipo_vivienda" class="block text-sm font-medium text-gray-700 mb-1">Tipo de Vivienda *</label>
                            <select id="tipo_vivienda" name="tipo_vivienda" 
                                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-cyan-500 focus:ring-2 focus:ring-cyan-200 transition-colors" required>
                                <option value="">Selecciona una opción</option>
                                <option value="casa" {{ old('tipo_vivienda') == 'casa' ? 'selected' : '' }}>Casa</option>
                                <option value="apartamento" {{ old('tipo_vivienda') == 'apartamento' ? 'selected' : '' }}>Apartamento</option>
                                <option value="duplex" {{ old('tipo_vivienda') == 'duplex' ? 'selected' : '' }}>Dúplex</option>
                                <option value="finca" {{ old('tipo_vivienda') == 'finca' ? 'selected' : '' }}>Finca</option>
                                <option value="otro" {{ old('tipo_vivienda') == 'otro' ? 'selected' : '' }}>Otro</option>
                            </select>
                        </div>
                        
                        <div>
                            <label for="tiene_patio" class="block text-sm font-medium text-gray-700 mb-1">¿Tienes Patio/Jardín? *</label>
                            <select id="tiene_patio" name="tiene_patio" 
                                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-cyan-500 focus:ring-2 focus:ring-cyan-200 transition-colors" required>
                                <option value="">Selecciona una opción</option>
                                <option value="si" {{ old('tiene_patio') == 'si' ? 'selected' : '' }}>Sí</option>
                                <option value="no" {{ old('tiene_patio') == 'no' ? 'selected' : '' }}>No</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="otros_mascotas" class="block text-sm font-medium text-gray-700 mb-1">¿Tienes otras mascotas? *</label>
                            <select id="otros_mascotas" name="otros_mascotas" 
                                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-cyan-500 focus:ring-2 focus:ring-cyan-200 transition-colors" required>
                                <option value="">Selecciona una opción</option>
                                <option value="si" {{ old('otros_mascotas') == 'si' ? 'selected' : '' }}>Sí</option>
                                <option value="no" {{ old('otros_mascotas') == 'no' ? 'selected' : '' }}>No</option>
                            </select>
                        </div>
                        
                        <div>
                            <label for="experiencia_mascotas" class="block text-sm font-medium text-gray-700 mb-1">Experiencia con mascotas *</label>
                            <select id="experiencia_mascotas" name="experiencia_mascotas" 
                                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-cyan-500 focus:ring-2 focus:ring-cyan-200 transition-colors" required>
                                <option value="">Selecciona una opción</option>
                                <option value="nada" {{ old('experiencia_mascotas') == 'nada' ? 'selected' : '' }}>Ninguna experiencia</option>
                                <option value="poca" {{ old('experiencia_mascotas') == 'poca' ? 'selected' : '' }}>Poca experiencia</option>
                                <option value="moderada" {{ old('experiencia_mascotas') == 'moderada' ? 'selected' : '' }}>Experiencia moderada</option>
                                <option value="mucha" {{ old('experiencia_mascotas') == 'mucha' ? 'selected' : '' }}>Mucha experiencia</option>
                            </select>
                        </div>
                    </div>
                    
                    <div id="mascotas-info" class="{{ old('otros_mascotas') == 'si' ? 'block' : 'hidden' }}">
                        <label for="descripcion_mascotas" class="block text-sm font-medium text-gray-700 mb-1">Describe tus otras mascotas</label>
                        <textarea id="descripcion_mascotas" name="descripcion_mascotas" rows="2" 
                                  class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-cyan-500 focus:ring-2 focus:ring-cyan-200 transition-colors"
                                  placeholder="Tipo, edad, tamaño, temperamento...">{{ old('descripcion_mascotas') }}</textarea>
                    </div>
                </div>

                <!-- Información Adicional -->
                <div class="space-y-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-cyan-100 p-2 rounded-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-cyan-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                            </svg>
                        </div>
                        <h3 class="ml-3 text-xl font-bold text-blue-900">Información Adicional</h3>
                    </div>
                    
                    <div>
                        <label for="motivo_adopcion" class="block text-sm font-medium text-gray-700 mb-1">Motivo de la Adopción *</label>
                        <p class="text-xs text-gray-500 mb-2">¿Por qué quieres adoptar a {{ $animal->nombre }}?</p>
                        <textarea id="motivo_adopcion" name="motivo_adopcion" rows="3" 
                                  class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-cyan-500 focus:ring-2 focus:ring-cyan-200 transition-colors"
                                  placeholder="Cuéntanos qué te motivó a elegir a {{ $animal->nombre }}..." required>{{ old('motivo_adopcion') }}</textarea>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="tiempo_disponible" class="block text-sm font-medium text-gray-700 mb-1">Tiempo Disponible *</label>
                            <select id="tiempo_disponible" name="tiempo_disponible" 
                                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-cyan-500 focus:ring-2 focus:ring-cyan-200 transition-colors" required>
                                <option value="">Selecciona una opción</option>
                                <option value="poco" {{ old('tiempo_disponible') == 'poco' ? 'selected' : '' }}>Poco tiempo (1-2 horas/día)</option>
                                <option value="moderado" {{ old('tiempo_disponible') == 'moderado' ? 'selected' : '' }}>Tiempo moderado (2-4 horas/día)</option>
                                <option value="mucho" {{ old('tiempo_disponible') == 'mucho' ? 'selected' : '' }}>Mucho tiempo (4+ horas/día)</option>
                            </select>
                        </div>
                        
                        <div>
                            <label for="compromiso_esterilizacion" class="block text-sm font-medium text-gray-700 mb-1">¿Aceptas esterilizar? *</label>
                            <select id="compromiso_esterilizacion" name="compromiso_esterilizacion" 
                                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-cyan-500 focus:ring-2 focus:ring-cyan-200 transition-colors" required>
                                <option value="">Selecciona una opción</option>
                                <option value="si" {{ old('compromiso_esterilizacion') == 'si' ? 'selected' : '' }}>Sí, estoy de acuerdo</option>
                                <option value="no" {{ old('compromiso_esterilizacion') == 'no' ? 'selected' : '' }}>No estoy de acuerdo</option>
                                <option value="consultar" {{ old('compromiso_esterilizacion') == 'consultar' ? 'selected' : '' }}>Deseo más información</option>
                            </select>
                        </div>
                    </div>
                    
                    <div>
                        <label for="bienestar_mascota" class="block text-sm font-medium text-gray-700 mb-1">Plan de Cuidado *</label>
                        <p class="text-xs text-gray-500 mb-2">Describe cómo garantizarás el bienestar de {{ $animal->nombre }}</p>
                        <textarea id="bienestar_mascota" name="bienestar_mascota" rows="4" 
                                  class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-cyan-500 focus:ring-2 focus:ring-cyan-200 transition-colors"
                                  placeholder="Alimentación, ejercicio, atención veterinaria, espacio, etc." required>{{ old('bienestar_mascota') }}</textarea>
                    </div>
                    
                    <div>
                        <label for="conocimiento_cuidados" class="block text-sm font-medium text-gray-700 mb-1">Conocimientos sobre Cuidados *</label>
                        <textarea id="conocimiento_cuidados" name="conocimiento_cuidados" rows="3" 
                                  class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-cyan-500 focus:ring-2 focus:ring-cyan-200 transition-colors"
                                  placeholder="Describe tu experiencia y conocimientos sobre el cuidado de mascotas" required>{{ old('conocimiento_cuidados') }}</textarea>
                    </div>
                    
                    <div class="space-y-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Preguntas de Seguridad *</label>
                        <p class="text-xs text-gray-500 mb-2">Responde estas preguntas para garantizar el bienestar de {{ $animal->nombre }}</p>
                        
                        <div class="space-y-4 bg-gray-50 p-4 rounded-lg border border-gray-200">
                            <div>
                                <p class="font-medium text-gray-800 mb-1">1. ¿Tienes experiencia previa con mascotas de este tipo?</p>
                                <textarea name="preguntas_seguridad[experiencia]" rows="2" 
                                          class="w-full px-3 py-2 text-sm rounded border border-gray-300 focus:border-cyan-500 focus:ring-2 focus:ring-cyan-200 transition-colors"
                                          placeholder="Describe tu experiencia previa..." required>{{ old('preguntas_seguridad.experiencia') }}</textarea>
                            </div>
                            <div>
                                <p class="font-medium text-gray-800 mb-1">2. ¿Cómo planeas manejar situaciones de emergencia con la mascota?</p>
                                <textarea name="preguntas_seguridad[emergencias]" rows="2" 
                                          class="w-full px-3 py-2 text-sm rounded border border-gray-300 focus:border-cyan-500 focus:ring-2 focus:ring-cyan-200 transition-colors"
                                          placeholder="Describe tu plan de acción..." required>{{ old('preguntas_seguridad.emergencias') }}</textarea>
                            </div>
                            <div>
                                <p class="font-medium text-gray-800 mb-1">3. ¿Cómo asegurarás que la mascota no se pierda?</p>
                                <textarea name="preguntas_seguridad[perdida]" rows="2" 
                                          class="w-full px-3 py-2 text-sm rounded border border-gray-300 focus:border-cyan-500 focus:ring-2 focus:ring-cyan-200 transition-colors"
                                          placeholder="Menciona las medidas de seguridad..." required>{{ old('preguntas_seguridad.perdida') }}</textarea>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-lg">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h2a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-blue-800">Ley Ángel (2054 de 2020)</h3>
                                <div class="mt-2 text-sm text-blue-700">
                                    <p>El maltrato animal es un delito en Colombia. Al adoptar, te comprometes a:</p>
                                    <ul class="list-disc pl-5 mt-1 space-y-1">
                                        <li>Proporcionar alimentación adecuada y agua potable</li>
                                        <li>Brindar atención veterinaria cuando sea necesario</li>
                                        <li>Proveer un espacio limpio y seguro</li>
                                        <li>No someter al animal a maltrato, abandono o crueldad</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div>
                        <label for="mensaje" class="block text-sm font-medium text-gray-700 mb-1">Información adicional (opcional)</label>
                        <textarea id="mensaje" name="mensaje" rows="2" 
                                  class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-cyan-500 focus:ring-2 focus:ring-cyan-200 transition-colors"
                                  placeholder="Cualquier información adicional que quieras compartir...">{{ old('mensaje') }}</textarea>
                    </div>
                </div>

                <!-- Botones -->
                <div class="flex flex-col sm:flex-row gap-4 pt-6 border-t border-gray-200">
                    <a href="{{ route('publico.animal', $animal->id) }}" 
                       class="w-full sm:w-auto flex items-center justify-center gap-2 border-2 border-cyan-400 text-cyan-600 py-3 px-6 rounded-full font-bold hover:bg-cyan-50 transition-all duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Cancelar
                    </a>
                    <button type="submit" 
                            class="w-full sm:w-auto flex items-center justify-center gap-2 bg-gradient-to-r from-blue-900 to-cyan-500 text-white py-3 px-8 rounded-full font-bold shadow-lg hover:from-cyan-600 hover:to-blue-800 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:ring-offset-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                        </svg>
                        Enviar Solicitud
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Mostrar/ocultar campo de descripción de mascotas según selección
    document.getElementById('otros_mascotas').addEventListener('change', function() {
        const mascotasInfo = document.getElementById('mascotas-info');
        if (this.value === 'si') {
            mascotasInfo.classList.remove('hidden');
            mascotasInfo.querySelector('textarea').setAttribute('required', 'required');
        } else {
            mascotasInfo.classList.add('hidden');
            mascotasInfo.querySelector('textarea').removeAttribute('required');
        }
    });
</script>
@endsection