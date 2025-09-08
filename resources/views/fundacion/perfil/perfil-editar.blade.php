@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="max-w-5xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-xl shadow-md overflow-hidden p-6">
            <div class="border-b border-gray-200 pb-4 mb-6">
                <h1 class="text-3xl font-bold text-gray-800">Editar Perfil de Fundación</h1>
                <p class="mt-1 text-sm text-gray-600">Actualiza la información pública de tu organización</p>
            </div>
            
            @if(session('success'))
                <div class="bg-green-50 border-l-4 border-green-400 p-4 mb-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-green-700">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <form action="{{ route('fundacion.perfil.actualizar') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Columna 1 - Información básica -->
                    <div class="space-y-6">
                        <div>
                            <h2 class="text-lg font-medium text-gray-900 mb-4 pb-2 border-b border-gray-200">Información Básica</h2>
                            
                            <div class="space-y-5">
                                <div>
                                    <label for="nombre" class="block text-sm font-medium text-gray-700 mb-1">Nombre de la Fundación *</label>
                                    <input type="text" id="nombre" name="nombre" value="{{ old('nombre', $fundacion->nombre) }}" 
                                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm py-2 px-3 border"
                                           required>
                                    @error('nombre')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="descripcion" class="block text-sm font-medium text-gray-700 mb-1">Descripción *</label>
                                    <textarea id="descripcion" name="descripcion" rows="4" 
                                              class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm py-2 px-3 border"
                                              required>{{ old('descripcion', $fundacion->descripcion) }}</textarea>
                                    @error('descripcion')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="imagen" class="block text-sm font-medium text-gray-700 mb-1">Logo/Imagen</label>
                                    <div class="flex items-center space-x-4">
                                        @if($fundacion->imagen)
                                            <div class="flex-shrink-0 h-16 w-16 rounded-full overflow-hidden bg-gray-200">
                                                <img class="h-full w-full object-cover" src="{{ $fundacion->imagen ? asset($fundacion->imagen) : asset('img/defaults/fundacion-default.jpg') }}" alt="Logo actual">
                                            </div>
                                        @endif
                                        <div class="flex-1">
                                            <input type="file" id="imagen" name="imagen" accept="image/*" 
                                                   class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                            <p class="mt-1 text-xs text-gray-500">PNG, JPG o JPEG (Máx. 2MB)</p>
                                            @error('imagen')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div>
                            <h2 class="text-lg font-medium text-gray-900 mb-4 pb-2 border-b border-gray-200">Ubicación</h2>
                            
                            <div class="space-y-4">
                                <div>
                                    <label for="direccion" class="block text-sm font-medium text-gray-700 mb-1">Dirección *</label>
                                    <input type="text" id="direccion" name="direccion" value="{{ old('direccion', $fundacion->direccion) }}" 
                                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm py-2 px-3 border mb-2" 
                                           placeholder="Busca o escribe la dirección" required autocomplete="off">
                                    <ul id="autocomplete-results" class="bg-white border border-gray-300 rounded-md shadow-lg mt-1 mb-2 hidden z-10 max-h-60 overflow-auto"></ul>
                                    <div class="text-xs text-gray-500 mb-2">Selecciona una ubicación en el mapa o busca una dirección</div>
                                    
                                    <div id="gmap-perfil" class="h-64 w-full rounded-lg border-2 border-blue-200 overflow-hidden">
                                        <span id="gmap-error" class="hidden text-red-500">No se pudo cargar el mapa. Por favor intenta recargar la página.</span>
                                    </div>
                                    
                                    <div class="grid grid-cols-2 gap-4 mt-3">
                                        <div>
                                            <label for="latitud" class="block text-xs font-medium text-gray-500">Latitud</label>
                                            <input type="text" id="latitud" name="latitud" value="{{ old('latitud', $fundacion->latitud ?? '') }}" 
                                                   class="block w-full rounded-md border-gray-300 shadow-sm sm:text-sm py-2 px-3 border bg-gray-50" 
                                                   readonly required>
                                        </div>
                                        <div>
                                            <label for="longitud" class="block text-xs font-medium text-gray-500">Longitud</label>
                                            <input type="text" id="longitud" name="longitud" value="{{ old('longitud', $fundacion->longitud ?? '') }}" 
                                                   class="block w-full rounded-md border-gray-300 shadow-sm sm:text-sm py-2 px-3 border bg-gray-50" 
                                                   readonly required>
                                        </div>
                                    </div>
                                    @error('direccion')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                    @error('latitud')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                    @error('longitud')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Columna 2 - Información de contacto -->
                    <div class="space-y-6">
                        <div>
                            <h2 class="text-lg font-medium text-gray-900 mb-4 pb-2 border-b border-gray-200">Contacto</h2>
                            
                            <div class="space-y-5">
                                <div>
                                    <label for="telefono" class="block text-sm font-medium text-gray-700 mb-1">Teléfono *</label>
                                    <input type="text" id="telefono" name="telefono" value="{{ old('telefono', $fundacion->telefono) }}" 
                                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm py-2 px-3 border"
                                           required>
                                    @error('telefono')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                                    <input type="email" id="email" name="email" value="{{ old('email', $fundacion->email) }}" 
                                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm py-2 px-3 border"
                                           required>
                                    @error('email')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                               
                            </div>
                        </div>

                        <div>
                            <h2 class="text-lg font-medium text-gray-900 mb-4 pb-2 border-b border-gray-200">Redes Sociales</h2>
                            
                            <div class="space-y-5">
                                <div>
                                    <label for="facebook" class="block text-sm font-medium text-gray-700 mb-1">Facebook</label>
                                    <div class="mt-1 flex rounded-md shadow-sm">
                                        <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 sm:text-sm">facebook.com/</span>
                                        <input type="text" id="facebook" name="facebook" value="{{ old('facebook', str_replace('https://facebook.com/', '', $fundacion->facebook ?? '')) }}" 
                                               class="flex-1 min-w-0 block w-full rounded-none rounded-r-md border-gray-300 focus:border-blue-500 focus:ring-blue-500 sm:text-sm py-2 px-3 border"
                                               placeholder="tupagina">
                                    </div>
                                    @error('facebook')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="instagram" class="block text-sm font-medium text-gray-700 mb-1">Instagram</label>
                                    <div class="mt-1 flex rounded-md shadow-sm">
                                        <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 sm:text-sm">instagram.com/</span>
                                        <input type="text" id="instagram" name="instagram" value="{{ old('instagram', str_replace('https://instagram.com/', '', $fundacion->instagram ?? '')) }}" 
                                               class="flex-1 min-w-0 block w-full rounded-none rounded-r-md border-gray-300 focus:border-blue-500 focus:ring-blue-500 sm:text-sm py-2 px-3 border"
                                               placeholder="tucuenta">
                                    </div>
                                    @error('instagram')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Sección de Información Bancaria -->
                        <div>
                            <h2 class="text-lg font-medium text-gray-900 mb-4 pb-2 border-b border-gray-200">Información Bancaria</h2>
                            
                            <div class="space-y-5">
                                <div>
                                    <label for="banco_nombre" class="block text-sm font-medium text-gray-700 mb-1">Banco *</label>
                                    <input type="text" id="banco_nombre" name="banco_nombre" value="{{ old('banco_nombre', $fundacion->banco_nombre ?? '') }}" 
                                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm py-2 px-3 border"
                                           required>
                                    @error('banco_nombre')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label for="tipo_cuenta" class="block text-sm font-medium text-gray-700 mb-1">Tipo de Cuenta *</label>
                                        <select id="tipo_cuenta" name="tipo_cuenta" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm py-2 px-3 border" required>
                                            <option value="">Seleccione...</option>
                                            <option value="ahorros" {{ old('tipo_cuenta', $fundacion->tipo_cuenta ?? '') == 'ahorros' ? 'selected' : '' }}>Ahorros</option>
                                            <option value="corriente" {{ old('tipo_cuenta', $fundacion->tipo_cuenta ?? '') == 'corriente' ? 'selected' : '' }}>Corriente</option>
                                        </select>
                                        @error('tipo_cuenta')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="numero_cuenta" class="block text-sm font-medium text-gray-700 mb-1">Número de Cuenta *</label>
                                        <input type="text" id="numero_cuenta" name="numero_cuenta" value="{{ old('numero_cuenta', $fundacion->numero_cuenta ?? '') }}" 
                                               class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm py-2 px-3 border"
                                               required>
                                        @error('numero_cuenta')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label for="nombre_titular" class="block text-sm font-medium text-gray-700 mb-1">Nombre del Titular *</label>
                                        <input type="text" id="nombre_titular" name="nombre_titular" value="{{ old('nombre_titular', $fundacion->nombre_titular ?? '') }}" 
                                               class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm py-2 px-3 border"
                                               required>
                                        @error('nombre_titular')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="identificacion_titular" class="block text-sm font-medium text-gray-700 mb-1">Identificación del Titular *</label>
                                        <input type="text" id="identificacion_titular" name="identificacion_titular" value="{{ old('identificacion_titular', $fundacion->identificacion_titular ?? '') }}" 
                                               class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm py-2 px-3 border"
                                               required>
                                        @error('identificacion_titular')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div>
                                    <label for="email_contacto_pagos" class="block text-sm font-medium text-gray-700 mb-1">Email para notificaciones de pagos</label>
                                    <input type="email" id="email_contacto_pagos" name="email_contacto_pagos" value="{{ old('email_contacto_pagos', $fundacion->email_contacto_pagos ?? '') }}" 
                                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm py-2 px-3 border">
                                    @error('email_contacto_pagos')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="otros_metodos_pago" class="block text-sm font-medium text-gray-700 mb-1">Otros métodos de pago aceptados</label>
                                    <textarea id="otros_metodos_pago" name="otros_metodos_pago" rows="3" 
                                              class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm py-2 px-3 border"
                                              placeholder="Ej: Nequi, Daviplata, Transferencia Bancolombia">{{ old('otros_metodos_pago', $fundacion->otros_metodos_pago ?? '') }}</textarea>
                                    @error('otros_metodos_pago')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <!-- Fin de Sección de Información Bancaria -->
                    </div>
                </div>

                <div class="pt-5 border-t border-gray-200">
                    <div class="flex justify-end space-x-3">
                        <a href="{{ route('fundacion.perfil') }}" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Cancelar
                        </a>
                        <button type="submit" class="inline-flex justify-center py-2 px-6 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Guardar Cambios
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    let map, marker;
    
    function setMarkerAndInputs(lat, lng, address) {
        if (marker) {
            marker.setLatLng([lat, lng]);
        } else {
            marker = L.marker([lat, lng], {draggable: true}).addTo(map);
            marker.on('dragend', function(e) {
                const latlng = marker.getLatLng();
                updateCoordinates(latlng.lat, latlng.lng);
                reverseGeocode(latlng.lat, latlng.lng);
            });
        }
        
        map.setView([lat, lng], 16);
        updateCoordinates(lat, lng);
        if (address) {
            document.getElementById('direccion').value = address;
        }
    }
    
    function updateCoordinates(lat, lng) {
        document.getElementById('latitud').value = lat.toFixed(7);
        document.getElementById('longitud').value = lng.toFixed(7);
    }
    
    function reverseGeocode(lat, lng) {
        fetch(`https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${lat}&lon=${lng}`)
            .then(res => res.json())
            .then(data => {
                if (data.display_name) {
                    document.getElementById('direccion').value = data.display_name;
                }
            })
            .catch(err => console.error('Error en geocodificación inversa:', err));
    }

    document.addEventListener('DOMContentLoaded', function () {
        // Valores por defecto (Bogotá)
        const defaultLat = parseFloat(document.getElementById('latitud').value) || 4.710989;
        const defaultLng = parseFloat(document.getElementById('longitud').value) || -74.072092;
        
        try {
            // Inicializar mapa
            map = L.map('gmap-perfil').setView([defaultLat, defaultLng], 13);
            
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '© OpenStreetMap'
            }).addTo(map);
            
            // Agregar marcador inicial
            setMarkerAndInputs(defaultLat, defaultLng);
            
            // Manejar clics en el mapa
            map.on('click', function(e) {
                setMarkerAndInputs(e.latlng.lat, e.latlng.lng);
                reverseGeocode(e.latlng.lat, e.latlng.lng);
            });
            
            // Ajustar tamaño del mapa después de que se cargue
            setTimeout(() => map.invalidateSize(), 300);

            // Autocompletado de direcciones
            const direccionInput = document.getElementById('direccion');
            const resultsList = document.getElementById('autocomplete-results');
            let timeout = null;
            
            direccionInput.addEventListener('input', function() {
                clearTimeout(timeout);
                const query = this.value.trim();
                
                if (query.length < 3) {
                    resultsList.innerHTML = '';
                    resultsList.classList.add('hidden');
                    return;
                }
                
                timeout = setTimeout(() => {
                    fetch(`https://nominatim.openstreetmap.org/search?format=jsonv2&q=${encodeURIComponent(query)}&limit=5`)
                        .then(res => res.json())
                        .then(data => {
                            resultsList.innerHTML = '';
                            
                            if (data.length === 0) {
                                resultsList.classList.add('hidden');
                                return;
                            }
                            
                            data.forEach(item => {
                                const li = document.createElement('li');
                                li.textContent = item.display_name;
                                li.className = 'px-3 py-2 cursor-pointer hover:bg-blue-50 text-sm';
                                li.addEventListener('click', function() {
                                    setMarkerAndInputs(
                                        parseFloat(item.lat), 
                                        parseFloat(item.lon), 
                                        item.display_name
                                    );
                                    resultsList.innerHTML = '';
                                    resultsList.classList.add('hidden');
                                });
                                resultsList.appendChild(li);
                            });
                            
                            resultsList.classList.remove('hidden');
                        })
                        .catch(err => console.error('Error en autocompletado:', err));
                }, 400);
            });
            
            // Ocultar resultados al hacer clic fuera
            document.addEventListener('click', function(e) {
                if (!direccionInput.contains(e.target) && !resultsList.contains(e.target)) {
                    resultsList.classList.add('hidden');
                }
            });
            
        } catch (e) {
            document.getElementById('gmap-error').classList.remove('hidden');
            console.error('Error al inicializar el mapa:', e);
        }
    });
</script>
@endpush