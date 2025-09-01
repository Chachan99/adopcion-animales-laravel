@extends('layouts.app')

@section('content')
<!-- Hero Section -->
<section class="bg-gradient-to-r from-blue-900 via-blue-800 to-cyan-600 text-white py-16">
    <div class="max-w-7xl mx-auto px-6 text-center">
        <h1 class="text-4xl md:text-5xl font-bold mb-4">Apoya Nuestra Misión</h1>
        <p class="text-xl text-blue-100 max-w-3xl mx-auto">Tu donación ayuda a salvar vidas y darles una segunda oportunidad a animales necesitados.</p>
    </div>
</section>

<!-- Donation Info Section Mejorada con Emojis -->
<section class="py-20 px-6 bg-gradient-to-b from-gray-50 via-white to-gray-50">
    <div class="max-w-6xl mx-auto">
        <div class="bg-white/80 backdrop-blur-lg rounded-2xl shadow-xl p-10 md:p-14 border border-gray-200">
            <h2 class="text-4xl font-extrabold text-gray-900 mb-8 text-center">
                ¿En qué se utilizan <span class="text-cyan-600">las donaciones?</span>
            </h2>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto text-center mb-12">
                Cada aporte que recibimos nos ayuda a mejorar la calidad de vida de los animales rescatados y a fomentar una tenencia responsable.
            </p>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-10">
                <!-- Item -->
                <div class="flex items-start space-x-5 group hover:scale-[1.02] transition-transform duration-300">
                    <div class="bg-cyan-100 p-4 rounded-full shadow-md text-2xl group-hover:bg-cyan-200 transition-colors flex items-center justify-center w-14 h-14">
                        🩺
                    </div>
                    <div>
                        <h3 class="font-bold text-xl text-gray-900 mb-2">Atención Veterinaria</h3>
                        <p class="text-gray-600">Consultas, vacunas, esterilizaciones y tratamientos médicos para garantizar su bienestar.</p>
                    </div>
                </div>

                <!-- Item -->
                <div class="flex items-start space-x-5 group hover:scale-[1.02] transition-transform duration-300">
                    <div class="bg-cyan-100 p-4 rounded-full shadow-md text-2xl group-hover:bg-cyan-200 transition-colors flex items-center justify-center w-14 h-14">
                        🏠
                    </div>
                    <div>
                        <h3 class="font-bold text-xl text-gray-900 mb-2">Albergue y Cuidado</h3>
                        <p class="text-gray-600">Alimentación, refugio seguro y cuidados diarios para los animales rescatados.</p>
                    </div>
                </div>

                <!-- Item -->
                <div class="flex items-start space-x-5 group hover:scale-[1.02] transition-transform duration-300">
                    <div class="bg-cyan-100 p-4 rounded-full shadow-md text-2xl group-hover:bg-cyan-200 transition-colors flex items-center justify-center w-14 h-14">
                        ❤️
                    </div>
                    <div>
                        <h3 class="font-bold text-xl text-gray-900 mb-2">Rehabilitación</h3>
                        <p class="text-gray-600">Terapias y cuidados especiales para animales con necesidades médicas o emocionales.</p>
                    </div>
                </div>

                <!-- Item -->
                <div class="flex items-start space-x-5 group hover:scale-[1.02] transition-transform duration-300">
                    <div class="bg-cyan-100 p-4 rounded-full shadow-md text-2xl group-hover:bg-cyan-200 transition-colors flex items-center justify-center w-14 h-14">
                        🐾
                    </div>
                    <div>
                        <h3 class="font-bold text-xl text-gray-900 mb-2">Campañas Educativas</h3>
                        <p class="text-gray-600">Programas de concientización sobre el cuidado y la tenencia responsable de mascotas.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>



       <!-- Cuentas Bancarias -->
