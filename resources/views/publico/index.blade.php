@extends('layouts.app')

@section('content')

<!-- Hero Section -->
<section class="bg-gradient-to-r from-blue-900 via-blue-800 to-cyan-600 text-white py-20 lg:py-32">
  <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-12">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
      <!-- Left Content -->
      <div>
        <h1 class="text-4xl lg:text-6xl font-extrabold mb-6 leading-tight drop-shadow-lg">
          Encuentra a tu nuevo mejor amigo
        </h1>
        <p class="text-xl lg:text-2xl mb-8 text-cyan-100 leading-relaxed drop-shadow-sm">
          Descubre increíbles mascotas esperando un hogar amoroso. Explora nuestros animales destacados y encuentra el compañero perfecto para tu familia.
        </p>
        <div class="space-y-5 mb-10">
          <div class="flex items-center space-x-3">
            <div class="w-4 h-4 bg-cyan-400 rounded-full flex-shrink-0"></div>
            <span class="text-lg font-semibold">Adopta un Perro</span>
            <a class="ml-auto text-cyan-300 hover:text-white underline font-medium transition-colors duration-200" href="{{ route('publico.buscar') }}?tipo=perro">
              Ver Perros <i class="fas fa-paw ml-1"></i>
            </a>
          </div>
          <div class="flex items-center space-x-3">
            <div class="w-4 h-4 bg-cyan-400 rounded-full flex-shrink-0"></div>
            <span class="text-lg font-semibold">Adopta un Gato</span>
            <a class="ml-auto text-cyan-300 hover:text-white underline font-medium transition-colors duration-200" href="{{ route('publico.buscar') }}?tipo=gato">
              Ver Gatos <i class="fas fa-cat ml-1"></i>
            </a>
          </div>
          <div class="flex items-center space-x-3">
            <div class="w-4 h-4 bg-cyan-400 rounded-full flex-shrink-0"></div>
            <span class="text-lg font-semibold">Adopta Mascotas Pequeñas</span>
            <a class="ml-auto text-cyan-300 hover:text-white underline font-medium transition-colors duration-200" href="{{ route('publico.buscar') }}?tipo=pequeño">
              Ver Mascotas Pequeñas <i class="fas fa-dove ml-1"></i>
            </a>
          </div>
        </div>
        <div class="flex flex-col sm:flex-row gap-4 mt-6">
          <a class="inline-block bg-cyan-400 hover:bg-cyan-300 text-blue-900 font-extrabold px-8 py-4 rounded-full text-lg shadow-lg hover:shadow-xl transition-all duration-300 tracking-wide text-center" href="{{ route('publico.buscar') }}">
            Comenzar proceso de adopción <i class="fas fa-heart ml-2"></i>
          </a>
          <a class="inline-block bg-white/20 hover:bg-white/30 text-white border-2 border-white font-extrabold px-8 py-4 rounded-full text-lg shadow-lg hover:shadow-xl transition-all duration-300 tracking-wide text-center" href="{{ route('publico.informacion') }}">
            Infórmate antes de adoptar <i class="fas fa-info-circle ml-2"></i>
          </a>
        </div>
      </div>
      
      <!-- Right Image -->
      <div class="flex justify-center lg:justify-end">
        <!-- Carrusel de mascotas -->
        <div 
          x-data="{
              active: 0, 
              total: {{ count($animales) }},
              start() {
                  setInterval(() => {
                      this.active = (this.active + 1) % this.total;
                  }, 2000);
              }
          }"
          x-init="start()"
          class="relative rounded-3xl shadow-2xl border-4 border-cyan-300 overflow-hidden w-80 h-80 lg:w-96 lg:h-96 flex items-center justify-center bg-white"
        >
          @foreach($animales as $index => $animal)
              <div x-show="active === {{ $index }}" class="absolute inset-0 flex flex-col items-center justify-center transition-all duration-500">
                  <img 
                      alt="Foto de {{ $animal->nombre }}, un {{ $animal->tipo }} adorable listo para adopción"
                      class="object-cover w-full h-full rounded-3xl"
                      src="{{ $animal->imagen_url }}"
                      onerror="this.onerror=null; this.src='{{ asset('img/defaults/animal-default.jpg') }}'"
                  />
                  <div class="absolute bottom-0 left-0 right-0 bg-cyan-900 bg-opacity-70 text-white text-lg font-bold py-2 text-center">
                      {{ $animal->nombre }}
                  </div>
              </div>
          @endforeach
          <!-- Botones de navegación -->
          <button @click="active = active === 0 ? total - 1 : active - 1"
              class="absolute left-2 top-1/2 -translate-y-1/2 bg-cyan-400 text-white rounded-full p-2 shadow hover:bg-cyan-600 transition">
              <i class="fas fa-chevron-left"></i>
          </button>
          <button @click="active = active === total - 1 ? 0 : active + 1"
              class="absolute right-2 top-1/2 -translate-y-1/2 bg-cyan-400 text-white rounded-full p-2 shadow hover:bg-cyan-600 transition">
              <i class="fas fa-chevron-right"></i>
          </button>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Search Section -->
