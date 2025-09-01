@props(['noticia' => null])

<div class="space-y-6">
    <!-- Título -->
    <div>
        <label for="titulo" class="block text-sm font-medium text-gray-700">Título *</label>
        <input type="text" name="titulo" id="titulo" required
               value="{{ old('titulo', $noticia->titulo ?? '') }}"
               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
               placeholder="Ingresa un título atractivo para tu noticia">
        @error('titulo')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- Contenido -->
    <div>
        <label for="contenido" class="block text-sm font-medium text-gray-700">Contenido *</label>
        <textarea name="contenido" id="contenido" rows="6" required
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">{{ old('contenido', $noticia->contenido ?? '') }}</textarea>
        @error('contenido')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- Resumen -->
    <div>
        <label for="resumen" class="block text-sm font-medium text-gray-700">Resumen</label>
        <textarea name="resumen" id="resumen" rows="3"
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">{{ old('resumen', $noticia->resumen ?? '') }}</textarea>
        <p class="mt-1 text-xs text-gray-500">Breve resumen que aparecerá en el listado de noticias.</p>
        @error('resumen')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- Imagen -->
    <div x-data="{ imagePreview: '{{ $noticia && $noticia->imagen ? asset('img/noticias/' . $noticia->imagen) : '' }}' }">
        <label for="imagen" class="block text-sm font-medium text-gray-700 mb-2">
            {{ $noticia && $noticia->imagen ? 'Cambiar imagen' : 'Imagen de portada' }}
        </label>
        
        <!-- Vista previa de la imagen -->
        <div class="mt-2 flex items-center space-x-4">
            <div class="flex-shrink-0">
                <img x-show="imagePreview" 
                     :src="imagePreview" 
                     alt="Vista previa de la imagen"
                     class="h-32 w-32 object-cover rounded-lg border border-gray-200">
                <div x-show="!imagePreview" 
                     class="h-32 w-32 rounded-lg border-2 border-dashed border-gray-300 flex items-center justify-center bg-gray-50">
                    <svg class="h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
            </div>
            <div class="flex-1">
                <input type="file" 
                       name="imagen" 
                       id="imagen"
                       accept="image/jpeg,image/png,image/jpg,image/gif"
                       x-on:change="{
                           const file = $event.target.files[0];
                           if (file) {
                               const reader = new FileReader();
                               reader.onload = (e) => imagePreview = e.target.result;
                               reader.readAsDataURL(file);
                           }
                       }"
                       class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                <p class="mt-1 text-xs text-gray-500">
                    Formatos aceptados: JPG, PNG, GIF. Tamaño máximo: 2MB
                </p>
                @error('imagen')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>

    <!-- Estado -->
    <div class="flex items-center">
        <input type="hidden" name="publicada" value="0">
        <input type="checkbox" 
               name="publicada" 
               id="publicada" 
               value="1"
               {{ old('publicada', $noticia->publicada ?? false) ? 'checked' : '' }}
               class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
        <label for="publicada" class="ml-2 block text-sm text-gray-700">
            Marcar como publicada
        </label>
        @error('publicada')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- Fecha de publicación -->
    <div x-data="{
        showDatePicker: {{ old('fecha_publicacion', $noticia->fecha_publicacion) ? 'true' : 'false' }},
        minDate: new Date().toISOString().slice(0, 16)
    }">
        <div class="flex items-center">
            <input type="hidden" name="fecha_publicacion" 
                   x-bind:value="showDatePicker ? $refs.fechaInput.value : ''">
            
            <input type="checkbox" 
                   id="programar_publicacion" 
                   x-model="showDatePicker"
                   class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
            <label for="programar_publicacion" class="ml-2 block text-sm text-gray-700">
                Programar publicación
            </label>
        </div>
        
        <div x-show="showDatePicker" class="mt-2">
            <div class="flex items-center space-x-2">
                <input type="datetime-local" 
                       x-ref="fechaInput"
                       :min="minDate"
                       x-bind:value="'{{ old('fecha_publicacion', $noticia->fecha_publicacion ? $noticia->fecha_publicacion->format('Y-m-d\TH:i') : now()->format('Y-m-d\TH:i')) }}'"
                       class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                <button type="button" 
                        @click="$refs.fechaInput.value = minDate; showDatePicker = false;"
                        class="text-gray-500 hover:text-gray-700">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <p class="mt-1 text-xs text-gray-500">
                Selecciona una fecha y hora futura para programar la publicación.
            </p>
            @error('fecha_publicacion')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <!-- Notas adicionales -->
    <div>
        <label for="notas" class="block text-sm font-medium text-gray-700">Notas internas</label>
        <textarea name="notas" id="notas" rows="2"
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">{{ old('notas', $noticia->notas ?? '') }}</textarea>
        <p class="mt-1 text-xs text-gray-500">Estas notas solo serán visibles para administradores y editores.</p>
    </div>
</div>
