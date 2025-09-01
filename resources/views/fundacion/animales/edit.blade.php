@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100">
    <div class="max-w-2xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h1 class="text-2xl font-bold mb-6">Editar Animal</h1>
            <form action="{{ route('fundacion.animales.actualizar', $animal->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Nombre</label>
                            <input type="text" name="nombre" value="{{ old('nombre', $animal->nombre) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('nombre')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Tipo</label>
                            <select name="tipo" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="perro" {{ old('tipo', $animal->tipo) == 'perro' ? 'selected' : '' }}>Perro</option>
                                <option value="gato" {{ old('tipo', $animal->tipo) == 'gato' ? 'selected' : '' }}>Gato</option>
                                <option value="otro" {{ old('tipo', $animal->tipo) == 'otro' ? 'selected' : '' }}>Otro</option>
                            </select>
                            @error('tipo')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mb-4 space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Edad</label>
                            <div class="mt-1">
                                <div class="flex items-center space-x-2">
                                    <input type="radio" name="tipo_edad" value="anios" id="edad_anios" 
                                           class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300" 
                                           {{ old('tipo_edad', $animal->tipo_edad) == 'anios' ? 'checked' : '' }}>
                                    <label for="edad_anios" class="text-sm font-medium text-gray-700">Años</label>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <input type="radio" name="tipo_edad" value="meses" id="edad_meses" 
                                           class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300" 
                                           {{ old('tipo_edad', $animal->tipo_edad) == 'meses' ? 'checked' : '' }}>
                                    <label for="edad_meses" class="text-sm font-medium text-gray-700">Meses</label>
                                </div>
                            </div>
                            <div class="mt-2">
                                <input type="number" name="edad" value="{{ old('edad', $animal->edad) }}" 
                                       class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('edad')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Sexo</label>
                            <select name="sexo" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="macho" {{ old('sexo', $animal->sexo) == 'macho' ? 'selected' : '' }}>Macho</option>
                                <option value="hembra" {{ old('sexo', $animal->sexo) == 'hembra' ? 'selected' : '' }}>Hembra</option>
                            </select>
                            @error('sexo')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Descripción</label>
                            <textarea name="descripcion" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('descripcion', $animal->descripcion) }}</textarea>
                            @error('descripcion')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Ubicación en el mapa -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Dirección *</label>
                            <input type="text" id="direccion" name="direccion" value="{{ old('direccion', $animal->direccion) }}" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 py-2 px-3 border mb-2" placeholder="Busca o escribe la dirección" required autocomplete="off">
                            <ul id="autocomplete-results" class="bg-white border border-gray-300 rounded shadow mt-1 mb-2 hidden z-10"></ul>
                            <div id="gmap-edit" class="min-h-[300px] border-2 border-red-500 flex items-center justify-center text-center text-red-700" style="height: 300px; border-radius: 0.5rem; margin-bottom: 1rem;">
                                <span id="gmap-error" style="display:none;">No se pudo cargar el mapa. Revisa la consola del navegador.</span>
                            </div>
                            <div class="flex gap-4">
                                <div class="w-1/2">
                                    <label for="latitud" class="block text-xs text-gray-600">Latitud</label>
                                    <input type="text" id="latitud" name="latitud" value="{{ old('latitud', $animal->latitud) }}" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 py-2 px-3 border" readonly required>
                                </div>
                                <div class="w-1/2">
                                    <label for="longitud" class="block text-xs text-gray-600">Longitud</label>
                                    <input type="text" id="longitud" name="longitud" value="{{ old('longitud', $animal->longitud) }}" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 py-2 px-3 border" readonly required>
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
                            <select name="estado" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="disponible" {{ old('estado', $animal->estado) == 'disponible' ? 'selected' : '' }}>Disponible</option>
                                <option value="adoptado" {{ old('estado', $animal->estado) == 'adoptado' ? 'selected' : '' }}>Adoptado</option>
                                <option value="en_adopcion" {{ old('estado', $animal->estado) == 'en_adopcion' ? 'selected' : '' }}>En adopción</option>
                            </select>
                            @error('estado')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Imagen (opcional)</label>
                            <input type="file" name="imagen" accept="image/*" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @if($animal->imagen)
                                <img src="{{ asset('test/' . $animal->imagen) }}" alt="Imagen actual" class="w-32 h-32 object-cover rounded mt-2">
                            @endif
                            @error('imagen')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="mt-6 flex justify-end">
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-800">Actualizar Animal</button>
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
        marker.setLatLng([lat, lng]);
        map.setView([lat, lng], 16);
        document.getElementById('latitud').value = lat.toFixed(7);
        document.getElementById('longitud').value = lng.toFixed(7);
        if(address) document.getElementById('direccion').value = address;
    }
    document.addEventListener('DOMContentLoaded', function () {
        var defaultLat = parseFloat(document.getElementById('latitud').value) || 4.710989;
        var defaultLng = parseFloat(document.getElementById('longitud').value) || -74.072092;
        var gmapDiv = document.getElementById('gmap-edit');
        var gmapError = document.getElementById('gmap-error');
        if (!gmapDiv) {
            if(gmapError) gmapError.style.display = 'block';
            console.error('No se encontró el div del mapa.');
            return;
        }
        try {
            map = L.map('gmap-edit').setView([defaultLat, defaultLng], 13);
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