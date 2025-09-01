@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-xl shadow-sm p-8">
            <!-- Encabezado -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900">Agregar Nuevo Animal</h1>
                <p class="mt-2 text-gray-600">Completa el formulario para registrar un nuevo animal en tu fundación</p>
            </div>

            <!-- Mensaje de éxito -->
            @if(session('success'))
                <div class="mb-6 p-4 bg-green-50 rounded-lg border border-green-200 flex items-start">
                    <svg class="h-5 w-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <div>
                        <h3 class="text-sm font-medium text-green-800">¡Éxito!</h3>
                        <p class="mt-1 text-sm text-green-700">{{ session('success') }}</p>
                    </div>
                </div>
            @elseif(session('error'))
                <div class="mb-6 p-4 bg-red-50 rounded-lg border border-red-200 flex items-start">
                    <svg class="h-5 w-5 text-red-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    <div>
                        <h3 class="text-sm font-medium text-red-800">¡Error!</h3>
                        <p class="mt-1 text-sm text-red-700">{{ session('error') }}</p>
                    </div>
                </div>
            @endif

            <!-- Formulario -->
            @if($errors->any())
                <div class="mb-4 p-4 bg-red-50 rounded-lg border border-red-200">
                    <h3 class="text-sm font-medium text-red-800">¡Error!</h3>
                    <ul class="mt-2 text-sm text-red-700">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('fundacion.animales.guardar') }}" method="POST" enctype="multipart/form-data" class="space-y-6" id="animalForm">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Columna 1 -->
                    <div class="space-y-6">
                        <!-- Nombre -->
                        <div>
                            <label for="nombre" class="block text-sm font-medium text-gray-700 mb-1">Nombre *</label>
                            <input type="text" id="nombre" name="nombre" value="{{ old('nombre') }}" 
                                   class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 py-2 px-3 border" 
                                   placeholder="Ej: Max" required>
                            @error('nombre')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Tipo -->
                        <div>
                            <label for="tipo" class="block text-sm font-medium text-gray-700 mb-1">Tipo *</label>
                            <select id="tipo" name="tipo" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 py-2 px-3 border" required>
                                <option value="" disabled selected>Selecciona una opción</option>
                                <option value="perro" {{ old('tipo') == 'perro' ? 'selected' : '' }}>Perro</option>
                                <option value="gato" {{ old('tipo') == 'gato' ? 'selected' : '' }}>Gato</option>
                                <option value="otro" {{ old('tipo') == 'otro' ? 'selected' : '' }}>Otro</option>
                            </select>
                            @error('tipo')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Edad -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Edad *</label>
                            <div class="grid grid-cols-2 gap-4">
                                <div class="space-y-2">
                                    <div class="flex items-center">
                                        <input type="radio" id="edad_anios" name="tipo_edad" value="anios" 
                                               class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300" 
                                               {{ old('tipo_edad') == 'anios' ? 'checked' : '' }}>
                                        <label for="edad_anios" class="ml-2 block text-sm text-gray-700">Años</label>
                                    </div>
                                    <div class="flex items-center">
                                        <input type="radio" id="edad_meses" name="tipo_edad" value="meses" 
                                               class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300" 
                                               {{ old('tipo_edad') == 'meses' ? 'checked' : '' }}>
                                        <label for="edad_meses" class="ml-2 block text-sm text-gray-700">Meses</label>
                                    </div>
                                    @error('tipo_edad')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <input type="number" name="edad" value="{{ old('edad') }}" 
                                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 py-2 px-3 border" 
                                           placeholder="0" min="0" required>
                                    @error('edad')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <!-- Ubicación en el mapa -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Dirección *</label>
                            <input type="text" id="direccion" name="direccion" value="{{ old('direccion') }}" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 py-2 px-3 border mb-2" placeholder="Busca o escribe la dirección" required autocomplete="off">
                            <ul id="autocomplete-results" class="bg-white border border-gray-300 rounded shadow mt-1 mb-2 hidden z-10"></ul>
                            <div id="gmap-create" class="min-h-[300px] border-2 border-red-500 flex items-center justify-center text-center text-red-700" style="height: 300px; border-radius: 0.5rem; margin-bottom: 1rem;">
                                <span id="gmap-error" style="display:none;">No se pudo cargar el mapa. Revisa la consola del navegador.</span>
                            </div>
                            <div class="flex gap-4">
                                <div class="w-1/2">
                                    <label for="latitud" class="block text-xs text-gray-600">Latitud</label>
                                    <input type="text" id="latitud" name="latitud" value="{{ old('latitud') }}" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 py-2 px-3 border" readonly required>
                                </div>
                                <div class="w-1/2">
                                    <label for="longitud" class="block text-xs text-gray-600">Longitud</label>
                                    <input type="text" id="longitud" name="longitud" value="{{ old('longitud') }}" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 py-2 px-3 border" readonly required>
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

                    <!-- Columna 2 -->
                    <div class="space-y-6">
                        <!-- Sexo -->
                        <div>
                            <label for="sexo" class="block text-sm font-medium text-gray-700 mb-1">Sexo *</label>
                            <select id="sexo" name="sexo" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 py-2 px-3 border" required>
                                <option value="" disabled selected>Selecciona una opción</option>
                                <option value="macho" {{ old('sexo') == 'macho' ? 'selected' : '' }}>Macho</option>
                                <option value="hembra" {{ old('sexo') == 'hembra' ? 'selected' : '' }}>Hembra</option>
                            </select>
                            @error('sexo')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Descripción -->
                        <div>
                            <label for="descripcion" class="block text-sm font-medium text-gray-700 mb-1">Descripción</label>
                            <textarea id="descripcion" name="descripcion" rows="4" 
                                      class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 py-2 px-3 border" 
                                      placeholder="Características, personalidad, etc.">{{ old('descripcion') }}</textarea>
                            @error('descripcion')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Imagen -->
                        <div>
                            <label for="imagen" class="block text-sm font-medium text-gray-700 mb-1">Imagen *</label>
                            <div class="mt-1 flex items-center">
                                <label for="imagen" class="cursor-pointer">
                                    <div class="group relative border-2 border-dashed border-gray-300 rounded-lg p-4 hover:border-indigo-500 transition-colors duration-200">
                                        <div class="flex flex-col items-center justify-center text-center">
                                            <svg class="mx-auto h-12 w-12 text-gray-400 group-hover:text-indigo-500" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg>
                                            <p class="mt-1 text-sm text-gray-600">Haz clic para subir una imagen</p>
                                            <p class="mt-1 text-xs text-gray-500">PNG, JPG, JPEG (Max. 5MB)</p>
                                        </div>
                                        <input id="imagen" name="imagen" type="file" accept="image/*" class="sr-only" required>
                                    </div>
                                </label>
                            </div>
                            @error('imagen')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Botones -->
                <div class="pt-6 flex justify-end space-x-4">
                    <a href="{{ route('fundacion.animales') }}" 
                       class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        <i class="fas fa-times mr-2"></i>Cancelar
                    </a>
                    <button type="submit" id="submitBtn"
                            class="inline-flex items-center px-6 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <i class="fas fa-save mr-2"></i>Guardar Animal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script>
    $(document).ready(function() {
        // Validación del formulario
        $('#animalForm').on('submit', function(e) {
            let isValid = true;
            const requiredFields = ['nombre', 'tipo', 'edad', 'tipo_edad', 'sexo', 'descripcion', 'imagen'];
            
            // Validar campos requeridos
            requiredFields.forEach(function(field) {
                const $field = $(`[name="${field}"]`);
                if (!$field.val()) {
                    isValid = false;
                    $field.addClass('border-red-500').removeClass('border-gray-300');
                } else {
                    $field.removeClass('border-red-500').addClass('border-gray-300');
                }
            });

            if (!isValid) {
                e.preventDefault();
                alert('Por favor, complete todos los campos requeridos.');
                return false;
            }
            
            // Mostrar mensaje de carga
            $('#submitBtn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-2"></i>Guardando...');
            return true;
        });
    });
