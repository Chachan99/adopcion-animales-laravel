<nav class="bg-gradient-to-r from-blue-900 via-blue-800 to-cyan-500 shadow-xl sticky top-0 z-50" x-data="{ mobileMenuOpen: false }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-20 items-center">
           
            <!-- Logo e Identidad -->
            <div class="flex items-center space-x-3">
                <a href="{{ url('/') }}" class="flex items-center space-x-2">
                    <!-- Ícono de huella -->
                    <svg class="w-8 h-8 text-cyan-300" fill="currentColor" viewBox="0 0 24 24">
                        <circle cx="6" cy="10" r="2"/>
                        <circle cx="18" cy="10" r="2"/>
                        <circle cx="12" cy="4" r="2.5"/>
                        <ellipse cx="12" cy="17" rx="5" ry="7"/>
                    </svg>
                    <span class="text-2xl font-extrabold text-white tracking-wide drop-shadow-lg" style="font-family: 'Figtree', sans-serif;">
                        Adopta un Amigo
                    </span>
                </a>
            </div>

            <!-- Enlaces de navegación (desktop) - Mejorado con efecto hover -->
            <div class="hidden sm:flex space-x-1">
                @php 
                    $isAdmin = Auth::check() && Auth::user()?->rol === 'admin'; 
                    $isFundacion = Auth::check() && Auth::user()?->rol === 'fundacion'; 
                @endphp
                
                @if(!$isAdmin && !$isFundacion)
                <a href="{{ route('publico.index') }}" class="relative px-4 py-2 rounded-lg transition-all duration-300 flex items-center {{ request()->routeIs('publico.index') ? 'text-white font-semibold bg-blue-700 bg-opacity-30' : 'text-white hover:text-cyan-200 font-medium hover:bg-blue-700 hover:bg-opacity-20' }}">
                    <i class="fas fa-home mr-2"></i> Inicio
                    @if(request()->routeIs('publico.index'))
                    <span class="absolute bottom-0 left-1/2 transform -translate-x-1/2 w-3/4 h-0.5 bg-cyan-400 rounded-full"></span>
                    @endif
                </a>
                <a href="{{ route('publico.buscar') }}" class="relative px-4 py-2 rounded-lg transition-all duration-300 flex items-center {{ request()->routeIs('publico.buscar') ? 'text-white font-semibold bg-blue-700 bg-opacity-30' : 'text-white hover:text-cyan-200 font-medium hover:bg-blue-700 hover:bg-opacity-20' }}">
                    <i class="fas fa-heart mr-2"></i> ¡Adopta!
                    @if(request()->routeIs('publico.buscar'))
                    <span class="absolute bottom-0 left-1/2 transform -translate-x-1/2 w-3/4 h-0.5 bg-cyan-400 rounded-full"></span>
                    @endif
                </a>
                <a href="{{ route('publico.nosotros') }}" class="relative px-4 py-2 rounded-lg transition-all duration-300 flex items-center {{ request()->routeIs('publico.nosotros') ? 'text-white font-semibold bg-blue-700 bg-opacity-30' : 'text-white hover:text-cyan-200 font-medium hover:bg-blue-700 hover:bg-opacity-20' }}">
                    <i class="fas fa-users mr-2"></i> Nosotros
                    @if(request()->routeIs('publico.nosotros'))
                    <span class="absolute bottom-0 left-1/2 transform -translate-x-1/2 w-3/4 h-0.5 bg-cyan-400 rounded-full"></span>
                    @endif
                </a>
                <a href="{{ route('noticias.index') }}" class="relative px-4 py-2 rounded-lg transition-all duration-300 flex items-center {{ request()->routeIs('noticias.*') ? 'text-white font-semibold bg-blue-700 bg-opacity-30' : 'text-white hover:text-cyan-200 font-medium hover:bg-blue-700 hover:bg-opacity-20' }}">
                    <i class="fas fa-newspaper mr-2"></i> Noticias
                    @if(request()->routeIs('noticias.*'))
                    <span class="absolute bottom-0 left-1/2 transform -translate-x-1/2 w-3/4 h-0.5 bg-cyan-400 rounded-full"></span>
                    @endif
                </a>
                <a href="{{ route('publico.donaciones') }}" class="relative px-4 py-2 rounded-lg transition-all duration-300 flex items-center {{ request()->routeIs('publico.donaciones') ? 'text-white font-semibold bg-blue-700 bg-opacity-30' : 'text-white hover:text-cyan-200 font-medium hover:bg-blue-700 hover:bg-opacity-20' }}">
                    <i class="fas fa-hand-holding-heart mr-2"></i> Donaciones
                    @if(request()->routeIs('publico.donaciones'))
                    <span class="absolute bottom-0 left-1/2 transform -translate-x-1/2 w-3/4 h-0.5 bg-cyan-400 rounded-full"></span>
                    @endif
                </a>
                <a href="{{ route('animales-perdidos.index') }}" class="relative px-4 py-2 rounded-lg transition-all duration-300 flex items-center {{ request()->routeIs('animales-perdidos.*') ? 'text-white font-semibold bg-blue-700 bg-opacity-30' : 'text-white hover:text-cyan-200 font-medium hover:bg-blue-700 hover:bg-opacity-20' }}">
                    <i class="fas fa-search-location mr-2"></i> Perdidos
                    @if(request()->routeIs('animales-perdidos.*'))
                    <span class="absolute bottom-0 left-1/2 transform -translate-x-1/2 w-3/4 h-0.5 bg-cyan-400 rounded-full"></span>
                    @endif
                </a>
                @endif
                
                @auth
                    @if(Auth::user()->rol === 'fundacion')
                        <a href="{{ route('fundacion.animales') }}" class="relative px-4 py-2 rounded-lg transition-all duration-300 flex items-center {{ request()->routeIs('fundacion.animales') ? 'text-white font-semibold bg-blue-700 bg-opacity-30' : 'text-white hover:text-cyan-200 font-medium hover:bg-blue-700 hover:bg-opacity-20' }}">
                            <i class="fas fa-paw mr-2"></i> Mis Animales
                            @if(request()->routeIs('fundacion.animales'))
                            <span class="absolute bottom-0 left-1/2 transform -translate-x-1/2 w-3/4 h-0.5 bg-cyan-400 rounded-full"></span>
                            @endif
                        </a>
                        <a href="{{ route('fundacion.solicitudes') }}" class="relative px-4 py-2 rounded-lg transition-all duration-300 flex items-center {{ request()->routeIs('fundacion.solicitudes') ? 'text-white font-semibold bg-blue-700 bg-opacity-30' : 'text-white hover:text-cyan-200 font-medium hover:bg-blue-700 hover:bg-opacity-20' }}">
                            <i class="fas fa-file-signature mr-2"></i> Solicitudes
                            @if(request()->routeIs('fundacion.solicitudes'))
                            <span class="absolute bottom-0 left-1/2 transform -translate-x-1/2 w-3/4 h-0.5 bg-cyan-400 rounded-full"></span>
                            @endif
                        </a>
                        <a href="{{ route('fundacion.noticias.index') }}" class="relative px-4 py-2 rounded-lg transition-all duration-300 flex items-center {{ request()->routeIs('fundacion.noticias.*') ? 'text-white font-semibold bg-blue-700 bg-opacity-30' : 'text-white hover:text-cyan-200 font-medium hover:bg-blue-700 hover:bg-opacity-20' }}">
                            <i class="fas fa-newspaper mr-2"></i> Noticias
                            @if(request()->routeIs('fundacion.noticias.*'))
                            <span class="absolute bottom-0 left-1/2 transform -translate-x-1/2 w-3/4 h-0.5 bg-cyan-400 rounded-full"></span>
                            @endif
                        </a>
                    @elseif(Auth::user()->rol === 'admin')
                        <a href="{{ route('admin.dashboard') }}" class="relative px-4 py-2 rounded-lg transition-all duration-300 flex items-center {{ request()->routeIs('admin.dashboard') ? 'text-white font-semibold bg-blue-700 bg-opacity-30' : 'text-white hover:text-cyan-200 font-medium hover:bg-blue-700 hover:bg-opacity-20' }}">
                            <i class="fas fa-tachometer-alt mr-2"></i> Panel
                            @if(request()->routeIs('admin.dashboard'))
                            <span class="absolute bottom-0 left-1/2 transform -translate-x-1/2 w-3/4 h-0.5 bg-cyan-400 rounded-full"></span>
                            @endif
                        </a>
                        <a href="{{ route('admin.usuarios') }}" class="relative px-4 py-2 rounded-lg transition-all duration-300 flex items-center {{ request()->routeIs('admin.usuarios') ? 'text-white font-semibold bg-blue-700 bg-opacity-30' : 'text-white hover:text-cyan-200 font-medium hover:bg-blue-700 hover:bg-opacity-20' }}">
                            <i class="fas fa-user-cog mr-2"></i> Usuarios
                            @if(request()->routeIs('admin.usuarios'))
                            <span class="absolute bottom-0 left-1/2 transform -translate-x-1/2 w-3/4 h-0.5 bg-cyan-400 rounded-full"></span>
                            @endif
                        </a>
                        <a href="{{ route('admin.solicitudes') }}" class="relative px-4 py-2 rounded-lg transition-all duration-300 flex items-center {{ request()->routeIs('admin.solicitudes') ? 'text-white font-semibold bg-blue-700 bg-opacity-30' : 'text-white hover:text-cyan-200 font-medium hover:bg-blue-700 hover:bg-opacity-20' }}">
                            <i class="fas fa-clipboard-list mr-2"></i> Solicitudes
                            @if(request()->routeIs('admin.solicitudes'))
                            <span class="absolute bottom-0 left-1/2 transform -translate-x-1/2 w-3/4 h-0.5 bg-cyan-400 rounded-full"></span>
                            @endif
                        </a>
                        <a href="{{ route('admin.mascotas-perdidas') }}" class="relative px-4 py-2 rounded-lg transition-all duration-300 flex items-center {{ request()->routeIs('admin.mascotas-perdidas*') ? 'text-white font-semibold bg-blue-700 bg-opacity-30' : 'text-white hover:text-cyan-200 font-medium hover:bg-blue-700 hover:bg-opacity-20' }}">
                            <i class="fas fa-search-location mr-2"></i> Mascotas Perdidas
                            @if(request()->routeIs('admin.mascotas-perdidas*'))
                            <span class="absolute bottom-0 left-1/2 transform -translate-x-1/2 w-3/4 h-0.5 bg-cyan-400 rounded-full"></span>
                            @endif
                        </a>
                    @endif
                @endauth
            </div>

            <!-- Botones de autenticación (desktop) - Mejorados con animaciones -->
            <div class="hidden sm:flex items-center space-x-4">
                @guest
                    <a href="{{ route('login') }}" class="relative group">
                        <span class="bg-cyan-400 hover:bg-cyan-300 text-blue-900 font-bold px-6 py-2.5 rounded-full shadow-lg transition-all duration-300 group-hover:shadow-xl group-hover:-translate-y-0.5 flex items-center">
                            <i class="fas fa-sign-in-alt mr-2"></i> Iniciar Sesión
                        </span>
                    </a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="relative group">
                            <span class="bg-white hover:bg-cyan-100 text-blue-900 font-bold px-6 py-2.5 rounded-full shadow-lg transition-all duration-300 group-hover:shadow-xl group-hover:-translate-y-0.5 flex items-center border-2 border-cyan-400">
                                <i class="fas fa-user-plus mr-2"></i> Registrarse
                            </span>
                        </a>
                    @endif
                @else
                    <!-- Menú desplegable de usuario mejorado -->
                    @auth
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open" type="button" class="flex items-center space-x-2 focus:outline-none group">
                                <div class="relative">
                                    <div class="w-10 h-10 rounded-full bg-cyan-100 flex items-center justify-center text-blue-900 font-bold border-2 border-cyan-300 group-hover:border-white transition-colors duration-300">
                                        {{ strtoupper(substr(Auth::user()->nombre ?? Auth::user()->name, 0, 1)) }}
                                    </div>
                                    <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-green-400 rounded-full border-2 border-white"></div>
                                </div>
                                <span class="text-white font-medium group-hover:text-cyan-200 transition-colors duration-300">
                                    {{ Auth::user()->nombre ?? Auth::user()->name }}
                                </span>
                                <svg class="h-4 w-4 text-white transform transition-transform duration-300" :class="{ 'rotate-180': open }" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            
                            <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-200" 
                                 x-transition:enter-start="opacity-0 translate-y-1" 
                                 x-transition:enter-end="opacity-100 translate-y-0" 
                                 x-transition:leave="transition ease-in duration-150" 
                                 x-transition:leave-start="opacity-100 translate-y-0" 
                                 x-transition:leave-end="opacity-0 translate-y-1"
                                 class="origin-top-right absolute right-0 mt-3 w-56 rounded-lg shadow-xl bg-white ring-1 ring-black ring-opacity-5 z-50 overflow-hidden" 
                                 x-cloak>
                                <div class="py-1">
                                    @if(Auth::user()->rol === 'fundacion')
                                        <a href="{{ route('fundacion.perfil') }}" class="flex items-center px-4 py-3 text-blue-900 hover:bg-cyan-50 transition-colors duration-200">
                                            <i class="fas fa-user-circle mr-3 text-cyan-500"></i> Mi Perfil
                                        </a>
                                        <a href="{{ route('fundacion.perfil.editar') }}" class="flex items-center px-4 py-3 text-blue-900 hover:bg-cyan-50 transition-colors duration-200">
                                            <i class="fas fa-cog mr-3 text-cyan-500"></i> Editar Perfil
                                        </a>
                                    @else
                                        <a href="{{ route('profile.edit-publico') }}" class="flex items-center px-4 py-3 text-blue-900 hover:bg-cyan-50 transition-colors duration-200">
                                            <i class="fas fa-user-edit mr-3 text-cyan-500"></i> Editar Perfil
                                        </a>
                                    @endif
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="w-full text-left flex items-center px-4 py-3 text-blue-900 hover:bg-cyan-50 transition-colors duration-200">
                                            <i class="fas fa-sign-out-alt mr-3 text-cyan-500"></i> Cerrar Sesión
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endauth
                @endguest
            </div>

            <!-- Menú hamburguesa para móvil - Mejorado -->
            <div class="sm:hidden flex items-center">
                <button @click="mobileMenuOpen = !mobileMenuOpen" class="text-cyan-200 hover:text-white focus:outline-none transition-colors duration-300 p-2">
                    <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-show="!mobileMenuOpen">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-show="mobileMenuOpen">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Menú móvil - Mejorado con animación -->
        <div x-show="mobileMenuOpen" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 -translate-y-5" 
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-5"
             @click.away="mobileMenuOpen = false"
             class="sm:hidden absolute top-20 left-0 right-0 bg-gradient-to-b from-blue-900 to-blue-800 shadow-xl z-40 overflow-hidden max-h-screen overflow-y-auto"
             style="display: none;">
            <div class="px-2 pt-2 pb-4 space-y-1 sm:px-3">
                @php
                    $isAdmin = Auth::check() && Auth::user()?->rol === 'admin';
                    $isFundacion = Auth::check() && Auth::user()?->rol === 'fundacion';
                @endphp
                
                @if(!$isAdmin && !$isFundacion)
                <a href="{{ route('publico.index') }}" class="block px-4 py-3 text-white {{ request()->routeIs('publico.index') ? 'bg-blue-700' : 'hover:bg-blue-700' }} rounded-lg transition-colors duration-300 flex items-center">
                    <i class="fas fa-home mr-3"></i> Inicio
                </a>
                <a href="{{ route('publico.buscar') }}" class="block px-4 py-3 text-white {{ request()->routeIs('publico.buscar') ? 'bg-blue-700' : 'hover:bg-blue-700' }} rounded-lg transition-colors duration-300 flex items-center">
                    <i class="fas fa-heart mr-3"></i> ¡Adopta!
                </a>
                <a href="{{ route('noticias.index') }}" class="block px-4 py-3 text-white {{ request()->routeIs('noticias.*') ? 'bg-blue-700' : 'hover:bg-blue-700' }} rounded-lg transition-colors duration-300 flex items-center">
                    <i class="fas fa-newspaper mr-3"></i> Noticias
                </a>
                <a href="{{ route('publico.nosotros') }}" class="block px-4 py-3 text-white {{ request()->routeIs('publico.nosotros') ? 'bg-blue-700' : 'hover:bg-blue-700' }} rounded-lg transition-colors duration-300 flex items-center">
                    <i class="fas fa-users mr-3"></i> Nosotros
                </a>
                <a href="/panel-fundacion/publico" class="block px-4 py-3 text-white {{ request()->is('panel-fundacion/publico') ? 'bg-blue-700' : 'hover:bg-blue-700' }} rounded-lg transition-colors duration-300 flex items-center">
                    <i class="fas fa-hands-helping mr-3"></i> Fundación
                </a>
                <a href="{{ route('publico.donaciones') }}" class="block px-4 py-3 text-white {{ request()->routeIs('publico.donaciones') ? 'bg-blue-700' : 'hover:bg-blue-700' }} rounded-lg transition-colors duration-300 flex items-center">
                    <i class="fas fa-hand-holding-heart mr-3"></i> Donaciones
                </a>
                <a href="{{ route('animales-perdidos.index') }}" class="block px-4 py-3 text-white {{ request()->routeIs('animales-perdidos.*') ? 'bg-blue-700' : 'hover:bg-blue-700' }} rounded-lg transition-colors duration-300 flex items-center">
                    <i class="fas fa-search-location mr-3"></i> Mascotas Perdidas
                </a>
                @endif
                
                @auth
                    @if(Auth::user()->rol === 'fundacion')
                        <a href="{{ route('fundacion.animales') }}" class="block px-4 py-3 text-white {{ request()->routeIs('fundacion.animales') ? 'bg-blue-700' : 'hover:bg-blue-700' }} rounded-lg transition-colors duration-300 flex items-center">
                            <i class="fas fa-paw mr-3"></i> Mis Animales
                        </a>
                        <a href="{{ route('fundacion.solicitudes') }}" class="block px-4 py-3 text-white {{ request()->routeIs('fundacion.solicitudes') ? 'bg-blue-700' : 'hover:bg-blue-700' }} rounded-lg transition-colors duration-300 flex items-center">
                            <i class="fas fa-file-signature mr-3"></i> Solicitudes
                        </a>
                        <a href="{{ route('fundacion.noticias.index') }}" class="block px-4 py-3 text-white {{ request()->routeIs('fundacion.noticias.*') ? 'bg-blue-700' : 'hover:bg-blue-700' }} rounded-lg transition-colors duration-300 flex items-center">
                            <i class="fas fa-newspaper mr-3"></i> Noticias
                        </a>
                    @elseif(Auth::user()->rol === 'admin')
                        <a href="{{ route('admin.dashboard') }}" class="block px-4 py-3 text-white {{ request()->routeIs('admin.dashboard') ? 'bg-blue-700' : 'hover:bg-blue-700' }} rounded-lg transition-colors duration-300 flex items-center">
                            <i class="fas fa-tachometer-alt mr-3"></i> Panel
                        </a>
                        <a href="{{ route('admin.usuarios') }}" class="block px-4 py-3 text-white {{ request()->routeIs('admin.usuarios') ? 'bg-blue-700' : 'hover:bg-blue-700' }} rounded-lg transition-colors duration-300 flex items-center">
                            <i class="fas fa-user-cog mr-3"></i> Usuarios
                        </a>
                        <a href="{{ route('admin.solicitudes') }}" class="block px-4 py-3 text-white {{ request()->routeIs('admin.solicitudes') ? 'bg-blue-700' : 'hover:bg-blue-700' }} rounded-lg transition-colors duration-300 flex items-center">
                            <i class="fas fa-clipboard-list mr-3"></i> Solicitudes
                        </a>
                        <a href="{{ route('admin.mascotas-perdidas') }}" class="block px-4 py-3 text-white {{ request()->routeIs('admin.mascotas-perdidas*') ? 'bg-blue-700' : 'hover:bg-blue-700' }} rounded-lg transition-colors duration-300 flex items-center">
                            <i class="fas fa-search-location mr-3"></i> Mascotas Perdidas
                        </a>
                    @endif
                @endauth
                
                <div class="border-t border-cyan-500 mt-2 pt-2">
                    @guest
                        <a href="{{ route('login') }}" class="block w-full text-center bg-cyan-400 hover:bg-cyan-300 text-blue-900 font-bold px-5 py-3 rounded-full shadow transition duration-300 mb-2">
                            <i class="fas fa-sign-in-alt mr-2"></i> Iniciar Sesión
                        </a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="block w-full text-center bg-white hover:bg-cyan-100 text-blue-900 font-bold px-5 py-3 rounded-full shadow transition duration-300 border-2 border-cyan-400">
                                <i class="fas fa-user-plus mr-2"></i> Registrarse
                            </a>
                        @endif
                    @else
                        <div class="flex items-center justify-between px-4 py-3 bg-blue-800 rounded-lg">
                            <div class="flex items-center space-x-3">
                                <div class="w-9 h-9 rounded-full bg-cyan-100 flex items-center justify-center text-blue-900 font-bold border-2 border-cyan-300">
                                    {{ strtoupper(substr(Auth::user()->nombre ?? Auth::user()->name, 0, 1)) }}
                                </div>
                                <span class="text-white font-medium">{{ Auth::user()->nombre ?? Auth::user()->name }}</span>
                            </div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="text-cyan-300 hover:text-white transition-colors duration-300">
                                    <i class="fas fa-sign-out-alt text-lg"></i>
                                </button>
                            </form>
                        </div>
                    @endguest
                </div>
            </div>
        </div>
    </div>
