@extends('layouts.app')

@section('title', $noticia->titulo . ' | ' . config('app.name'))

@section('content')
<article class="container mx-auto px-4 py-8 max-w-4xl">
    <!-- Breadcrumb navigation -->
    <nav class="mb-6" aria-label="Navegación">
        <ol class="flex items-center space-x-2 text-sm">
            <li>
                <a href="{{ route('publico.index') }}" class="text-indigo-600 hover:text-indigo-800 transition-colors">Inicio</a>
            </li>
            <li class="text-gray-500">/</li>
            <li>
                <a href="{{ route('noticias.index') }}" class="text-indigo-600 hover:text-indigo-800 transition-colors">Noticias</a>
            </li>
            <li class="text-gray-500">/</li>
            <li class="text-gray-600 truncate max-w-xs" aria-current="page">{{ $noticia->titulo }}</li>
        </ol>
    </nav>

    <!-- Main content -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <!-- Featured image with loading="lazy" -->
        <div class="mb-6 rounded-t-xl overflow-hidden bg-gray-100">
            @if($noticia->imagen && file_exists(public_path('img/noticias/' . $noticia->imagen)))
                <img src="{{ asset('img/noticias/' . $noticia->imagen) }}" 
                     alt="{{ $noticia->titulo }}" 
                     class="w-full h-auto max-h-[32rem] object-cover"
                     loading="lazy"
                     onerror="this.onerror=null;this.src='{{ asset('img/defaults/noticia-default.jpg') }}'">
            @else
                <div class="w-full h-64 bg-gradient-to-r from-indigo-50 to-purple-50 flex items-center justify-center">
                    <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
            @endif
        </div>
        
        <!-- Article header -->
        <div class="px-6 pb-6">
            <!-- Meta information -->
            <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-4">
                <div class="flex flex-wrap items-center gap-4">
                    <span class="inline-flex items-center text-sm text-gray-500">
                        <i class="far fa-calendar-alt mr-1.5"></i>
                        @if($noticia->publicado_en)
                            {{ $noticia->publicado_en->isoFormat('LL') }}
                        @else
                            {{ $noticia->created_at->isoFormat('LL') }}
                        @endif
                    </span>
                    <span class="inline-flex items-center text-sm text-gray-500">
                        <i class="far fa-eye mr-1.5"></i>
                        {{ number_format($noticia->vistas, 0, ',', '.') }} {{ $noticia->vistas == 1 ? 'visualización' : 'visualizaciones' }}
                    </span>
                    @if($noticia->categoria)
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                        {{ $noticia->categoria->nombre }}
                    </span>
                    @endif
                </div>
                
                @php
                    $fundacion = $noticia->fundacion;
                @endphp
                @if($fundacion && $fundacion->exists)
                <div class="mt-3 md:mt-0">
                    <a href="{{ route('publico.fundacion', $fundacion) }}" class="group flex items-center">
                        @if($fundacion->imagen_perfil && file_exists(public_path('img/fundacion/' . $fundacion->imagen_perfil)))
                            <img class="h-8 w-8 rounded-full mr-2 object-cover border border-gray-200 group-hover:border-indigo-300 transition-colors" 
                                 src="{{ asset('img/fundacion/' . $fundacion->imagen_perfil) }}" 
                                 alt="{{ $fundacion->nombre }}"
                                 loading="lazy">
                        @else
                            <div class="h-8 w-8 rounded-full bg-indigo-100 flex items-center justify-center mr-2 border border-gray-200 group-hover:border-indigo-300 transition-colors">
                                <i class="fas fa-paw text-indigo-500 text-sm"></i>
                            </div>
                        @endif
                        <span class="text-sm font-medium text-gray-700 group-hover:text-indigo-600 transition-colors">
                            {{ $fundacion->nombre }}
                        </span>
                    </a>
                </div>
                @endif
            </div>
            
            <!-- Title -->
            <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-6 leading-tight">{{ $noticia->titulo }}</h1>
            
            <!-- Social sharing buttons -->
            <div class="flex items-center space-x-4 mb-6">
                <span class="text-sm text-gray-500">Compartir:</span>
                <button onclick="shareOnSocial('facebook')" class="text-gray-500 hover:text-blue-600 transition-colors" aria-label="Compartir en Facebook">
                    <i class="fab fa-facebook-f text-lg"></i>
                </button>
                <button onclick="shareOnSocial('twitter')" class="text-gray-500 hover:text-blue-400 transition-colors" aria-label="Compartir en Twitter">
                    <i class="fab fa-twitter text-lg"></i>
                </button>
                <button onclick="shareOnSocial('linkedin')" class="text-gray-500 hover:text-blue-700 transition-colors" aria-label="Compartir en LinkedIn">
                    <i class="fab fa-linkedin-in text-lg"></i>
                </button>
                <button onclick="shareNews()" class="text-gray-500 hover:text-indigo-600 transition-colors" aria-label="Compartir en otras redes">
                    <i class="fas fa-share-alt text-lg"></i>
                </button>
            </div>
        </div>
        
        <!-- Article content -->
        <div class="px-6 pb-8">
            <div class="prose prose-indigo max-w-none text-gray-700 mb-8">
                {!! $noticia->contenido !!}
            </div>
            
            <!-- Tags -->
            @if(isset($noticia->tags) && $noticia->tags->count() > 0)
            <div class="mt-8 pt-6 border-t border-gray-200">
                <h3 class="text-sm font-medium text-gray-500 mb-3">Etiquetas:</h3>
                <div class="flex flex-wrap gap-2">
                    @foreach($noticia->tags as $tag)
                    <a href="{{ route('noticias.index', ['tag' => $tag->slug]) }}" class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800 hover:bg-indigo-100 hover:text-indigo-800 transition-colors">
                        #{{ $tag->nombre }}
                    </a>
                    @endforeach
                </div>
            </div>
            @endif
            
            <!-- Author/foundation info -->
            <div class="mt-8 pt-6 border-t border-gray-200">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        @if($noticia->fundacion->imagen_perfil)
                            <img class="h-12 w-12 rounded-full border border-gray-200" 
                                 src="{{ asset('storage/' . $noticia->fundacion->imagen_perfil) }}" 
                                 alt="{{ $noticia->fundacion->nombre }}"
                                 loading="lazy">
                        @else
                            <div class="h-12 w-12 rounded-full bg-indigo-100 flex items-center justify-center border border-gray-200">
                                <i class="fas fa-paw text-indigo-500 text-xl"></i>
                            </div>
                        @endif
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-900">
                            <a href="{{ route('publico.fundacion', $noticia->fundacion) }}" class="hover:text-indigo-600 transition-colors">
                                {{ $noticia->fundacion->nombre }}
                            </a>
                        </p>
                        <p class="text-sm text-gray-500">Fundación de protección animal</p>
                        @if($noticia->fundacion->descripcion_corta)
                        <p class="text-sm text-gray-600 mt-1 line-clamp-2">
                            {{ $noticia->fundacion->descripcion_corta }}
                        </p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Related news -->
    @if(isset($relacionadas) && $relacionadas->count() > 0)
    <div class="mt-16">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">
            @if($noticia->fundacion)
                Más noticias de {{ $noticia->fundacion->nombre }}
            @else
                Otras noticias que te pueden interesar
            @endif
        </h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($relacionadas as $relacionada)
            <article class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300 flex flex-col h-full">
                @if($relacionada->imagen)
                <div class="h-48 overflow-hidden">
                    <a href="{{ route('noticias.show', $relacionada) }}" class="block h-full">
                        <img src="{{ $relacionada->imagen_url }}" 
                             alt="{{ $relacionada->titulo }}" 
                             class="w-full h-full object-cover hover:scale-105 transition-transform duration-500"
                             loading="lazy">
                    </a>
                </div>
                @endif
                <div class="p-4 flex-grow flex flex-col">
                    <div class="mb-2">
                        @if($relacionada->categoria)
                        <span class="inline-block px-2 py-1 rounded text-xs font-medium bg-indigo-100 text-indigo-800 mb-2">
                            {{ $relacionada->categoria->nombre }}
                        </span>
                        @endif
                        <h3 class="font-semibold text-lg mb-2 hover:text-indigo-600 transition-colors">
                            <a href="{{ route('noticias.show', $relacionada) }}">{{ $relacionada->titulo }}</a>
                        </h3>
                        <p class="text-sm text-gray-500 mb-3">
                            <i class="far fa-calendar-alt mr-1"></i> 
                            {{ $relacionada->fecha_publicacion->isoFormat('LL') }}
                        </p>
                        <p class="text-gray-600 text-sm line-clamp-2 mb-4">
                            {{ \Illuminate\Support\Str::limit(strip_tags($relacionada->contenido), 100) }}
                        </p>
                    </div>
                    <div class="mt-auto">
                        <a href="{{ route('noticias.show', $relacionada) }}" 
                           class="text-indigo-600 hover:text-indigo-800 text-sm font-medium inline-flex items-center transition-colors">
                            Leer más
                            <i class="fas fa-arrow-right ml-1.5 text-xs transition-transform group-hover:translate-x-1"></i>
                        </a>
                    </div>
                </div>
            </article>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Navigation footer -->
    <div class="mt-12 pt-8 border-t border-gray-200">
        <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
            <a href="{{ route('noticias.index') }}" 
               class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i> Volver a todas las noticias
            </a>
            
            <div class="flex items-center space-x-4">
                <button onclick="window.scrollTo({top: 0, behavior: 'smooth'})" 
                        class="text-gray-600 hover:text-indigo-600 transition-colors inline-flex items-center"
                        aria-label="Volver arriba">
                    <i class="fas fa-arrow-up mr-1"></i> Arriba
                </button>
                <button onclick="shareNews()" 
                        class="text-gray-600 hover:text-indigo-600 transition-colors inline-flex items-center"
                        aria-label="Compartir noticia">
                    <i class="fas fa-share-alt mr-1"></i> Compartir
                </button>
            </div>
        </div>
    </div>
