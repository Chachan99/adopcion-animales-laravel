@props(['noticia' => null])

<div class="space-y-6">
    <!-- Título -->
    <div>
        <label for="titulo" class="block text-sm font-medium text-gray-700">
            Título <span class="text-red-500">*</span>
        </label>
        <div class="mt-1">
            <input type="text" name="titulo" id="titulo" required
                   value="{{ old('titulo', $noticia->titulo ?? '') }}"
                   class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
        </div>
        @error('titulo')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- Contenido -->
    <div>
        <label for="contenido" class="block text-sm font-medium text-gray-700">
            Contenido <span class="text-red-500">*</span>
        </label>
        <div class="mt-1">
            <textarea id="contenido" name="contenido" rows="8" required
                      class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border border-gray-300 rounded-md">{{ old('contenido', $noticia->contenido ?? '') }}</textarea>
        </div>
        @error('contenido')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- Imagen -->
    <div>
        <label class="block text-sm font-medium text-gray-700">
            Imagen
        </label>
        <div class="mt-1 flex items-center">
            @if(isset($noticia) && $noticia->imagen)
                <img src="{{ $noticia->imagen_url }}" alt="{{ $noticia->titulo }}" class="h-32 w-32 object-cover rounded-md">
                <div class="ml-4">
                    <p class="text-sm text-gray-500">Imagen actual</p>
                </div>
            @endif
            <div class="ml-4">
                <input type="file" id="imagen" name="imagen" class="text-sm text-gray-500">
                <p class="mt-1 text-xs text-gray-500">PNG, JPG, GIF hasta 2MB</p>
            </div>
        </div>
        @error('imagen')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- Estado -->
    <div class="flex items-center">
        <input id="publicada" name="publicada" type="checkbox" 
               {{ old('publicada', isset($noticia) && $noticia->publicada ? 'checked' : '') }}
               class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
        <label for="publicada" class="ml-2 block text-sm text-gray-900">
            Publicar ahora
        </label>
    </div>

    <!-- Fecha de publicación -->
    <div id="fecha_publicacion_container" class="hidden">
        <label for="fecha_publicacion" class="block text-sm font-medium text-gray-700">
            Fecha de publicación
        </label>
        <div class="mt-1">
            <input type="datetime-local" name="fecha_publicacion" id="fecha_publicacion"
                   value="{{ old('fecha_publicacion', isset($noticia) && $noticia->fecha_publicacion ? $noticia->fecha_publicacion->format('Y-m-d\TH:i') : now()->format('Y-m-d\TH:i')) }}"
                   class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const publicadaCheckbox = document.getElementById('publicada');
        const fechaContainer = document.getElementById('fecha_publicacion_container');
        
        function toggleFechaPublicacion() {
            if (publicadaCheckbox.checked) {
                fechaContainer.classList.remove('hidden');
            } else {
                fechaContainer.classList.add('hidden');
            }
        }
        
        // Initial state
        toggleFechaPublicacion();
        
        // Toggle on change
        publicadaCheckbox.addEventListener('change', toggleFechaPublicacion);
    });
</script>
@endpush