</nav>


<script>
    // Cerrar menú móvil al cambiar el tamaño de la pantalla
    window.addEventListener('resize', () => {
        if (window.innerWidth >= 640) {
            // Usar Alpine.js store o evento personalizado para cerrar el menú
            const navbar = document.querySelector('[x-data]').__x.$data;
            if (navbar && navbar.mobileMenuOpen) {
                navbar.mobileMenuOpen = false;
            }
        }
    });
</script>

<style>
    /* Animación para el navbar */
    nav {
        transition: all 0.3s ease;
    }
    
    /* Mejorar la legibilidad del menú desplegable */
    [x-cloak] { display: none !important; }
    
    /* Efecto de onda para los botones */
    .wave-effect {
        position: relative;
        overflow: hidden;
    }
    
    .wave-effect:after {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 5px;
        height: 5px;
        background: rgba(255, 255, 255, 0.5);
        opacity: 0;
        border-radius: 100%;
        transform: scale(1, 1) translate(-50%);
        transform-origin: 50% 50%;
    }
    
    .wave-effect:focus:not(:active)::after {
        animation: wave 0.6s ease-out;
    }
    
    @keyframes wave {
        0% {
            transform: scale(0, 0);
            opacity: 0.5;
        }
        100% {
            transform: scale(20, 20);
            opacity: 0;
        }
    }
</style>