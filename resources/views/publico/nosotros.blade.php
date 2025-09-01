@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-purple-50 via-blue-50 to-cyan-100 py-12 px-4 lg:px-0 relative overflow-hidden">
    <!-- Elementos decorativos de fondo -->
    <div class="absolute inset-0 opacity-10">
        <div class="absolute top-20 left-10 w-32 h-32 bg-gradient-to-br from-purple-400 to-pink-400 rounded-full blur-xl animate-pulse"></div>
        <div class="absolute top-40 right-20 w-24 h-24 bg-gradient-to-br from-blue-400 to-cyan-400 rounded-full blur-xl animate-pulse delay-1000"></div>
        <div class="absolute bottom-32 left-1/4 w-40 h-40 bg-gradient-to-br from-cyan-400 to-blue-400 rounded-full blur-xl animate-pulse delay-2000"></div>
        <div class="absolute bottom-20 right-1/3 w-28 h-28 bg-gradient-to-br from-pink-400 to-purple-400 rounded-full blur-xl animate-pulse delay-3000"></div>
    </div>

    <div class="max-w-6xl mx-auto relative z-10">
        <!-- Hero Section -->
        <div class="text-center mb-16">
            <div class="inline-block bg-gradient-to-r from-purple-500 to-pink-500 text-white px-8 py-3 rounded-full text-sm font-bold uppercase tracking-wider mb-6 shadow-lg">
                🐾 Conectando Corazones
            </div>
            <h1 class="text-6xl lg:text-7xl font-black bg-gradient-to-r from-purple-600 via-blue-600 to-cyan-600 bg-clip-text text-transparent mb-6 leading-tight">
                Sobre Nosotros
            </h1>
            <p class="text-xl lg:text-2xl text-gray-700 max-w-4xl mx-auto leading-relaxed font-medium">
                Somos una plataforma dedicada a conectar animales en situación de abandono con familias amorosas que desean adoptar. Nuestro objetivo es facilitar el proceso de adopción, apoyar a las fundaciones y promover la tenencia responsable de mascotas.
            </p>
        </div>

        <!-- Cards principales -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-16">
            <!-- Misión -->
            <div class="group relative">
                <div class="absolute inset-0 bg-gradient-to-r from-purple-500 to-pink-500 rounded-3xl blur opacity-75 group-hover:opacity-100 transition-opacity duration-300"></div>
                <div class="relative bg-white rounded-3xl p-8 shadow-xl border border-purple-200 hover:transform hover:scale-105 transition-all duration-300">
                    <div class="flex items-center mb-6">
                        <div class="bg-gradient-to-r from-purple-500 to-pink-500 w-16 h-16 rounded-full flex items-center justify-center mr-4">
                            <span class="text-3xl">🎯</span>
                        </div>
                        <h2 class="text-3xl font-bold bg-gradient-to-r from-purple-600 to-pink-600 bg-clip-text text-transparent">Misión</h2>
                    </div>
                    <p class="text-gray-700 text-lg leading-relaxed">Brindar una segunda oportunidad a animales rescatados, facilitando su adopción y promoviendo el bienestar animal a través de la colaboración con fundaciones y la comunidad.</p>
                </div>
            </div>

            <!-- Visión -->
            <div class="group relative">
                <div class="absolute inset-0 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-3xl blur opacity-75 group-hover:opacity-100 transition-opacity duration-300"></div>
                <div class="relative bg-white rounded-3xl p-8 shadow-xl border border-blue-200 hover:transform hover:scale-105 transition-all duration-300">
                    <div class="flex items-center mb-6">
                        <div class="bg-gradient-to-r from-blue-500 to-cyan-500 w-16 h-16 rounded-full flex items-center justify-center mr-4">
                            <span class="text-3xl">🌟</span>
                        </div>
                        <h2 class="text-3xl font-bold bg-gradient-to-r from-blue-600 to-cyan-600 bg-clip-text text-transparent">Visión</h2>
                    </div>
                    <p class="text-gray-700 text-lg leading-relaxed">Ser la plataforma líder en adopción de animales, inspirando una cultura de respeto, amor y responsabilidad hacia los animales en toda la sociedad.</p>
                </div>
            </div>
        </div>

        <!-- Valores -->
        <div class="bg-white rounded-3xl shadow-2xl p-8 lg:p-12 border-2 border-gradient-to-r from-purple-200 to-cyan-200 mb-12 relative overflow-hidden">
            <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-purple-500 via-pink-500 via-blue-500 to-cyan-500"></div>
            <div class="text-center mb-10">
                <h2 class="text-4xl font-bold bg-gradient-to-r from-purple-600 to-cyan-600 bg-clip-text text-transparent mb-4">Nuestros Valores</h2>
                <p class="text-gray-600 text-lg">Los principios que guían cada una de nuestras acciones</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="bg-gradient-to-br from-purple-50 to-pink-50 rounded-2xl p-6 hover:transform hover:scale-105 transition-all duration-300 border border-purple-100">
                    <div class="text-4xl mb-4">💖</div>
                    <h3 class="font-bold text-purple-800 mb-2">Compasión</h3>
                    <p class="text-gray-700">Empatía por los animales en todas nuestras decisiones</p>
                </div>
                <div class="bg-gradient-to-br from-blue-50 to-cyan-50 rounded-2xl p-6 hover:transform hover:scale-105 transition-all duration-300 border border-blue-100">
                    <div class="text-4xl mb-4">🤝</div>
                    <h3 class="font-bold text-blue-800 mb-2">Transparencia</h3>
                    <p class="text-gray-700">Confianza en cada proceso de adopción</p>
                </div>
                <div class="bg-gradient-to-br from-cyan-50 to-blue-50 rounded-2xl p-6 hover:transform hover:scale-105 transition-all duration-300 border border-cyan-100">
                    <div class="text-4xl mb-4">🌟</div>
                    <h3 class="font-bold text-cyan-800 mb-2">Colaboración</h3>
                    <p class="text-gray-700">Trabajo conjunto con fundaciones y voluntarios</p>
                </div>
                <div class="bg-gradient-to-br from-pink-50 to-purple-50 rounded-2xl p-6 hover:transform hover:scale-105 transition-all duration-300 border border-pink-100">
                    <div class="text-4xl mb-4">📚</div>
                    <h3 class="font-bold text-pink-800 mb-2">Educación</h3>
                    <p class="text-gray-700">Promovemos la tenencia responsable</p>
                </div>
                <div class="bg-gradient-to-br from-green-50 to-blue-50 rounded-2xl p-6 hover:transform hover:scale-105 transition-all duration-300 border border-green-100">
                    <div class="text-4xl mb-4">🚀</div>
                    <h3 class="font-bold text-green-800 mb-2">Innovación</h3>
                    <p class="text-gray-700">Mejora continua en nuestros servicios</p>
                </div>
            </div>
        </div>

        <!-- Nuestro Equipo -->
        <div class="bg-gradient-to-br from-purple-500 to-pink-500 rounded-3xl shadow-2xl p-8 lg:p-12 mb-12 text-white relative overflow-hidden">
            <div class="absolute inset-0 bg-black opacity-10"></div>
            <div class="relative z-10">
                <div class="text-center mb-10">
                    <h2 class="text-4xl font-bold mb-4">Nuestro Equipo</h2>
                    <p class="text-xl opacity-90">Un equipo multidisciplinario comprometido con la causa</p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="bg-white bg-opacity-20 backdrop-blur-sm rounded-2xl p-6 hover:bg-opacity-30 transition-all duration-300">
                        <div class="text-5xl mb-4 text-center">💻</div>
                        <h3 class="font-bold text-xl mb-2 text-center">Desarrolladores</h3>
                        <p class="opacity-90 text-center">Expertos en tecnología web y diseño</p>
                    </div>
                    <div class="bg-white bg-opacity-20 backdrop-blur-sm rounded-2xl p-6 hover:bg-opacity-30 transition-all duration-300">
                        <div class="text-5xl mb-4 text-center">🙋‍♀️</div>
                        <h3 class="font-bold text-xl mb-2 text-center">Voluntarios</h3>
                        <p class="opacity-90 text-center">Colaboradores de fundaciones aliadas</p>
                    </div>
                    <div class="bg-white bg-opacity-20 backdrop-blur-sm rounded-2xl p-6 hover:bg-opacity-30 transition-all duration-300">
                        <div class="text-5xl mb-4 text-center">🐾</div>
                        <h3 class="font-bold text-xl mb-2 text-center">Especialistas</h3>
                        <p class="opacity-90 text-center">Expertos en bienestar animal</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contacto -->
        <div class="bg-gradient-to-br from-blue-500 to-cyan-500 rounded-3xl shadow-2xl p-8 lg:p-12 mb-12 text-white relative overflow-hidden">
            <div class="absolute inset-0 bg-black opacity-10"></div>
            <div class="relative z-10 text-center">
                <div class="text-6xl mb-6">📧</div>
                <h2 class="text-4xl font-bold mb-4">¡Contáctanos!</h2>
                <p class="text-xl mb-8 opacity-90 max-w-2xl mx-auto">
                    ¿Tienes preguntas, sugerencias o deseas colaborar? Estamos aquí para ayudarte
                </p>
                <div class="bg-white bg-opacity-20 backdrop-blur-sm rounded-2xl p-6 max-w-md mx-auto mb-8">
                    <a href="mailto:contacto@adoptaunamigo.com" class="text-2xl font-bold hover:text-cyan-200 transition-colors duration-300">
                        contacto@adoptaunamigo.com
                    </a>
                </div>
            </div>
        </div>

        <!-- Botón de acción -->
        <div class="text-center">
            <a href="{{ route('publico.index') }}" class="group relative inline-block">
                <div class="absolute inset-0 bg-gradient-to-r from-purple-500 to-pink-500 rounded-full blur opacity-75 group-hover:opacity-100 transition-opacity duration-300"></div>
                <div class="relative bg-gradient-to-r from-purple-500 to-pink-500 hover:from-purple-600 hover:to-pink-600 text-white font-bold px-12 py-4 rounded-full text-xl shadow-2xl hover:shadow-3xl transition-all duration-300 transform hover:scale-105 flex items-center space-x-3">
                    <span>🏠</span>
                    <span>Volver al Inicio</span>
                </div>
            </a>
        </div>
    </div>
</div>

<style>
@keyframes float {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-10px); }
}

.animate-float {
    animation: float 3s ease-in-out infinite;
}

.animate-pulse {
    animation: pulse 4s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

.delay-1000 {
    animation-delay: 1s;
}

.delay-2000 {
    animation-delay: 2s;
}

.delay-3000 {
    animation-delay: 3s;
}
</style>
@endsection