<section class="py-12 lg:py-16 px-6 lg:px-12 bg-cyan-50">
  <div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-3xl shadow-xl p-10 border-2 border-cyan-200">
      <h2 class="text-3xl lg:text-4xl font-extrabold text-blue-900 mb-8 text-center tracking-wide">
        Encuentra tu mejor amigo
      </h2>
      <form action="{{ route('publico.buscar') }}" class="grid grid-cols-1 md:grid-cols-4 gap-6" method="GET">
        <div>
          <label class="block text-sm font-semibold text-blue-900 mb-3" for="tipo">Tipo</label>
          <select class="w-full rounded-xl border border-cyan-300 shadow-sm px-4 py-3 text-blue-900 font-medium focus:outline-none focus:ring-2 focus:ring-cyan-400 focus:border-cyan-500 transition" id="tipo" name="tipo">
            <option value="" {{ request('tipo') == '' ? 'selected' : '' }}>Todos</option>
            <option value="perro" {{ request('tipo') == 'perro' ? 'selected' : '' }}>Perro</option>
            <option value="gato" {{ request('tipo') == 'gato' ? 'selected' : '' }}>Gato</option>
            <option value="pequeño" {{ request('tipo') == 'pequeño' ? 'selected' : '' }}>Mascotas Pequeñas</option>
          </select>
        </div>
        <div>
          <label class="block text-sm font-semibold text-blue-900 mb-3" for="edad_min">Edad Mínima</label>
          <input class="w-full rounded-xl border border-cyan-300 shadow-sm px-4 py-3 text-blue-900 font-medium focus:outline-none focus:ring-2 focus:ring-cyan-400 focus:border-cyan-500 transition" id="edad_min" min="0" name="edad_min" placeholder="0" type="number" value="{{ request('edad_min') }}"/>
        </div>
        <div>
          <label class="block text-sm font-semibold text-blue-900 mb-3" for="edad_max">Edad Máxima</label>
          <input class="w-full rounded-xl border border-cyan-300 shadow-sm px-4 py-3 text-blue-900 font-medium focus:outline-none focus:ring-2 focus:ring-cyan-400 focus:border-cyan-500 transition" id="edad_max" min="0" name="edad_max" placeholder="10" type="number" value="{{ request('edad_max') }}"/>
        </div>
        <div class="flex items-end">
          <button class="w-full bg-gradient-to-r from-blue-900 to-cyan-400 text-white px-6 py-4 rounded-xl font-extrabold shadow-lg hover:from-cyan-600 hover:to-blue-800 transition-all duration-300 tracking-wide" type="submit">
            <i class="fas fa-search mr-2"></i>
            Buscar
          </button>
        </div>
      </form>
    </div>
  </div>
</section>

