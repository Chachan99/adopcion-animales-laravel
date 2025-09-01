@extends('layouts.admin')

@section('title', $noticia->exists ? 'Editar Noticia' : 'Crear Noticia')

@push('styles')
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<style>
    .ql-container {
        min-height: 200px;
    }
    .editor-container {
        height: 300px;
        margin-bottom: 20px;
    }
</style>
@endpush

@section('contenido')
<div class="bg-white shadow-lg rounded-lg p-6">
    <h2 class="text-2xl font-semibold text-gray-800 mb-6">
        {{ $noticia->exists ? 'Editar Noticia' : 'Crear Nueva Noticia' }}
    </h2>

    <form action="{{ $noticia->exists ? route('admin.noticias.update', $noticia) : route('admin.noticias.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @if($noticia->exists)
            @method('PUT')
        @endif

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Columna izquierda -->
            <div class="md:col-span-2 space-y-6">
                <!-- Título -->
                <div>
                    <label for="titulo" class="block text-sm font-medium text-gray-700 mb-1">Título *</label>
                    <input type="text" name="titulo" id="titulo" value="{{ old('titulo', $noticia->titulo) }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           required>
                    @error('titulo')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Contenido -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Contenido *</label>
                    <input type="hidden" name="contenido" id="contenido">
                    <div id="editor-container" class="border border-gray-300 rounded-lg overflow-hidden">
                        {!! old('contenido', $noticia->contenido) !!}
                    </div>
                    @error('contenido')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Resumen -->
                <div>
                    <label for="resumen" class="block text-sm font-medium text-gray-700 mb-1">Resumen (opcional)</label>
                    <textarea name="resumen" id="resumen" rows="3"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('resumen', $noticia->resumen) }}</textarea>
                    <p class="mt-1 text-xs text-gray-500">Breve descripción que aparecerá en el listado de noticias.</p>
                </div>
            </div>

            <!-- Columna derecha -->
            <div class="space-y-6">
                <!-- Imagen -->
                <div class="bg-gray-50 p-4 rounded-lg">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Imagen destacada</label>
                    
                    @if($noticia->imagen)
                    <div class="mb-4">
                        <img src="{{ asset('storage/noticias/' . $noticia->imagen) }}" alt="{{ $noticia->titulo }}" class="w-full h-48 object-cover rounded-lg mb-2">
                        <label class="flex items-center">
                            <input type="checkbox" name="eliminar_imagen" class="rounded text-blue-500">
                            <span class="ml-2 text-sm text-gray-600">Eliminar imagen</span>
                        </label>
                    </div>
                    @endif
                    
                    <div class="mt-2">
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                            <div class="space-y-1 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="flex text-sm text-gray-600">
                                    <label for="imagen" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none">
                                        <span>Subir una imagen</span>
                                        <input id="imagen" name="imagen" type="file" class="sr-only" accept="image/*">
                                    </label>
                                    <p class="pl-1">o arrastra y suelta</p>
                                </div>
                                <p class="text-xs text-gray-500">
                                    PNG, JPG, GIF hasta 2MB
                                </p>
                            </div>
                        </div>
                    </div>
                    @error('imagen')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Estado -->
                <div class="bg-gray-50 p-4 rounded-lg">
                    <label for="estado" class="block text-sm font-medium text-gray-700 mb-2">Estado</label>
                    <select name="estado" id="estado" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="borrador" {{ old('estado', $noticia->estado) === 'borrador' ? 'selected' : '' }}>Borrador</option>
                        <option value="publicada" {{ old('estado', $noticia->estado) === 'publicada' ? 'selected' : '' }}>Publicada</option>
                        <option value="archivada" {{ old('estado', $noticia->estado) === 'archivada' ? 'selected' : '' }}>Archivada</option>
                    </select>
                </div>

                <!-- Fecha de publicación -->
                <div class="bg-gray-50 p-4 rounded-lg">
                    <label for="publicado_en" class="block text-sm font-medium text-gray-700 mb-2">Fecha de publicación</label>
                    <input type="datetime-local" name="publicado_en" id="publicado_en"
                           value="{{ old('publicado_en', $noticia->publicado_en ? $noticia->publicado_en->format('Y-m-d\TH:i') : '') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <p class="mt-1 text-xs text-gray-500">Dejar en blanco para publicar inmediatamente</p>
                </div>

                <!-- Opciones adicionales -->
                <div class="bg-gray-50 p-4 rounded-lg space-y-4">
                    <div class="flex items-start">
                        <div class="flex items-center h-5">
                            <input id="destacada" name="destacada" type="checkbox" 
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                   {{ old('destacada', $noticia->destacada) ? 'checked' : '' }}>
                        </div>
                        <div class="ml-3 text-sm">
                            <label for="destacada" class="font-medium text-gray-700">Destacada</label>
                            <p class="text-gray-500">Mostrar esta noticia como destacada</p>
                        </div>
                    </div>

                    <div>
                        <label for="autor" class="block text-sm font-medium text-gray-700 mb-1">Autor</label>
                        <input type="text" name="autor" id="autor" value="{{ old('autor', $noticia->autor) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <p class="mt-1 text-xs text-gray-500">Dejar en blanco para usar tu nombre</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Botones de acción -->
        <div class="mt-8 pt-6 border-t border-gray-200 flex justify-end space-x-3">
            <a href="{{ route('admin.noticias.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                Cancelar
            </a>
            <button type="submit" class="px-6 py-2 border border-transparent rounded-lg shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                {{ $noticia->exists ? 'Actualizar' : 'Publicar' }} Noticia
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Inicializar el editor Quill
        const toolbarOptions = [
            ['bold', 'italic', 'underline', 'strike'],
            ['blockquote', 'code-block'],
            [{ 'header': 1 }, { 'header': 2 }],
            [{ 'list': 'ordered'}, { 'list': 'bullet' }],
            [{ 'script': 'sub'}, { 'script': 'super' }],
            [{ 'indent': '-1'}, { 'indent': '+1' }],
            [{ 'direction': 'rtl' }],
            [{ 'size': ['small', false, 'large', 'huge'] }],
            [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
            [{ 'color': [] }, { 'background': [] }],
            [{ 'font': [] }],
            [{ 'align': [] }],
            ['clean'],
            ['link', 'image']
        ];

        const quill = new Quill('#editor-container', {
            theme: 'snow',
            modules: {
                toolbar: toolbarOptions
            },
            placeholder: 'Escribe el contenido de la noticia aquí...',
        });

        // Establecer el contenido inicial si existe
        const contenido = document.querySelector('#contenido');
        if (contenido && contenido.value) {
            quill.root.innerHTML = contenido.value;
        }

        // Actualizar el campo oculto con el contenido HTML antes de enviar el formulario
        const form = document.querySelector('form');
        form.onsubmit = function() {
            const editor = document.querySelector('#editor-container .ql-editor');
            document.querySelector('#contenido').value = editor.innerHTML;
            return true;
        };
    });
</script>
@endpush
