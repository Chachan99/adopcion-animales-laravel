@extends('layouts.app')

@section('content')
<!-- Hero Section -->
<section class="bg-gradient-to-r from-blue-900 via-blue-800 to-cyan-600 text-white py-16">
    <div class="max-w-7xl mx-auto px-6 text-center">
        <h1 class="text-4xl md:text-5xl font-bold mb-4">Infórmate Antes de Adoptar</h1>
        <p class="text-xl text-blue-100 max-w-3xl mx-auto">Todo lo que necesitas saber para darle una vida feliz a tu nueva mascota.</p>
    </div>
</section>

<!-- Main Content -->
<section class="py-16 px-6">
    <div class="max-w-4xl mx-auto">
        <!-- Introducción -->
        <div class="bg-white rounded-xl shadow-lg p-8 mb-12">
            <p class="text-lg text-gray-700 mb-6">Adoptar una mascota es una decisión importante que cambia vidas. Antes de dar este paso, es fundamental estar bien informado sobre las responsabilidades y compromisos que implica.</p>
            
            <div class="bg-amber-50 border-l-4 border-amber-400 p-4 mb-8">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-amber-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-amber-700">
                            <strong>Recuerda:</strong> Una mascota no es un regalo ni un capricho, es un ser vivo que dependerá completamente de ti durante toda su vida.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sección 1: Consideraciones antes de adoptar -->
        <div class="bg-white rounded-xl shadow-lg p-8 mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-6 flex items-center">
                <span class="bg-blue-100 text-blue-600 w-10 h-10 rounded-full flex items-center justify-center mr-4">1</span>
                Consideraciones antes de adoptar
            </h2>
            
            <div class="space-y-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <div class="flex items-center justify-center h-10 w-10 rounded-full bg-green-100 text-green-600">
                            <i class="fas fa-home"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-medium text-gray-900">Espacio adecuado</h3>
                        <p class="mt-1 text-gray-600">Asegúrate de tener el espacio suficiente para que la mascota se desarrolle cómodamente. Algunas razas necesitan más espacio que otras.</p>
                    </div>
                </div>
                
                <div class="flex">
                    <div class="flex-shrink-0">
                        <div class="flex items-center justify-center h-10 w-10 rounded-full bg-green-100 text-green-600">
                            <i class="fas fa-clock"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-medium text-gray-900">Tiempo y dedicación</h3>
                        <p class="mt-1 text-gray-600">Las mascotas necesitan atención diaria: paseos, juegos, limpieza y compañía. Asegúrate de tener tiempo para dedicarle.</p>
                    </div>
                </div>
                
                <div class="flex">
                    <div class="flex-shrink-0">
                        <div class="flex items-center justify-center h-10 w-10 rounded-full bg-green-100 text-green-600">
                            <i class="fas fa-piggy-bank"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-medium text-gray-900">Recursos económicos</h3>
                        <p class="mt-1 text-gray-600">Considera los gastos de alimentación, veterinario, accesorios, vacunas, esterilización y posibles emergencias médicas.</p>
                    </div>
                </div>
                
                <div class="flex">
                    <div class="flex-shrink-0">
                        <div class="flex items-center justify-center h-10 w-10 rounded-full bg-green-100 text-green-600">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-medium text-gray-900">Estilo de vida familiar</h3>
                        <p class="mt-1 text-gray-600">Toma en cuenta las alergias, la presencia de niños pequeños u otras mascotas, y asegúrate de que todos en casa estén de acuerdo con la adopción.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sección 2: Compromisos de la adopción -->
        <div class="bg-white rounded-xl shadow-lg p-8 mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-6 flex items-center">
                <span class="bg-blue-100 text-blue-600 w-10 h-10 rounded-full flex items-center justify-center mr-4">2</span>
                Compromisos de la adopción
            </h2>
            
            <div class="space-y-6">
                <div class="relative pl-8">
                    <div class="absolute left-0 top-1 w-6 h-6 bg-blue-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-heart text-blue-600 text-xs"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900">Cuidado de por vida</h3>
                    <p class="mt-1 text-gray-600">Una mascota puede vivir entre 10 a 20 años dependiendo de la especie y raza. Debes estar preparado para asumir esta responsabilidad a largo plazo.</p>
                </div>
                
                <div class="relative pl-8">
                    <div class="absolute left-0 top-1 w-6 h-6 bg-blue-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-heart text-blue-600 text-xs"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900">Salud y bienestar</h3>
                    <p class="mt-1 text-gray-600">Comprométete a proporcionar atención veterinaria regular, vacunas, desparasitación y esterilización.</p>
                </div>
                
                <div class="relative pl-8">
                    <div class="absolute left-0 top-1 w-6 h-6 bg-blue-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-heart text-blue-600 text-xs"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900">Educación y entrenamiento</h3>
                    <p class="mt-1 text-gray-600">Dedica tiempo a educar a tu mascota para una convivencia armoniosa. El entrenamiento básico es esencial para su seguridad y bienestar.</p>
                </div>
                
                <div class="relative pl-8">
                    <div class="absolute left-0 top-1 w-6 h-6 bg-blue-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-heart text-blue-600 text-xs"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900">Identificación y seguridad</h3>
                    <p class="mt-1 text-gray-600">Proporciona identificación con microchip y asegúrate de que tu mascota esté siempre en un entorno seguro.</p>
                </div>
            </div>
        </div>

        <!-- Sección 3: Preguntas para reflexionar -->
        <div class="bg-white rounded-xl shadow-lg p-8 mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-6 flex items-center">
                <span class="bg-blue-100 text-blue-600 w-10 h-10 rounded-full flex items-center justify-center mr-4">3</span>
                Preguntas para reflexionar
            </h2>
            
            <div class="space-y-6">
                <div class="bg-blue-50 p-6 rounded-lg">
                    <h3 class="text-lg font-medium text-gray-900 mb-3">¿Estás listo para los cambios en tu estilo de vida?</h3>
                    <p class="text-gray-600">Las mascotas requieren tiempo y pueden limitar tu capacidad para viajar o salir espontáneamente. ¿Tienes planes de quién cuidará de tu mascota cuando no puedas hacerlo?</p>
                </div>
                
                <div class="bg-blue-50 p-6 rounded-lg">
                    <h3 class="text-lg font-medium text-gray-900 mb-3">¿Conoces las necesidades específicas de la mascota que deseas adoptar?</h3>
                    <p class="text-gray-600">Cada raza y especie tiene características y necesidades diferentes. Investiga bien antes de decidirte por un animal en particular.</p>
                </div>
                
                <div class="bg-blue-50 p-6 rounded-lg">
                    <h3 class="text-lg font-medium text-gray-900 mb-3">¿Qué harás si tu mascota desarrolla problemas de comportamiento o de salud?</h3>
                    <p class="text-gray-600">Los problemas pueden surgir en cualquier momento. Asegúrate de estar preparado para afrontarlos con paciencia y responsabilidad.</p>
                </div>
                
                <div class="bg-blue-50 p-6 rounded-lg">
                    <h3 class="text-lg font-medium text-gray-900 mb-3">¿Estás adoptando por las razones correctas?</h3>
                    <p class="text-gray-600">Una mascota no es un juguete ni un regalo. Asegúrate de que tu motivación principal sea brindar un hogar amoroso y responsable.</p>
                </div>
            </div>
        </div>

        <!-- CTA Section -->
        <div class="text-center bg-gradient-to-r from-cyan-600 to-blue-600 text-white rounded-2xl p-10 shadow-xl">
            <h2 class="text-3xl font-bold mb-4">¿Listo para adoptar?</h2>
            <p class="text-xl mb-8 max-w-2xl mx-auto">Si has considerado todos estos aspectos y estás listo para el compromiso, estás en el camino correcto para darle un hogar a una mascota necesitada.</p>
            <a href="{{ route('publico.buscar') }}" class="inline-block bg-white text-cyan-700 hover:bg-gray-100 font-bold py-3 px-8 rounded-full text-lg transition-colors">
                Ver mascotas en adopción
            </a>
            <p class="mt-6 text-sm text-cyan-100">O si aún tienes dudas, <a href="{{ route('publico.contacto') }}" class="underline hover:text-white">contáctanos</a> para más información.</p>
        </div>
    </div>