</script>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    let map, marker;
    function setMarkerAndInputs(lat, lng, address) {
        marker.setLatLng([lat, lng]);
        map.setView([lat, lng], 16);
        document.getElementById('latitud').value = lat.toFixed(7);
        document.getElementById('longitud').value = lng.toFixed(7);
        if(address) document.getElementById('direccion').value = address;
    }
    document.addEventListener('DOMContentLoaded', function () {
        var defaultLat = parseFloat(document.getElementById('latitud').value) || 4.710989;
        var defaultLng = parseFloat(document.getElementById('longitud').value) || -74.072092;
        var gmapDiv = document.getElementById('gmap-create');
        var gmapError = document.getElementById('gmap-error');
        if (!gmapDiv) {
            if(gmapError) gmapError.style.display = 'block';
            console.error('No se encontró el div del mapa.');
            return;
        }
        try {
            map = L.map('gmap-create').setView([defaultLat, defaultLng], 13);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '© OpenStreetMap'
            }).addTo(map);
            marker = L.marker([defaultLat, defaultLng], {draggable:true}).addTo(map);
            setMarkerAndInputs(defaultLat, defaultLng);
            setTimeout(() => { map.invalidateSize(); }, 300);
            map.on('click', function(e) {
                setMarkerAndInputs(e.latlng.lat, e.latlng.lng);
                // Reverse geocode
                fetch(`https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${e.latlng.lat}&lon=${e.latlng.lng}`)
                    .then(res => res.json())
                    .then(data => {
                        if(data.display_name) document.getElementById('direccion').value = data.display_name;
                    });
            });
            marker.on('dragend', function(e) {
                var latlng = marker.getLatLng();
                setMarkerAndInputs(latlng.lat, latlng.lng);
                fetch(`https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${latlng.lat}&lon=${latlng.lng}`)
                    .then(res => res.json())
                    .then(data => {
                        if(data.display_name) document.getElementById('direccion').value = data.display_name;
                    });
            });

            // Autocomplete
            const direccionInput = document.getElementById('direccion');
            const resultsList = document.getElementById('autocomplete-results');
            let timeout = null;
            direccionInput.addEventListener('input', function() {
                clearTimeout(timeout);
                const query = this.value;
                if(query.length < 3) {
                    resultsList.innerHTML = '';
                    resultsList.classList.add('hidden');
                    return;
                }
                timeout = setTimeout(() => {
                    fetch(`https://nominatim.openstreetmap.org/search?format=jsonv2&q=${encodeURIComponent(query)}`)
                        .then(res => res.json())
                        .then(data => {
                            resultsList.innerHTML = '';
                            if(data.length === 0) {
                                resultsList.classList.add('hidden');
                                return;
                            }
                            data.forEach(item => {
                                const li = document.createElement('li');
                                li.textContent = item.display_name;
                                li.className = 'px-3 py-2 cursor-pointer hover:bg-blue-100';
                                li.addEventListener('click', function() {
                                    setMarkerAndInputs(parseFloat(item.lat), parseFloat(item.lon), item.display_name);
                                    resultsList.innerHTML = '';
                                    resultsList.classList.add('hidden');
                                });
                                resultsList.appendChild(li);
                            });
                            resultsList.classList.remove('hidden');
                        });
                }, 400);
            });
            document.addEventListener('click', function(e) {
                if(!direccionInput.contains(e.target) && !resultsList.contains(e.target)) {
                    resultsList.classList.add('hidden');
                }
            });
        } catch (e) {
            if(gmapError) gmapError.style.display = 'block';
            console.error('Error al inicializar el mapa:', e);
        }
    });
</script>
@endpush