<div class="mb-16">
    <h2 class="text-4xl font-extrabold text-gray-900 mb-10 text-center">
        💳 Cuentas Bancarias para Donaciones
    </h2>

    @if($fundaciones->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($fundaciones as $fundacion)
                <div class="bg-white/90 backdrop-blur-md rounded-2xl shadow-lg overflow-hidden border border-gray-100 hover:shadow-2xl hover:-translate-y-1 transition-all duration-300">
                    <div class="p-6">
                        <!-- Encabezado -->
                        <div class="flex items-center mb-6">
                            @if($fundacion->imagen)
                                <img src="{{ asset($fundacion->imagen) }}" alt="{{ $fundacion->nombre }}" class="w-16 h-16 rounded-full object-cover mr-4 border-2 border-cyan-500">
                            @else
                                <div class="w-16 h-16 rounded-full bg-cyan-100 flex items-center justify-center mr-4 text-3xl">
                                    🐾
                                </div>
                            @endif
                            <h3 class="text-xl font-bold text-gray-900">{{ $fundacion->nombre }}</h3>
                        </div>

                        <div class="space-y-4">
                            @if(!empty($fundacion->banco_nombre))
                                <div class="bg-gray-50 p-4 rounded-lg flex items-center">
                                    <span class="text-2xl mr-3">🏦</span>
                                    <div>
                                        <p class="text-sm text-gray-500">Banco</p>
                                        <p class="font-medium text-gray-900">{{ $fundacion->banco_nombre }}</p>
                                    </div>
                                </div>
                            @endif

                            @if(!empty($fundacion->tipo_cuenta))
                                <div class="bg-gray-50 p-4 rounded-lg flex items-center">
                                    <span class="text-2xl mr-3">{{ in_array(strtolower($fundacion->tipo_cuenta), ['ahorros', 'ahorro']) ? '🐖' : '👛' }}</span>
                                    <div>
                                        <p class="text-sm text-gray-500">Tipo de Cuenta</p>
                                        <p class="font-medium text-gray-900">{{ ucfirst($fundacion->tipo_cuenta) }}</p>
                                    </div>
                                </div>
                            @endif

                            @if(!empty($fundacion->numero_cuenta))
                                <div class="bg-gray-50 p-4 rounded-lg flex justify-between items-center">
                                    <div>
                                        <p class="text-sm text-gray-500">Número de Cuenta</p>
                                        <p class="font-mono text-gray-900 font-medium">{{ $fundacion->numero_cuenta }}</p>
                                    </div>
                                    <button type="button" onclick="copiarAlPortapapeles('{{ $fundacion->numero_cuenta }}', this)" class="text-cyan-600 hover:text-cyan-700 text-lg" title="Copiar">
                                        📋
                                    </button>
                                </div>
                            @endif

                            @if(!empty($fundacion->nombre_titular))
                                <div class="bg-gray-50 p-4 rounded-lg flex items-center">
                                    <span class="text-2xl mr-3">👤</span>
                                    <div>
                                        <p class="text-sm text-gray-500">Titular</p>
                                        <p class="font-medium text-gray-900">{{ $fundacion->nombre_titular }}</p>
                                    </div>
                                </div>
                            @endif

                            @if(!empty($fundacion->identificacion_titular))
                                <div class="bg-gray-50 p-4 rounded-lg flex justify-between items-center">
                                    <div>
                                        <p class="text-sm text-gray-500">{{ $fundacion->tipo_identificacion ?? 'Identificación' }}</p>
                                        <p class="font-medium text-gray-900">{{ $fundacion->identificacion_titular }}</p>
                                    </div>
                                    <button type="button" onclick="copiarAlPortapapeles('{{ $fundacion->identificacion_titular }}', this)" class="text-cyan-600 hover:text-cyan-700 text-lg" title="Copiar">
                                        📋
                                    </button>
                                </div>
                            @endif

                            @if(!empty($fundacion->email_contacto_pagos))
                                <div class="bg-gray-50 p-4 rounded-lg flex items-center">
                                    <span class="text-2xl mr-3">📧</span>
                                    <div>
                                        <p class="text-sm text-gray-500">Email</p>
                                        <p class="font-medium text-gray-900">{{ $fundacion->email_contacto_pagos }}</p>
                                    </div>
                                </div>
                            @endif

                            @if(!empty($fundacion->otros_metodos_pago))
                                <div class="bg-amber-50 border-l-4 border-amber-400 p-4 rounded-lg">
                                    <p class="text-sm text-amber-700">
                                        <span class="font-medium">💡 Otros métodos:</span> {{ $fundacion->otros_metodos_pago }}
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-12 bg-gray-50 rounded-xl">
            <span class="text-5xl block mb-4">ℹ️</span>
            <p class="text-gray-600">Próximamente estarán disponibles las cuentas para donaciones.</p>
        </div>
    @endif
</div>

<script>
function copiarAlPortapapeles(texto, btn) {
    navigator.clipboard.writeText(texto).then(() => {
        btn.innerHTML = "✅";
        setTimeout(() => btn.innerHTML = "📋", 1500);
    });
}
</script>

       <!-- Otras formas de ayudar -->
<div class="bg-white/90 backdrop-blur-md rounded-2xl shadow-xl p-10 border border-gray-100">
    <h2 class="text-4xl font-extrabold text-gray-900 mb-10 text-center">
        🌟 Otras formas de ayudar
    </h2>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <!-- Voluntariado -->
        <div class="text-center p-6 bg-cyan-50/60 rounded-xl hover:shadow-lg hover:scale-[1.03] transition-all duration-300">
            <div class="bg-cyan-100 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4 text-3xl">
                🤝
            </div>
            <h3 class="font-bold text-xl text-gray-900 mb-2">Voluntariado</h3>
            <p class="text-gray-600">Únete a nuestro equipo y ayuda directamente a los animales rescatados.</p>
        </div>

        <!-- Hogar Temporal -->
        <div class="text-center p-6 bg-cyan-50/60 rounded-xl hover:shadow-lg hover:scale-[1.03] transition-all duration-300">
            <div class="bg-cyan-100 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4 text-3xl">
                🏠
            </div>
            <h3 class="font-bold text-xl text-gray-900 mb-2">Hogar Temporal</h3>
            <p class="text-gray-600">Ofrece un lugar seguro para animales en proceso de adopción.</p>
        </div>

        <!-- Donación en Especie -->
        <div class="text-center p-6 bg-cyan-50/60 rounded-xl hover:shadow-lg hover:scale-[1.03] transition-all duration-300">
            <div class="bg-cyan-100 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4 text-3xl">
                🎁
            </div>
            <h3 class="font-bold text-xl text-gray-900 mb-2">Donación en Especie</h3>
            <p class="text-gray-600">Alimentos, medicinas, cobijas y más siempre son bienvenidos.</p>
        </div>
    </div>
</div>

</section>
@push('scripts')
<script>
    function copiarAlPortapapeles(texto) {
        // Crear un elemento de texto temporal
        const elementoTemporal = document.createElement('textarea');
        elementoTemporal.value = texto;
        elementoTemporal.setAttribute('readonly', '');
        elementoTemporal.style.position = 'absolute';
        elementoTemporal.style.left = '-9999px';
        document.body.appendChild(elementoTemporal);
        
        // Seleccionar el texto
        elementoTemporal.select();
        
        try {
            // Copiar al portapapeles
            const exito = document.execCommand('copy');
            if (exito) {
                // Mostrar notificación
                const notificacion = document.createElement('div');
                notificacion.className = 'fixed bottom-4 right-4 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg flex items-center';
                notificacion.innerHTML = `
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    ¡Número de cuenta copiado!
                `;
                document.body.appendChild(notificacion);
                
                // Eliminar la notificación después de 3 segundos
                setTimeout(() => {
                    notificacion.classList.add('opacity-0', 'transition-opacity', 'duration-500');
                    setTimeout(() => {
                        document.body.removeChild(notificacion);
                    }, 500);
                }, 2000);
            }
        } catch (err) {
            console.error('Error al copiar al portapapeles:', err);
        }
        
        // Limpiar
        document.body.removeChild(elementoTemporal);
    }
    
    // Agregar animación al hacer clic en el botón de copiar
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('[onclick^="copiarAlPortapapeles"]').forEach(boton => {
            boton.addEventListener('click', function(e) {
                // Agregar clase de animación
                const icono = this.querySelector('i');
                if (icono) {
                    icono.classList.remove('fa-copy');
                    icono.classList.add('fa-check');
                    
                    // Volver al icono original después de 2 segundos
                    setTimeout(() => {
                        icono.classList.remove('fa-check');
                        icono.classList.add('fa-copy');
                    }, 2000);
                }
            });
        });
    });
