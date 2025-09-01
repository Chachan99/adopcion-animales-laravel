@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Hero Section -->
    <div class="text-center mb-12">
        <h1 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">
            Noticias y Actualizaciones
        </h1>
        <p class="text-gray-600 max-w-2xl mx-auto">
            Mantente informado sobre las últimas noticias, historias de éxito y actualizaciones de nuestras fundaciones asociadas.
        </p>
        
        <!-- Search Bar -->
        <div class="mt-8 max-w-2xl mx-auto">
            <form action="{{ route('noticias.index') }}" method="GET" class="flex flex-col sm:flex-row gap-4">
                <div class="flex-grow relative">
                    <input type="text" name="search" placeholder="Buscar noticias..." 
                           value="{{ request('search') }}"
                           class="w-full pl-10 pr-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 shadow-sm">
                    <svg class="absolute left-3 top-3 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition-colors shadow-md hover:shadow-lg">
                    Buscar
                </button>
            </form>
        </div>
    </div>

    <!-- Featured News Section -->
    @if($featuredNews->count() > 0)
    <div class="mb-12">
        <h2 class="text-2xl font-semibold text-gray-700 mb-6 flex items-center border-b-2 border-blue-100 pb-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
            </svg>
            Noticias Destacadas
        </h2>
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            @foreach($featuredNews as $noticia)
            <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300 flex flex-col h-full border-2 border-yellow-200">
                <div class="h-64 overflow-hidden bg-gray-100 relative">
                    <a href="{{ route('noticias.show', $noticia) }}">
                        @if($noticia->imagen)
                            <img src="{{ asset('img/noticias/' . $noticia->imagen) }}" 
                                 alt="{{ $noticia->titulo }}" 
                                 class="w-full h-full object-cover transition-transform duration-500 hover:scale-105">
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-gradient-to-r from-blue-50 to-indigo-50">
                                <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        @endif
                        <div class="absolute top-4 right-4 bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded-full flex items-center shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                            Destacada
                        </div>
                    </a>
                </div>
                
                <div class="p-7 flex flex-col flex-grow">
                    <div class="flex justify-between items-center mb-4">
                        <span class="text-sm text-gray-500 flex items-center bg-gray-50 px-3 py-1.5 rounded-full">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            {{ $noticia->publicado_en ? $noticia->publicado_en->translatedFormat('d M Y') : $noticia->created_at->translatedFormat('d M Y') }}
                        </span>
                        <span class="text-sm text-gray-500 flex items-center bg-blue-50 px-3 py-1.5 rounded-full">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            {{ $noticia->vistas ?? 0 }}
                        </span>
                    </div>
                    
                    <h3 class="text-xl font-bold text-gray-900 mb-4 leading-tight group-hover:text-blue-600 transition-colors duration-300">
                        <a href="{{ route('noticias.show', $noticia) }}" class="hover:underline decoration-2 underline-offset-2">{{ $noticia->titulo }}</a>
                    </h3>
                    
                    <p class="text-gray-600 mb-6 line-clamp-3 flex-grow leading-relaxed">
                        @if($noticia->resumen)
                            {{ $noticia->resumen }}
                        @else
                            {{ Str::limit(strip_tags($noticia->contenido), 150) }}
                        @endif
                    </p>
                    
                    <div class="mt-auto pt-5 border-t border-gray-100 flex justify-between items-center">
                        <div class="flex items-center group/author">
                            @if($noticia->fundacion->imagen_perfil)
                                <img class="h-10 w-10 rounded-full object-cover ring-2 ring-blue-100 shadow-md group-hover/author:ring-blue-200 transition-all duration-300" src="{{ asset('img/fundacion/' . $noticia->fundacion->imagen_perfil) }}" alt="{{ $noticia->fundacion->nombre }}">
                            @else
                                <div class="h-10 w-10 rounded-full bg-gradient-to-br from-blue-100 to-indigo-100 flex items-center justify-center ring-2 ring-blue-100 shadow-md group-hover/author:ring-blue-200 transition-all duration-300">
                                    <svg class="h-5 w-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                            @endif
                            <div class="ml-3">
                                <span class="text-sm font-semibold text-gray-800 group-hover/author:text-blue-600 transition-colors duration-300">{{ $noticia->fundacion->nombre }}</span>
                                <p class="text-xs text-gray-500">Fundación</p>
                            </div>
                        </div>
                        <a href="{{ route('noticias.show', $noticia) }}" class="inline-flex items-center px-4 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white text-sm font-semibold rounded-full shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            <span>Leer más</span>
                            <svg class="ml-2 w-4 h-4 transition-transform duration-300 group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- All News Section -->
    <div class="mb-8">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 border-b-2 border-blue-100 pb-2">
            <h2 class="text-2xl font-semibold text-gray-700 flex items-center mb-4 sm:mb-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                </svg>
                Todas las Noticias
            </h2>
            
            <div class="flex items-center space-x-4">
                @if($noticias->count() > 0)
                <span class="text-sm text-gray-500 hidden sm:block">
                    Mostrando {{ $noticias->firstItem() }}-{{ $noticias->lastItem() }} de {{ $noticias->total() }} noticias
                </span>
                @endif
                
                <div class="relative">
                    <select id="sort" onchange="window.location.href = '{{ route('noticias.index') }}?sort=' + this.value" class="appearance-none bg-white border border-gray-300 rounded-md pl-3 pr-8 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 shadow-sm">
                        <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Más recientes primero</option>
                        <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Más antiguas primero</option>
                        <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Más populares</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                            <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
        
        @if($noticias->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($noticias as $noticia)
            <article class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-2xl hover:-translate-y-1 transition-all duration-300 flex flex-col h-full border border-gray-50 group">
                <div class="aspect-[16/10] overflow-hidden bg-gradient-to-br from-gray-100 to-gray-200 relative">
                    <a href="{{ route('noticias.show', $noticia) }}" class="block h-full">
                        @if($noticia->imagen)
                            <img src="{{ asset('img/noticias/' . $noticia->imagen) }}" 
                                 alt="{{ $noticia->titulo }}" 
                                 class="w-full h-full object-cover transition-all duration-700 group-hover:scale-110 group-hover:brightness-110">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/40 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                            <div class="absolute top-4 right-4 bg-white/90 backdrop-blur-sm rounded-full p-2 opacity-0 group-hover:opacity-100 transition-all duration-300 transform translate-y-2 group-hover:translate-y-0">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </div>
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50">
                                <div class="text-center">
                                    <svg class="w-16 h-16 text-gray-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <p class="text-xs text-gray-400 font-medium">Sin imagen</p>
                                </div>
                            </div>
                        @endif
                    </a>
                </div>
                
                <div class="p-6 flex flex-col flex-grow">
                    <div class="flex justify-between items-center mb-3">
                        <span class="text-sm text-gray-500 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            {{ $noticia->publicado_en ? $noticia->publicado_en->translatedFormat('d M Y') : $noticia->created_at->translatedFormat('d M Y') }}
                        </span>
                        <span class="text-sm text-gray-500 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            {{ $noticia->vistas ?? 0 }}
                        </span>
                    </div>
                    
                    <h3 class="text-xl font-bold text-gray-800 mb-3 hover:text-blue-600 transition-colors">
                        <a href="{{ route('noticias.show', $noticia) }}" class="hover:underline">{{ $noticia->titulo }}</a>
                    </h3>
                    
                    <p class="text-gray-600 mb-4 line-clamp-3 flex-grow">
                        @if($noticia->resumen)
                            {{ $noticia->resumen }}
                        @else
                            {{ Str::limit(strip_tags($noticia->contenido), 150) }}
                        @endif
                    </p>
                    
                    <div class="mt-auto pt-4 border-t border-gray-100 flex justify-between items-center">
                        <div class="flex items-center">
                            @if($noticia->fundacion->imagen_perfil)
                                <img class="h-8 w-8 rounded-full object-cover ring-2 ring-white shadow-sm" src="{{ asset('img/fundacion/' . $noticia->fundacion->imagen_perfil) }}" alt="{{ $noticia->fundacion->nombre }}">
                            @else
                                <div class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center ring-2 ring-white shadow-sm">
                                    <svg class="h-5 w-5 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                            @endif
                            <span class="ml-2 text-sm font-medium text-gray-700">{{ $noticia->fundacion->nombre }}</span>
                        </div>
                        <a href="{{ route('noticias.show', $noticia) }}" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-full shadow-sm text-blue-700 bg-blue-100 hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                            Leer más
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="text-center py-12 bg-gray-50 rounded-lg">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <h3 class="mt-4 text-lg font-medium text-gray-700">No hay noticias disponibles</h3>
            <p class="mt-2 text-gray-500">No hemos encontrado noticias que coincidan con tu búsqueda.</p>
            @auth
            <div class="mt-6">
                <a href="{{ route('noticias.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-800 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150 shadow-md hover:shadow-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Crear nueva noticia
                </a>
            </div>
            @endauth
        </div>
        @endif
        
        @if($noticias->hasPages())
        <div class="mt-8 flex flex-col sm:flex-row justify-between items-center">
            <div class="mb-4 sm:mb-0 text-sm text-gray-500">
                Mostrando {{ $noticias->firstItem() }}-{{ $noticias->lastItem() }} de {{ $noticias->total() }} noticias
            </div>
            {{ $noticias->onEachSide(1)->links() }}
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Función para manejar el ordenamiento
    document.getElementById('sort').addEventListener('change', function() {
        const sortValue = this.value;
        const url = new URL(window.location.href);
        url.searchParams.set('sort', sortValue);
        window.location.href = url.toString();
    });
</script>
@endpush