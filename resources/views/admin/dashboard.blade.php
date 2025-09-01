@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-cyan-50 to-white py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Encabezado con breadcrumbs -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8">
            <div class="flex items-center">
                <svg class="w-8 h-8 text-cyan-600 mr-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M12 4v16m8-8H4"/>
                </svg>
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">Panel de Administración</h1>
                    <nav class="flex text-sm" aria-label="Breadcrumb">
                        <ol class="inline-flex items-center space-x-1">
                            <li class="inline-flex items-center">
                                <a href="{{ route('admin.dashboard') }}" class="text-cyan-600 hover:text-cyan-800 inline-flex items-center">
                                    Dashboard
                                </a>
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="mt-4 md:mt-0">
                <span class="text-sm text-gray-500">Última actualización: {{ now()->format('d/m/Y H:i') }}</span>
            </div>
        </div>

        <!-- Tarjetas de estadísticas -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-10">
            <!-- Tarjeta Usuarios -->
            <div class="bg-white rounded-xl shadow-md hover:shadow-lg transition-shadow p-6 flex flex-col items-center border-t-4 border-cyan-500 transform hover:-translate-y-1 transition-transform">
                <div class="bg-cyan-100 p-3 rounded-full mb-3">
                    <svg class="w-8 h-8 text-cyan-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M17 20h5v-2a4 4 0 0 0-3-3.87"/>
                        <path d="M9 20H4v-2a4 4 0 0 1 3-3.87"/>
                        <circle cx="12" cy="7" r="4"/>
                    </svg>
                </div>
                <div class="text-2xl font-bold text-gray-800">{{ $estadisticas['total_usuarios'] }}</div>
                <div class="text-gray-600 mb-3">Usuarios</div>
                <a href="{{ route('admin.usuarios') }}" class="text-sm text-cyan-600 hover:text-cyan-800 font-medium flex items-center">
                    Ver todos
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>

            <!-- Tarjeta Fundaciones -->
            <div class="bg-white rounded-xl shadow-md hover:shadow-lg transition-shadow p-6 flex flex-col items-center border-t-4 border-green-500 transform hover:-translate-y-1 transition-transform">
                <div class="bg-green-100 p-3 rounded-full mb-3">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M3 10v6a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-6"/>
                        <path d="M16 10V6a4 4 0 0 0-8 0v4"/>
                    </svg>
                </div>
                <div class="text-2xl font-bold text-gray-800">{{ $estadisticas['total_fundaciones'] }}</div>
                <div class="text-gray-600 mb-3">Fundaciones</div>
                <a href="{{ route('admin.fundaciones') }}" class="text-sm text-green-600 hover:text-green-800 font-medium flex items-center">
                    Ver todas
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>

            <!-- Tarjeta Animales -->
            <div class="bg-white rounded-xl shadow-md hover:shadow-lg transition-shadow p-6 flex flex-col items-center border-t-4 border-yellow-500 transform hover:-translate-y-1 transition-transform">
                <div class="bg-yellow-100 p-3 rounded-full mb-3">
                    <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="10"/>
                        <path d="M8 15s1.5-2 4-2 4 2 4 2"/>
                    </svg>
                </div>
                <div class="text-2xl font-bold text-gray-800">{{ $estadisticas['total_animales'] }}</div>
                <div class="text-gray-600 mb-3">Animales</div>
                <a href="{{ route('admin.animales') }}" class="text-sm text-yellow-600 hover:text-yellow-800 font-medium flex items-center">
                    Ver todos
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>

            <!-- Tarjeta Donaciones -->
            <div class="bg-white rounded-xl shadow-md hover:shadow-lg transition-shadow p-6 flex flex-col items-center border-t-4 border-blue-500 transform hover:-translate-y-1 transition-transform">
                <div class="bg-blue-100 p-3 rounded-full mb-3">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M12 8v4l3 3"/>
                        <circle cx="12" cy="12" r="10"/>
                    </svg>
                </div>
                <div class="text-2xl font-bold text-gray-800">{{ $estadisticas['total_donaciones'] }}</div>
                <div class="text-gray-600 mb-3">Donaciones</div>
                <a href="{{ route('admin.donaciones') }}" class="text-sm text-blue-600 hover:text-blue-800 font-medium flex items-center">
                    Ver todas
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>

            <!-- Tarjeta Solicitudes -->
            <div class="bg-white rounded-xl shadow-md hover:shadow-lg transition-shadow p-6 flex flex-col items-center border-t-4 border-pink-500 transform hover:-translate-y-1 transition-transform">
                <div class="bg-pink-100 p-3 rounded-full mb-3">
                    <svg class="w-8 h-8 text-pink-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                        <polyline points="17 8 12 3 7 8"/>
                        <line x1="12" y1="3" x2="12" y2="15"/>
                    </svg>
                </div>
                <div class="text-2xl font-bold text-gray-800">{{ $estadisticas['total_solicitudes'] }}</div>
                <div class="text-gray-600 mb-3">Solicitudes</div>
                <a href="{{ route('admin.solicitudes') }}" class="text-sm text-pink-600 hover:text-pink-800 font-medium flex items-center">
                    Ver todas
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>
        </div>

        <!-- Sección de gráficos y tablas -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Gráfico de donaciones (placeholder) -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-semibold text-gray-800 flex items-center">
                        <svg class="w-6 h-6 text-blue-500 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M12 8v4l3 3"/>
                            <circle cx="12" cy="12" r="10"/>
                        </svg>
                        Donaciones Recientes
                    </h2>
                    <select class="text-sm border border-gray-300 rounded-md px-3 py-1 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option>Últimos 7 días</option>
                        <option>Últimos 30 días</option>
                        <option selected>Últimos 90 días</option>
                    </select>
                </div>
                <div class="h-64 bg-gray-50 rounded-lg flex items-center justify-center mb-4">
                    <!-- Aquí iría un gráfico con Chart.js o similar -->
                    <p class="text-gray-500">Gráfico de donaciones por mes</p>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fundación</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Monto</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($donaciones as $donacion)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $donacion->created_at->format('d/m/Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $donacion->fundacion->nombre ?? 'Anónimo' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-green-600">
                                    ${{ number_format($donacion->monto, 2) }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-4 flex justify-end">
                    <a href="{{ route('admin.donaciones') }}" class="text-sm text-blue-600 hover:text-blue-800 font-medium flex items-center">
                        Ver todas las donaciones
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Tabla de solicitudes -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-semibold text-gray-800 flex items-center">
                        <svg class="w-6 h-6 text-pink-500 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                            <polyline points="17 8 12 3 7 8"/>
                            <line x1="12" y1="3" x2="12" y2="15"/>
                        </svg>
                        Últimas Solicitudes
                    </h2>
                    <div class="flex space-x-2">
                        <button class="text-sm bg-gray-100 hover:bg-gray-200 px-3 py-1 rounded-md transition">
                            Todas
                        </button>
                        <button class="text-sm bg-yellow-100 hover:bg-yellow-200 px-3 py-1 rounded-md transition">
                            Pendientes
                        </button>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Animal</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Solicitante</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($solicitudes as $solicitud)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <img class="h-10 w-10 rounded-full object-cover" src="{{ $solicitud->animal->imagen_url ?? asset('img/default-pet.jpg') }}" alt="{{ $solicitud->animal->nombre ?? 'Animal' }}">
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $solicitud->animal->nombre ?? '-' }}</div>
                                            <div class="text-sm text-gray-500">{{ $solicitud->animal->especie ?? '' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $solicitud->usuario->name ?? 'Anónimo' }}</div>
                                    <div class="text-sm text-gray-500">{{ $solicitud->usuario->email ?? '' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                        @if($solicitud->estado === 'pendiente') bg-yellow-100 text-yellow-800
                                        @elseif($solicitud->estado === 'aceptado') bg-green-100 text-green-800
                                        @else bg-red-100 text-red-800 @endif">
                                        {{ ucfirst($solicitud->estado) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $solicitud->created_at->format('d/m/Y') }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-4 flex justify-between items-center">
                    <span class="text-sm text-gray-500">Mostrando {{ $solicitudes->count() }} de {{ $estadisticas['total_solicitudes'] }} solicitudes</span>
                    <a href="{{ route('admin.solicitudes') }}" class="text-sm text-pink-600 hover:text-pink-800 font-medium flex items-center">
                        Ver todas las solicitudes
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>

        <!-- Sección de actividad reciente -->
        <div class="mt-8 bg-white rounded-xl shadow-md p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                <svg class="w-6 h-6 text-gray-500 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2z"/>
                </svg>
                Actividad Reciente
            </h2>
            <div class="space-y-4">
                @foreach($actividades as $actividad)
                <div class="flex items-start pb-4 border-b border-gray-100 last:border-0">
                    <div class="flex-shrink-0 mt-1">
                        <div class="h-8 w-8 rounded-full bg-{{ $actividad['color'] }}-100 flex items-center justify-center">
                            <svg class="h-5 w-5 text-{{ $actividad['color'] }}-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                {!! $actividad['icono'] !!}
                            </svg>
                        </div>
                    </div>
                    <div class="ml-3 flex-1">
                        <div class="text-sm font-medium text-gray-900">
                            {{ $actividad['titulo'] }}
                            <span class="text-xs text-gray-500 ml-2">{{ $actividad['fecha']->diffForHumans() }}</span>
                        </div>
                        <div class="text-sm text-gray-500">
                            {{ $actividad['descripcion'] }}
                        </div>
                    </div>
                    <div class="text-xs text-gray-500">
                        {{ $actividad['fecha']->format('H:i') }}
                    </div>
                </div>
                @endforeach
            </div>
            <div class="mt-4 text-center">
                <a href="#" class="text-sm text-gray-600 hover:text-gray-800 font-medium">Ver todo el historial de actividad</a>
            </div>
        </div>
    </div>
</div>
@endsection