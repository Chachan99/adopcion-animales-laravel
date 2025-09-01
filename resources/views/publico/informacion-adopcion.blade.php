@extends('layouts.app')

@section('content')
<!-- Hero Section -->
<section class="bg-gradient-to-r from-cyan-500 to-blue-500 text-white py-20">
    <div class="container mx-auto px-6 text-center">
        <h1 class="text-4xl md:text-5xl font-bold mb-6">Guía para Primerizos en la Adopción</h1>
        <p class="text-xl md:text-2xl mb-8">Todo lo que necesitas saber antes de darle un hogar a una mascota</p>
        <div class="flex justify-center space-x-4">
            <a href="{{ route('publico.animales') }}" class="bg-white text-cyan-600 hover:bg-gray-100 font-bold py-3 px-8 rounded-full transition-colors">
                Ver Animales en Adopción
            </a>
        </div>
    </div>
</section>

<!-- Main Content -->
<section class="py-16 px-6 bg-white">
    <div class="max-w-4xl mx-auto">
        <!-- Introducción -->
        <div class="mb-16 text-center">
            <h2 class="text-3xl font-bold text-gray-900 mb-6">¡Bienvenido al maravilloso mundo de la adopción! 🐾</h2>
            <p class="text-lg text-gray-700 leading-relaxed">Adoptar una mascota es una decisión que cambia vidas, tanto la del animal como la tuya. Antes de dar este importante paso, es fundamental estar bien informado sobre lo que implica ser responsable de un nuevo miembro de la familia.</p>
        </div>

        <!-- Secciones de información -->
        <div class="space-y-16">
            <!-- 1. Compromiso a Largo Plazo -->
            <div class="bg-gray-50 p-8 rounded-xl">
                <div class="flex items-center mb-6">
                    <div class="bg-cyan-100 text-cyan-600 w-12 h-12 rounded-full flex items-center justify-center text-2xl mr-4">
                        ⏳
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900">1. Compromiso a Largo Plazo</h3>
                </div>
                <div class="pl-16">
                    <ul class="space-y-4 text-gray-700">
                        <li class="flex items-start">
                            <span class="text-cyan-500 mr-2">•</span>
                            <span>Las mascotas pueden vivir entre 10-20 años dependiendo de la especie y raza.</span>
                        </li>
                        <li class="flex items-start">
                            <span class="text-cyan-500 mr-2">•</span>
                            <span>Considera cambios futuros en tu vida (mudanzas, viajes, familia) y cómo afectarán a tu mascota.</span>
                        </li>
                        <li class="flex items-start">
                            <span class="text-cyan-500 mr-2">•</span>
                            <span>La adopción es para toda la vida, no es un compromiso temporal.</span>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- 2. Costos Asociados -->
            <div class="bg-gray-50 p-8 rounded-xl">
                <div class="flex items-center mb-6">
                    <div class="bg-cyan-100 text-cyan-600 w-12 h-12 rounded-full flex items-center justify-center text-2xl mr-4">
                        💰
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900">2. Costos Asociados</h3>
                </div>
                <div class="pl-16">
                    <ul class="space-y-4 text-gray-700">
                        <li class="flex items-start">
                            <span class="text-cyan-500 mr-2">•</span>
                            <span><strong>Alimentación:</strong> De calidad adecuada a su edad y tamaño.</span>
                        </li>
                        <li class="flex items-start">
                            <span class="text-cyan-500 mr-2">•</span>
                            <span><strong>Atención veterinaria:</strong> Vacunas, desparasitación, esterilización y emergencias.</span>
                        </li>
                        <li class="flex items-start">
                            <span class="text-cyan-500 mr-2">•</span>
                            <span><strong>Accesorios:</strong> Cama, correa, platos, juguetes, etc.</span>
                        </li>
                        <li class="flex items-start">
                            <span class="text-cyan-500 mr-2">•</span>
                            <span><strong>Guardería o paseadores:</strong> Si trabajas fuera de casa.</span>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- 3. Tiempo y Atención -->
            <div class="bg-gray-50 p-8 rounded-xl">
                <div class="flex items-center mb-6">
                    <div class="bg-cyan-100 text-cyan-600 w-12 h-12 rounded-full flex items-center justify-center text-2xl mr-4">
                        ⏰
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900">3. Tiempo y Atención</h3>
                </div>
                <div class="pl-16">
                    <ul class="space-y-4 text-gray-700">
                        <li class="flex items-start">
                            <span class="text-cyan-500 mr-2">•</span>
                            <span>Las mascotas necesitan tiempo de calidad contigo todos los días.</span>
                        </li>
                        <li class="flex items-start">
                            <span class="text-cyan-500 mr-2">•</span>
                            <span>Paseos diarios (especialmente perros) y tiempo de juego.</span>
                        </li>
                        <li class="flex items-start">
                            <span class="text-cyan-500 mr-2">•</span>
                            <span>Entrenamiento básico y socialización.</span>
                        </li>
                        <li class="flex items-start">
                            <span class="text-cyan-500 mr-2">•</span>
                            <span>Limpieza y mantenimiento de su espacio.</span>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- 4. Espacio y Entorno -->
            <div class="bg-gray-50 p-8 rounded-xl">
                <div class="flex items-center mb-6">
                    <div class="bg-cyan-100 text-cyan-600 w-12 h-12 rounded-full flex items-center justify-center text-2xl mr-4">
                        🏠
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900">4. Espacio y Entorno</h3>
                </div>
                <div class="pl-16">
                    <ul class="space-y-4 text-gray-700">
                        <li class="flex items-start">
                            <span class="text-cyan-500 mr-2">•</span>
                            <span>Asegúrate de que tu vivienda sea apta para mascotas (tamaño, espacio exterior, normas del lugar).</span>
                        </li>
                        <li class="flex items-start">
                            <span class="text-cyan-500 mr-2">•</span>
                            <span>Considera si tienes espacio suficiente para el tamaño y nivel de energía del animal.</span>
                        </li>
                        <li class="flex items-start">
                            <span class="text-cyan-500 mr-2">•</span>
                            <span>Verifica que no haya riesgos en el hogar (productos tóxicos, cables sueltos, etc.).</span>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- 5. Elección del Animal Adecuado -->
            <div class="bg-gray-50 p-8 rounded-xl">
                <div class="flex items-center mb-6">
                    <div class="bg-cyan-100 text-cyan-600 w-12 h-12 rounded-full flex items-center justify-center text-2xl mr-4">
                        🐶🐱
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900">5. Elección del Animal Adecuado</h3>
                </div>
                <div class="pl-16">
                    <ul class="space-y-4 text-gray-700">
                        <li class="flex items-start">
                            <span class="text-cyan-500 mr-2">•</span>
                            <span>Elige un animal que se adapte a tu estilo de vida, no solo por su apariencia.</span>
                        </li>
                        <li class="flex items-start">
                            <span class="text-cyan-500 mr-2">•</span>
                            <span>Considera la edad: los cachorros requieren más tiempo y paciencia.</span>
                        </li>
                        <li class="flex items-start">
                            <span class="text-cyan-500 mr-2">•</span>
                            <span>Investiga sobre las características de la raza o mezcla.</span>
                        </li>
                        <li class="flex items-start">
                            <span class="text-cyan-500 mr-2">•</span>
                            <span>Si tienes niños u otras mascotas, asegúrate de que sean compatibles.</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- CTA Section -->
        <div class="mt-16 text-center bg-gradient-to-r from-cyan-50 to-blue-50 p-10 rounded-2xl">
            <h3 class="text-2xl font-bold text-gray-900 mb-6">¿Listo para dar el siguiente paso?</h3>
            <p class="text-lg text-gray-700 mb-8">Si después de leer esta guía sientes que estás preparado, ¡te invitamos a conocer a nuestros amigos que buscan hogar!</p>
            <a href="{{ route('publico.animales') }}" class="inline-block bg-cyan-600 hover:bg-cyan-700 text-white font-bold py-3 px-8 rounded-full transition-colors">
                Ver Animales en Adopción
            </a>
        </div>
    </div>
</section>

@endsection