</script>
@endpush

@push('scripts')
<script>
    // Función para copiar texto al portapapeles
    function copiarAlPortapapeles(texto, boton) {
        if (!texto) return;
        
        // Crear un elemento de texto temporal
        const elementoTemporal = document.createElement('textarea');
        elementoTemporal.value = texto;
        elementoTemporal.setAttribute('readonly', '');
        elementoTemporal.style.position = 'absolute';
        elementoTemporal.style.left = '-9999px';
        document.body.appendChild(elementoTemporal);
        
        // Seleccionar y copiar el texto
        elementoTemporal.select();
        document.execCommand('copy');
        
        // Eliminar el elemento temporal
        document.body.removeChild(elementoTemporal);
        
        // Mostrar notificación de éxito
        const notificacion = document.createElement('div');
        notificacion.className = 'fixed bottom-4 right-4 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg flex items-center space-x-2 z-50';
        notificacion.innerHTML = `
            <i class="fas fa-check-circle"></i>
            <span>¡Copiado al portapapeles!</span>
        `;
        document.body.appendChild(notificacion);
        
        // Eliminar la notificación después de 2 segundos
        setTimeout(() => {
            notificacion.classList.add('opacity-0', 'transition-opacity', 'duration-500');
            setTimeout(() => {
                document.body.removeChild(notificacion);
            }, 500);
        }, 2000);
        
        // Cambiar temporalmente el ícono del botón
        if (boton) {
            const icono = boton.querySelector('i');
            if (icono) {
                const iconoOriginal = icono.className;
                icono.className = 'fas fa-check text-green-500';
                setTimeout(() => {
                    icono.className = iconoOriginal;
                }, 2000);
            }
        }
    }
    
    // Inicializar tooltips
    document.addEventListener('DOMContentLoaded', function() {
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>
@endpush

@push('styles')
<style>
    /* Estilos para las tarjetas de fundación */
    .fundacion-card {
        transition: all 0.3s ease;
    }
    
    .fundacion-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }
    
    /* Estilos para la notificación */
    .notificacion-copiar {
        animation: slideIn 0.3s ease-out;
    }
    
    @keyframes slideIn {
        from {
            transform: translateY(100px);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }
    
    /* Mejoras de accesibilidad */
    button:focus {
        outline: 2px solid #0891b2;
        outline-offset: 2px;
    }
    
    /* Efecto de hover en los botones de copiar */
    .btn-copiar {
        transition: all 0.2s ease;
    }
    
    .btn-copiar:hover {
        transform: scale(1.1);
    }
    
    .btn-copiar:active {
        transform: scale(0.95);
    }
</style>
@endpush
@endsection