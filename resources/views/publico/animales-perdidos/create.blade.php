@extends('layouts.app')

@section('title', 'Reportar Mascota Perdida - ' . config('app.name'))

@section('content')
<div class="bg-gray-50 py-8">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6 bg-gray-50 border-b border-gray-200">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Reportar Mascota Perdida</h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">Complete la información para reportar una mascota perdida.</p>
            </div>
            
            <form action="{{ route('animales-perdidos.store') }}" method="POST" enctype="multipart/form-data" class="p-6">
                @csrf
                
                <!-- Información Básica -->
                <div class="space-y-6">
                    <div>
                        <h4 class="text-lg font-medium text-gray-900 mb-4">Información Básica</h4>
                        <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                            <!-- Nombre -->
                            <div class="sm:col-span-3">
                                <label for="nombre" class="block text-sm font-medium text-gray-700">Nombre <span class="text-red-500">*</span></label>
                                <input type="text" name="nombre" id="nombre" required
                                    class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                    value="{{ old('nombre') }}">
                            </div>

                            <!-- Tipo de Animal -->
                            <div class="sm:col-span-3">
                                <label for="tipo" class="block text-sm font-medium text-gray-700">Tipo <span class="text-red-500">*</span></label>
                                <select id="tipo" name="tipo" required
                                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    <option value="">Seleccione un tipo</option>
                                    <option value="perro" {{ old('tipo') == 'perro' ? 'selected' : '' }}>Perro</option>
                                    <option value="gato" {{ old('tipo') == 'gato' ? 'selected' : '' }}>Gato</option>
                                    <option value="otro" {{ old('tipo') == 'otro' ? 'selected' : '' }}>Otro</option>
                                </select>
                            </div>

                            <!-- Raza -->
                            <div class="sm:col-span-3">
                                <label for="raza" class="block text-sm font-medium text-gray-700">Raza (opcional)</label>
                                <input type="text" name="raza" id="raza"
                                    class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                    value="{{ old('raza') }}">
                            </div>

                            <!-- Edad -->
                            <div class="sm:col-span-3">
                                <label for="edad" class="block text-sm font-medium text-gray-700">Edad <span class="text-red-500">*</span></label>
                                <div class="mt-1 flex rounded-md shadow-sm">
                                    <input type="number" name="edad" id="edad" min="0" required
                                        class="focus:ring-indigo-500 focus:border-indigo-500 flex-1 block w-full rounded-none rounded-l-md sm:text-sm border-gray-300"
                                        value="{{ old('edad') }}">
                                    <select name="tipo_edad" class="focus:ring-indigo-500 focus:border-indigo-500 h-full py-0 pl-1 pr-4 border-transparent bg-transparent text-gray-500 sm:text-sm rounded-r-md border-l border-gray-300">
                                        <option value="meses" {{ old('tipo_edad', 'meses') == 'meses' ? 'selected' : '' }}>meses</option>
                                        <option value="años" {{ old('tipo_edad') == 'años' ? 'selected' : '' }}>años</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Sexo -->
                            <div class="sm:col-span-3">
                                <label for="sexo" class="block text-sm font-medium text-gray-700">Sexo <span class="text-red-500">*</span></label>
                                <select id="sexo" name="sexo" required
                                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    <option value="">Seleccione el sexo</option>
                                    <option value="macho" {{ old('sexo') == 'macho' ? 'selected' : '' }}>Macho</option>
                                    <option value="hembra" {{ old('sexo') == 'hembra' ? 'selected' : '' }}>Hembra</option>
                                </select>
                            </div>

                            <!-- Imagen -->
                            <div class="sm:col-span-6">
                                <label class="block text-sm font-medium text-gray-700">
                                    Foto de la mascota <span class="text-red-500">*</span>
                                </label>
                                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                                    <div class="space-y-1 text-center">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        <div class="flex text-sm text-gray-600">
                                            <label for="imagen" class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                                                <span>Subir una imagen</span>
                                                <input id="imagen" name="imagen" type="file" class="sr-only" accept="image/*" required>
                                            </label>
                                            <p class="pl-1">o arrastra y suelta</p>
                                        </div>
                                        <p class="text-xs text-gray-500">
                                            PNG, JPG, GIF hasta 2MB
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Descripción -->
                            <div class="sm:col-span-6">
                                <label for="descripcion" class="block text-sm font-medium text-gray-700">
                                    Descripción <span class="text-red-500">*</span>
                                </label>
                                <div class="mt-1">
                                    <textarea id="descripcion" name="descripcion" rows="3" required
                                        class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border border-gray-300 rounded-md">{{ old('descripcion') }}</textarea>
                                </div>
                                <p class="mt-2 text-sm text-gray-500">Describa características distintivas, color del collar, etc.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Información de Contacto -->
                    <div class="pt-6 border-t border-gray-200">
                        <h4 class="text-lg font-medium text-gray-900 mb-4">Información de Contacto</h4>
                        <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                            <!-- Teléfono -->
                            <div class="sm:col-span-3">
                                <label for="telefono_contacto" class="block text-sm font-medium text-gray-700">
                                    Teléfono de contacto <span class="text-red-500">*</span>
                                </label>
                                <div class="mt-1">
                                    <input type="tel" name="telefono_contacto" id="telefono_contacto" required
                                        class="focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                        value="{{ old('telefono_contacto') }}">
                                </div>
                            </div>

                            <!-- Email -->
                            <div class="sm:col-span-3">
                                <label for="email_contacto" class="block text-sm font-medium text-gray-700">
                                    Correo electrónico <span class="text-red-500">*</span>
                                </label>
                                <div class="mt-1">
                                    <input type="email" name="email_contacto" id="email_contacto" required
                                        class="focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                        value="{{ old('email_contacto') }}">
                                </div>
                            </div>

                            <!-- Recompensa -->
                            <div class="sm:col-span-6">
                                <label for="recompensa" class="block text-sm font-medium text-gray-700">
                                    Recompensa (opcional)
                                </label>
                                <div class="mt-1">
                                    <input type="text" name="recompensa" id="recompensa"
                                        class="focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                        placeholder="Ej: $100.000 o juguetes"
                                        value="{{ old('recompensa') }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Última Ubicación Vista -->
                    <div class="pt-6 border-t border-gray-200">
                        <h4 class="text-lg font-medium text-gray-900 mb-4">Última Ubicación Vista</h4>
                        <div class="grid grid-cols-1 gap-y-4 sm:grid-cols-6">
                            <!-- Campo de dirección -->
                            <div class="sm:col-span-6">
                                <label for="ultima_ubicacion" class="block text-sm font-medium text-gray-700 mb-1">
                                    Dirección o punto de referencia <span class="text-red-500">*</span>
                                </label>
                                <div class="mt-1 flex rounded-md shadow-sm">
                                    <input type="text" name="ultima_ubicacion" id="ultima_ubicacion" required
                                        class="focus:ring-indigo-500 focus:border-indigo-500 flex-1 block w-full rounded-md sm:text-sm border-gray-300 px-4 py-2"
                                        placeholder="Ej: Calle 123 #45-67, Barrio El Poblado"
                                        value="{{ old('ultima_ubicacion') }}">
                                </div>
                            </div>

                            <!-- Botón para obtener ubicación -->
                            <div class="sm:col-span-6">
                                <button type="button" id="obtener-ubicacion" 
                                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    <i class="fas fa-location-arrow mr-2"></i> Usar mi ubicación actual
                                </button>
                                <p class="mt-2 text-sm text-gray-500">
                                    Permite que el navegador acceda a tu ubicación para completar automáticamente las coordenadas.
                                </p>
                            </div>

                            <!-- Campos ocultos para coordenadas -->
                            <input type="hidden" name="latitud" id="latitud" value="{{ old('latitud') }}">
                            <input type="hidden" name="longitud" id="longitud" value="{{ old('longitud') }}">
                            <input type="hidden" name="direccion" id="direccion" value="{{ old('direccion') }}">

                            <!-- Mostrar coordenadas obtenidas -->
                            <div class="sm:col-span-6 bg-gray-50 p-3 rounded-md">
                                <p class="text-sm font-medium text-gray-700">Coordenadas obtenidas:</p>
                                <div id="coordenadas-display" class="mt-1 text-sm text-gray-600">
                                    @if(old('latitud') && old('longitud'))
                                        Latitud: {{ old('latitud') }}, Longitud: {{ old('longitud') }}
                                    @else
                                        No se han obtenido coordenadas aún
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Botones de acción -->
                <div class="pt-5">
                    <div class="flex justify-end">
                        <a href="{{ route('animales-perdidos.index') }}" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Cancelar
                        </a>
                        <button type="submit" class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Publicar Mascota Perdida
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Botón para obtener ubicación
        const ubicacionBtn = document.getElementById('obtener-ubicacion');
        
        ubicacionBtn.addEventListener('click', function() {
            if (navigator.geolocation) {
                const icon = ubicacionBtn.querySelector('i');
                const originalIcon = icon.className;
                const originalText = ubicacionBtn.innerHTML;
                
                // Mostrar spinner de carga
                icon.className = 'fas fa-spinner fa-spin mr-2';
                ubicacionBtn.disabled = true;
                ubicacionBtn.innerHTML = icon.outerHTML + ' Obteniendo ubicación...';
                
                navigator.geolocation.getCurrentPosition(
                    function(position) {
                        const lat = position.coords.latitude;
                        const lng = position.coords.longitude;
                        
                        // Actualizar campos ocultos
                        document.getElementById('latitud').value = lat;
                        document.getElementById('longitud').value = lng;
                        
                        // Mostrar coordenadas
                        document.getElementById('coordenadas-display').innerHTML = 
                            `Latitud: ${lat.toFixed(6)}, Longitud: ${lng.toFixed(6)}`;
                        
                        // Obtener dirección usando Nominatim (geocodificación inversa)
                        fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&zoom=18&addressdetails=1`)
                            .then(response => response.json())
                            .then(data => {
                                if (data.display_name) {
                                    document.getElementById('direccion').value = data.display_name;
                                    document.getElementById('ultima_ubicacion').value = data.display_name;
                                }
                                
                                // Mostrar checkmark y restaurar botón
                                icon.className = 'fas fa-check mr-2';
                                ubicacionBtn.innerHTML = icon.outerHTML + ' Ubicación obtenida';
                                
                                setTimeout(() => {
                                    icon.className = originalIcon;
                                    ubicacionBtn.innerHTML = originalText;
                                    ubicacionBtn.disabled = false;
                                }, 2000);
                                
                                showToast('Ubicación obtenida correctamente');
                            })
                            .catch(error => {
                                console.error('Error al obtener la dirección:', error);
                                icon.className = originalIcon;
                                ubicacionBtn.innerHTML = originalText;
                                ubicacionBtn.disabled = false;
                                showToast('Ubicación obtenida, pero no se pudo determinar la dirección', 3000);
                            });
                    },
                    function(error) {
                        icon.className = originalIcon;
                        ubicacionBtn.innerHTML = originalText;
                        ubicacionBtn.disabled = false;
                        
                        let errorMessage;
                        switch(error.code) {
                            case error.PERMISSION_DENIED:
                                errorMessage = "Permiso de ubicación denegado. Por favor habilítalo en la configuración de tu navegador.";
                                break;
                            case error.POSITION_UNAVAILABLE:
                                errorMessage = "Información de ubicación no disponible.";
                                break;
                            case error.TIMEOUT:
                                errorMessage = "La solicitud de ubicación ha caducado.";
                                break;
                            case error.UNKNOWN_ERROR:
                                errorMessage = "Ocurrió un error desconocido al obtener la ubicación.";
                                break;
                        }
                        
                        showToast(errorMessage, 5000);
                    },
                    {
                        enableHighAccuracy: true,
                        timeout: 10000,
                        maximumAge: 0
                    }
                );
            } else {
                showToast("Tu navegador no soporta la geolocalización", 5000);
            }
        });
        
        // Función para mostrar notificación toast
        function showToast(message, duration = 3000) {
            const toast = document.createElement('div');
            toast.className = 'toast';
            toast.textContent = message;
            document.body.appendChild(toast);
            
            setTimeout(() => toast.classList.add('show'), 10);
            
            setTimeout(() => {
                toast.classList.remove('show');
                setTimeout(() => toast.remove(), 300);
            }, duration);
        }
    });
</script>

<style>
    .toast {
        position: fixed;
        bottom: 20px;
        left: 50%;
        transform: translateX(-50%);
        background-color: #333;
        color: white;
        padding: 12px 24px;
        border-radius: 4px;
        z-index: 1000;
        opacity: 0;
        transition: opacity 0.3s;
    }
    .toast.show {
        opacity: 1;
    }
    .fa-spin {
        animation: spin 1s linear infinite;
    }
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
</style>
@endpush

@endsection