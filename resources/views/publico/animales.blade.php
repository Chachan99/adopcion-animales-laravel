@extends('layouts.app')

@section('content')
<!-- Hero Section -->
<section class="bg-gradient-to-r from-blue-900 via-blue-800 to-cyan-600 text-white py-20 lg:py-32">
  <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-12">
    <div class="text-center">
      <h1 class="text-4xl lg:text-6xl font-extrabold mb-6 leading-tight drop-shadow-lg">
        Nuestros Amigos en Adopción
      </h1>
      <p class="text-xl lg:text-2xl mb-8 text-cyan-100 leading-relaxed drop-shadow-sm max-w-4xl mx-auto">
        Conoce a estos maravillosos animales que están buscando un hogar lleno de amor. Cada uno tiene una historia única y está listo para convertirse en tu nuevo mejor amigo.
      </p>
      <div class="flex flex-col sm:flex-row gap-4 justify-center">
        <a href="{{ route('publico.index') }}" class="inline-block bg-white/20 hover:bg-white/30 text-white border-2 border-white font-extrabold px-8 py-3 rounded-full text-lg shadow-lg hover:shadow-xl transition-all duration-300">
          <i class="fas fa-home mr-2"></i> Volver al Inicio
        </a>
        <a href="{{ route('publico.informacion') }}" class="inline-block bg-cyan-400 hover:bg-cyan-300 text-blue-900 font-extrabold px-8 py-3 rounded-full text-lg shadow-lg hover:shadow-xl transition-all duration-300">
          <i class="fas fa-info-circle mr-2"></i> Guía para Adoptantes
        </a>
      </div>
    </div>
  </div>
</section>

<!-- Filtros -->
<section class="py-12 px-6 bg-gray-50">
  <div class="max-w-7xl mx-auto">
    <div class="bg-white rounded-xl shadow-md p-6 mb-8">
      <h2 class="text-2xl font-bold text-gray-900 mb-6">Filtrar Búsqueda</h2>
      <form action="{{ route('publico.animales') }}" method="GET" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div>
          <label for="tipo" class="block text-sm font-medium text-gray-700 mb-1">Tipo de Animal</label>
          <select id="tipo" name="tipo" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-cyan-500 focus:ring-cyan-500">
            <option value="">Todos</option>
            <option value="perro">Perros</option>
            <option value="gato">Gatos</option>
            <option value="otro">Otros</option>
          </select>
        </div>
        <div>
          <label for="tamano" class="block text-sm font-medium text-gray-700 mb-1">Tamaño</label>
          <select id="tamano" name="tamano" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-cyan-500 focus:ring-cyan-500">
            <option value="">Cualquier tamaño</option>
            <option value="pequeño">Pequeño</option>
            <option value="mediano">Mediano</option>
            <option value="grande">Grande</option>
          </select>
        </div>
        <div>
          <label for="edad" class="block text-sm font-medium text-gray-700 mb-1">Edad</label>
          <select id="edad" name="edad" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-cyan-500 focus:ring-cyan-500">
            <option value="">Cualquier edad</option>
            <option value="cachorro">Cachorro</option>
            <option value="joven">Joven</option>
            <option value="adulto">Adulto</option>
            <option value="mayor">Mayor</option>
          </select>
        </div>
        <div class="flex items-end">
          <button type="submit" class="w-full bg-cyan-600 hover:bg-cyan-700 text-white font-bold py-2 px-6 rounded-lg transition-colors">
            Filtrar
          </button>
        </div>
      </form>
    </div>
  </div>
</section>