</article>

@push('scripts')
<script>
// Web Share API
function shareNews() {
    const shareTitle = '{{ addslashes($noticia->titulo) }}';
    const shareText = '{{ addslashes(Str::limit(strip_tags($noticia->contenido), 100)) }}';
    
    if (navigator.share) {
        navigator.share({
            title: shareTitle,
            text: shareText,
            url: window.location.href
        }).catch(console.error);
    } else {
        // Fallback: copy to clipboard
        navigator.clipboard.writeText(window.location.href).then(() => {
            alert('Enlace copiado al portapapeles. Puedes compartirlo donde quieras.');
        }).catch(err => {
            console.error('Error al copiar: ', err);
            const emailSubject = encodeURIComponent(shareTitle);
            const emailBody = encodeURIComponent('Mira esta noticia: ' + shareTitle + ' - ' + window.location.href);
            window.open('mailto:?subject=' + emailSubject + '&body=' + emailBody, '_blank');
        });
    }
}

// Social media sharing
function shareOnSocial(network) {
    const url = encodeURIComponent(window.location.href);
    const title = encodeURIComponent('{{ addslashes($noticia->titulo) }}');
    
    const shareUrls = {
        'facebook': `https://www.facebook.com/sharer/sharer.php?u=${url}`,
        'twitter': `https://twitter.com/intent/tweet?url=${url}&text=${title}`,
        'linkedin': `https://www.linkedin.com/shareArticle?mini=true&url=${url}&title=${title}`
    };
    
    if (shareUrls[network]) {
        window.open(shareUrls[network], '_blank', 'width=600,height=400');
    }
}
</script>
@endpush

@endsection