</section>

<!-- Resources Section -->
<section class="bg-gray-50 py-16 px-6">
    <div class="max-w-4xl mx-auto">
        <h2 class="text-3xl font-bold text-center text-gray-900 mb-12">Recursos Adicionales</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-white p-6 rounded-xl shadow-md hover:shadow-lg transition-shadow">
                <div class="text-cyan-600 text-4xl mb-4">
                    <i class="fas fa-book"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Guías de Cuidado</h3>
                <p class="text-gray-600 mb-4">Aprende sobre alimentación, entrenamiento y cuidados básicos para diferentes tipos de mascotas.</p>
                <a href="#" class="text-cyan-600 font-medium hover:underline">Ver guías →</a>
            </div>
            
            <div class="bg-white p-6 rounded-xl shadow-md hover:shadow-lg transition-shadow">
                <div class="text-cyan-600 text-4xl mb-4">
                    <i class="fas fa-video"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Videos Educativos</h3>
                <p class="text-gray-600 mb-4">Mira tutoriales sobre entrenamiento, socialización y resolución de problemas de comportamiento.</p>
                <a href="#" class="text-cyan-600 font-medium hover:underline">Ver videos →</a>
            </div>
            
            <div class="bg-white p-6 rounded-xl shadow-md hover:shadow-lg transition-shadow">
                <div class="text-cyan-600 text-4xl mb-4">
                    <i class="fas fa-comments"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Foro de la Comunidad</h3>
                <p class="text-gray-600 mb-4">Conecta con otros dueños de mascotas, comparte experiencias y haz preguntas a expertos.</p>
                <a href="#" class="text-cyan-600 font-medium hover:underline">Unirse al foro →</a>
            </div>
        </div>
    </div>
</section>

<!-- Final CTA -->
<section class="bg-blue-900 text-white py-16">
    <div class="max-w-4xl mx-auto text-center px-6">
        <h2 class="text-3xl font-bold mb-6">¿Aún no estás listo para adoptar?</h2>
        <p class="text-xl mb-8 max-w-2xl mx-auto">Hay muchas otras formas de ayudar a los animales necesitados sin adoptar.</p>
        <div class="flex flex-col sm:flex-row justify-center gap-4">
            <a href="{{ route('publico.donaciones') }}" class="bg-cyan-400 hover:bg-cyan-300 text-blue-900 font-bold py-3 px-6 rounded-lg transition-colors">
                Hacer una donación
            </a>
            <a href="{{ route('publico.voluntariado') }}" class="bg-transparent hover:bg-blue-800 border-2 border-white text-white font-bold py-3 px-6 rounded-lg transition-colors">
                Ser voluntario
            </a>
        </div>
    </div>
</section>
@endsection