<!-- Listado de Animales -->
<section class="py-12 px-6">
  <div class="max-w-7xl mx-auto">
    @if($animales->count() > 0)
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
        @foreach($animales as $animal)
          <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300">
            <!-- Imagen del animal -->
            <div class="h-64 overflow-hidden">
              <img src="{{ $animal->imagen_url }}" alt="{{ $animal->nombre }}" class="w-full h-full object-cover hover:scale-105 transition-transform duration-500">
              <div class="absolute top-4 right-4 bg-{{ $animal->estado === 'disponible' ? 'green' : 'yellow' }}-500 text-white text-xs font-bold px-3 py-1 rounded-full">
                {{ ucfirst($animal->estado) }}
              </div>
            </div>
            
            <!-- Información del animal -->
            <div class="p-6">
              <div class="flex justify-between items-start mb-2">
                <h3 class="text-2xl font-bold text-gray-900">{{ $animal->nombre }}</h3>
                <span class="bg-cyan-100 text-cyan-800 text-xs font-semibold px-2.5 py-0.5 rounded">
                  {{ ucfirst($animal->tipo) }}
                </span>
              </div>
              
              <div class="flex items-center text-gray-600 text-sm mb-4">
                <i class="fas fa-{{ $animal->sexo === 'macho' ? 'mars' : 'venus' }} mr-2"></i>
                <span class="mr-4">{{ ucfirst($animal->sexo) }}</span>
                <i class="fas fa-birthday-cake mr-2"></i>
                <span>{{ $animal->edad }} años</span>
              </div>
              
              <p class="text-gray-700 mb-6 line-clamp-2">
                {{ $animal->descripcion }}
              </p>
              
              <div class="flex items-center justify-between">
                <a href="{{ route('publico.animal', $animal->id) }}" class="text-cyan-600 hover:text-cyan-800 font-medium flex items-center">
                  Ver detalles
                  <i class="fas fa-arrow-right ml-2"></i>
                </a>
                <span class="text-sm text-gray-500">
                  {{ $animal->created_at->diffForHumans() }}
                </span>
              </div>
            </div>
          </div>
        @endforeach
      </div>
      
      <!-- Paginación -->
      <div class="mt-12">
        {{ $animales->links() }}
      </div>
      
    @else
      <div class="text-center py-16 bg-white rounded-xl shadow-md">
        <i class="fas fa-paw text-5xl text-gray-400 mb-4"></i>
        <h3 class="text-2xl font-bold text-gray-900 mb-2">No hay animales disponibles en este momento</h3>
        <p class="text-gray-600 mb-6">Pronto tendremos nuevos amigos buscando hogar. ¡Vuelve a visitarnos!</p>
        <a href="{{ route('publico.index') }}" class="inline-block bg-cyan-600 hover:bg-cyan-700 text-white font-bold py-2 px-6 rounded-lg transition-colors">
          Volver al inicio
        </a>
      </div>
    @endif
  </div>
</section>

<!-- CTA Section -->
<section class="bg-cyan-600 text-white py-16">
  <div class="max-w-4xl mx-auto text-center px-6">
    <h2 class="text-3xl font-bold mb-6">¿No encuentras a tu compañero ideal?</h2>
    <p class="text-xl mb-8">Regístrate para recibir notificaciones cuando lleguen nuevos animales en adopción.</p>
    @guest
      <a href="{{ route('register') }}" class="inline-block bg-white text-cyan-600 hover:bg-gray-100 font-bold py-3 px-8 rounded-full transition-colors">
        Regístrate Ahora
      </a>
    @else
      <p class="text-cyan-100">¡Gracias por estar registrado! Te notificaremos cuando lleguen nuevos amigos.</p>
    @endguest
  </div>
</section>

<!-- Información adicional -->
<section class="py-16 px-6 bg-gray-50">
  <div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-xl shadow-md p-8">
      <h2 class="text-2xl font-bold text-gray-900 mb-6">¿Por qué adoptar?</h2>
      <div class="grid md:grid-cols-2 gap-8">
        <div>
          <h3 class="text-lg font-semibold text-gray-900 mb-3 flex items-center">
            <i class="fas fa-heart text-cyan-600 mr-2"></i> Salvas una vida
          </h3>
          <p class="text-gray-600">Al adoptar, le das una segunda oportunidad a un animal que necesita un hogar amoroso.</p>
        </div>
        <div>
          <h3 class="text-lg font-semibold text-gray-900 mb-3 flex items-center">
            <i class="fas fa-home text-cyan-600 mr-2"></i> Ayudas a otros animales
          </h3>
          <p class="text-gray-600">Al adoptar, liberas espacio en refugios para que puedan rescatar a más animales necesitados.</p>
        </div>
        <div>
          <h3 class="text-lg font-semibold text-gray-900 mb-3 flex items-center">
            <i class="fas fa-smile text-cyan-600 mr-2"></i> Mejoras tu vida
          </h3>
          <p class="text-gray-600">Las mascotas reducen el estrés, la ansiedad y la depresión, y te brindan compañía incondicional.</p>
        </div>
        <div>
          <h3 class="text-lg font-semibold text-gray-900 mb-3 flex items-center">
            <i class="fas fa-hand-holding-heart text-cyan-600 mr-2"></i> Apoyas una causa justa
          </h3>
          <p class="text-gray-600">Tu adopción ayuda a combatir el abandono y maltrato animal.</p>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection
