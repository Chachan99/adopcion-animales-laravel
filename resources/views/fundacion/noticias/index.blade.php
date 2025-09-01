@extends('layouts.app')

@section('title', 'Mis Noticias')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-2xl font-bold text-gray-800">Mis Noticias</h1>
        <a href="{{ route('fundacion.noticias.create') }}" 
           class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg transition duration-200">
            <i class="fas fa-plus mr-2"></i>Nueva Noticia
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded" role="alert">
            <p>{{ session('success') }}</p>
        </div>
    @endif

    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        @if($noticias->count() > 0)
            <ul class="divide-y divide-gray-200">
                @foreach($noticias as $noticia)
                    <li class="hover:bg-gray-50">
                        <div class="px-4 py-4 sm:px-6">
                            <div class="flex items-center justify-between">
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-indigo-600 truncate">
                                        {{ $noticia->titulo }}
                                    </p>
                                    <p class="mt-1 text-sm text-gray-500 truncate">
                                        {{ $noticia->fecha_publicacion ? $noticia->fecha_publicacion->format('d/m/Y H:i') : 'No publicada' }}
                                        <span class="mx-2">•</span>
                                        @if($noticia->publicada)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                Publicada
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                Borrador
                                            </span>
                                        @endif
                                    </p>
                                </div>
                                <div class="ml-4 flex-shrink-0 flex space-x-4">
                                    <a href="{{ route('fundacion.noticias.edit', $noticia) }}" 
                                       class="text-indigo-600 hover:text-indigo-900">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('fundacion.noticias.destroy', $noticia) }}" method="POST" 
                                          onsubmit="return confirm('¿Estás seguro de eliminar esta noticia?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
            <div class="px-4 py-3 bg-gray-50 sm:px-6">
                {{ $noticias->links() }}
            </div>
        @else
            <div class="px-4 py-5 sm:p-6 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No hay noticias</h3>
                <p class="mt-1 text-sm text-gray-500">
                    Empieza creando una nueva noticia.
                </p>
                <div class="mt-6">
                    <a href="{{ route('fundacion.noticias.create') }}" 
                       class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <i class="fas fa-plus -ml-1 mr-2 h-5 w-5"></i>
                        Nueva Noticia
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