<!-- Animales Destacados -->
<section class="py-12 lg:py-20 px-6 lg:px-12">
  <div class="max-w-7xl mx-auto">
    <h2 class="text-4xl font-extrabold text-blue-900 text-center mb-14 tracking-wide">
      Animales Destacados
    </h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-10">
      @forelse($animales as $animal)
      <div class="flip-card h-[500px] perspective-1000 group">
        <div class="flip-card-inner relative w-full h-full transition-transform duration-700 transform-style-preserve-3d group-hover:rotate-y-180">
          <!-- Front of Card -->
          <div class="flip-card-front absolute w-full h-full backface-hidden bg-white rounded-3xl shadow-xl p-8 flex flex-col border-2 border-cyan-100 hover:shadow-2xl hover:border-cyan-400 transition-all duration-300 items-center text-center">
            <div class="aspect-w-1 aspect-h-1 w-full mb-4 rounded-lg overflow-hidden border-4 border-cyan-200 shadow">
              <img 
                alt="Foto de {{ $animal->nombre }}, un {{ $animal->tipo }} adorable listo para adopción" 
                class="object-cover w-full h-full transition-transform duration-300 hover:scale-105" 
                src="{{ $animal->imagen_url }}"
                onerror="this.onerror=null; this.src='{{ asset('img/defaults/animal-default.jpg') }}'"
                loading="lazy"
              />
            </div>
            <h3 class="text-2xl font-extrabold mb-2 text-blue-900">
              {{ $animal->nombre }}
            </h3>
            <div class="flex items-center justify-center gap-2 mb-2">
              <span class="px-3 py-1 bg-blue-100 text-blue-800 text-sm font-medium rounded-full">
                {{ ucfirst($animal->tipo) }}
              </span>
              <span class="px-3 py-1 bg-cyan-100 text-cyan-800 text-sm font-medium rounded-full">
                {{ $animal->edad }} {{ $animal->tipo_edad === 'anios' ? 'años' : 'meses' }}
              </span>
              <span class="px-3 py-1 bg-green-100 text-green-800 text-sm font-medium rounded-full">
                {{ ucfirst($animal->sexo) }}
              </span>
            </div>
            <p class="text-gray-600 mb-4 line-clamp-2">
              {{ $animal->descripcion }}
            </p>
            <div class="mt-auto">
              <span class="text-blue-600 text-sm font-medium">¡Toca para ver más detalles!</span>
            </div>
          </div>
          
          <!-- Back of Card -->
          <div class="flip-card-back absolute w-full h-full backface-hidden bg-white rounded-3xl shadow-xl p-8 flex flex-col border-2 border-cyan-400 transform rotate-y-180 overflow-y-auto">
            <h3 class="text-2xl font-extrabold mb-4 text-blue-900">
              {{ $animal->nombre }}
            </h3>
            <div class="text-left mb-4">
              <p class="text-gray-700 mb-2"><span class="font-semibold text-blue-900">Edad:</span> {{ $animal->edad }} {{ $animal->tipo_edad === 'anios' ? 'años' : 'meses' }}</p>
              <p class="text-gray-700 mb-2"><span class="font-semibold text-blue-900">Sexo:</span> {{ $animal->sexo === 'macho' ? 'Macho' : 'Hembra' }}</p>
              <p class="text-gray-700 mb-2"><span class="font-semibold text-blue-900">Tamaño:</span> {{ ucfirst($animal->tamanio ?? 'Mediano') }}</p>
              <p class="text-gray-700 mb-2"><span class="font-semibold text-blue-900">Personalidad:</span> {{ $animal->personalidad ?? 'Amigable y cariñoso' }}</p>
            </div>
            <div class="mb-4">
              <h4 class="font-bold text-blue-900 mb-2">Descripción:</h4>
              <p class="text-gray-600 text-sm">{{ $animal->descripcion ?? 'Este adorable animal está buscando un hogar amoroso donde pueda recibir todo el cariño que merece.' }}</p>
            </div>
            <div class="mt-auto w-full">
              @auth
              <form action="{{ route('adopcion.store') }}" class="w-full" method="POST">
                @csrf
                <input name="animal_id" type="hidden" value="{{ $animal->id }}"/>
                <button class="w-full bg-gradient-to-r from-cyan-400 to-blue-900 text-white px-5 py-3 rounded-full font-extrabold shadow-lg hover:from-blue-800 hover:to-cyan-600 transition-all duration-300 tracking-wide" type="submit">
                  Solicitar Adopción <i class="fas fa-hand-holding-heart ml-2"></i>
                </button>
              </form>
              @else
              <a class="w-full bg-gradient-to-r from-cyan-400 to-blue-900 text-white px-5 py-3 rounded-full font-extrabold shadow-lg hover:from-blue-800 hover:to-cyan-600 transition-all duration-300 tracking-wide" href="{{ route('publico.animal', $animal->id) }}">
                Ver detalle <i class="fas fa-sign-in-alt ml-2"></i>
              </a>
              @endauth
            </div>
          </div>
        </div>
      </div>
      @empty
      <div class="col-span-3 text-center py-16">
        <p class="text-xl text-gray-600 font-semibold">
          No hay animales disponibles en este momento.
        </p>
      </div>
      @endforelse
    </div>
    @if($animales->hasPages())
    <div class="mt-16 flex justify-center">
      {{ $animales->links() }}
    </div>
    @endif
  </div>
</section>

<!-- Donaciones Section -->
<section class="py-16 px-6 lg:px-12 bg-gradient-to-br from-cyan-50 to-blue-50">
  <div class="max-w-4xl mx-auto text-center">
    <h2 class="text-4xl font-extrabold text-blue-900 mb-8 tracking-wide">
      ¡Ayuda a nuestros amigos peludos!
    </h2>
    <p class="text-xl text-gray-700 mb-10 leading-relaxed max-w-3xl mx-auto">
      ¡Tu ayuda es fundamental para mantener nuestras fundaciones funcionando! Cada donación hace posible que más animales encuentren un hogar amoroso.
    </p>
    <a class="inline-block bg-gradient-to-r from-green-600 to-cyan-600 text-white px-10 py-5 rounded-full font-extrabold text-lg shadow-lg hover:shadow-xl transition-all duration-300 tracking-wide" href="{{ route('publico.donaciones') }}">
      Hacer una Donación <i class="fas fa-donate ml-3"></i>
    </a>
  </div>
</section>

<style>
  .flip-card {
    perspective: 1000px;
  }
  .flip-card-inner {
    position: relative;
    width: 100%;
    height: 100%;
    transition: transform 0.7s;
    transform-style: preserve-3d;
  }
  .flip-card:hover .flip-card-inner {
    transform: rotateY(180deg);
  }
  .flip-card-front, .flip-card-back {
    position: absolute;
    width: 100%;
    height: 100%;
    backface-visibility: hidden;
  }
  .flip-card-back {
    transform: rotateY(180deg);
  }
  .transform-rotate-y-180 {
    transform: rotateY(180deg);
  }
  .backface-hidden {
    backface-visibility: hidden;
  }
  .transform-style-preserve-3d {
    transform-style: preserve-3d;
  }
</style>

@endsection