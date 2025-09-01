@extends('layouts.app')

@section('content')
<!-- Hero Section -->
<section class="bg-gradient-to-r from-blue-900 via-blue-800 to-cyan-600 text-white py-16">
    <div class="max-w-7xl mx-auto px-6 text-center">
        <h1 class="text-4xl md:text-5xl font-bold mb-4">Últimas Noticias</h1>
        <p class="text-xl text-blue-100 max-w-3xl mx-auto">Mantente informado sobre nuestros rescates, historias de éxito y campañas especiales.</p>
    </div>
</section>

<!-- Noticias Section -->
<section class="py-16 px-6">
    <div class="max-w-7xl mx-auto">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($noticias as $noticia)
            <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300">
                <div class="h-48 overflow-hidden">
                    <img src="{{ $noticia['imagen'] }}" alt="{{ $noticia['titulo'] }}" class="w-full h-full object-cover">
                </div>
                <div class="p-6">
                    <div class="text-blue-600 text-sm font-semibold mb-2">{{ $noticia['fecha'] }}</div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">{{ $noticia['titulo'] }}</h3>
                    <p class="text-gray-600 mb-4">{{ $noticia['resumen'] }}</p>
                    <a href="#" class="text-cyan-600 font-semibold hover:text-cyan-700 transition-colors">Leer más →</a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="bg-cyan-50 py-16">
    <div class="max-w-4xl mx-auto text-center px-6">
        <h2 class="text-3xl font-bold text-gray-900 mb-6">¿Quieres ayudar a más animales?</h2>
        <p class="text-gray-600 mb-8 text-lg">Tu apoyo puede marcar la diferencia en la vida de muchos animales que necesitan un hogar.</p>
        <a href="{{ route('publico.donaciones') }}" class="inline-block bg-cyan-600 hover:bg-cyan-700 text-white font-bold py-3 px-8 rounded-full transition-colors">
            Haz una donación
        </a>
    </div>
</section>
@endsection
