@extends('layouts.fundacion')

@section('title', $noticia->titulo)

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="md:flex md:items-center md:justify-between mb-8">
        <div class="flex-1 min-w-0">
            <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                {{ $noticia->titulo }}
            </h2>
            <div class="mt-1 flex flex-col sm:flex-row sm:flex-wrap sm:mt-0 sm:space-x-6">
                <div class="mt-2 flex items-center text-sm text-gray-500">
                    <i class="far fa-calendar-alt mr-1.5 h-4 w-4 text-gray-400"></i>
                    {{ $noticia->fecha_publicacion ? $noticia->fecha_publicacion->format('d/m/Y H:i') : 'No publicada' }}
                </div>
                <div class="mt-2 flex items-center text-sm text-gray-500">
                    <i class="far fa-user mr-1.5 h-4 w-4 text-gray-400"></i>
                    {{ $noticia->fundacion->nombre }}
                </div>
                <div class="mt-2">
                    @if($noticia->publicada)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            Publicada
                        </span>
                    @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                            Borrador
                        </span>
                    @endif
                </div>
            </div>
        </div>
        <div class="mt-4 flex md:mt-0 md:ml-4 space-x-3">
            <a href="{{ route('fundacion.noticias.edit', $noticia) }}" 
               class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <i class="fas fa-edit -ml-1 mr-2 h-4 w-4"></i>
                Editar
            </a>
            <a href="{{ route('fundacion.noticias.index') }}" 
               class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <i class="fas fa-arrow-left -ml-1 mr-2 h-4 w-4"></i>
                Volver al listado
            </a>
        </div>
    </div>

    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6">
            @if($noticia->imagen)
                <div class="mb-6">
                    <img src="{{ $noticia->imagen_url }}" alt="{{ $noticia->titulo }}" class="w-full h-64 object-cover rounded-lg">
                </div>
            @endif
            
            <div class="prose max-w-none">
                {!! nl2br(e($noticia->contenido)) !!}
            </div>
            
            <div class="mt-8 pt-6 border-t border-gray-200">
                <div class="flex justify-between items-center">
                    <div class="text-sm text-gray-500">
                        Última actualización: {{ $noticia->updated_at->format('d/m/Y H:i') }}
                    </div>
                    <div class="flex space-x-3">
                        @if($noticia->publicada)
                            <a href="{{ route('noticias.show', $noticia) }}" 
                               target="_blank"
                               class="inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <i class="fas fa-external-link-alt mr-1.5 h-4 w-4"></i>
                                Ver en sitio público
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
