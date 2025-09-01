@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100">
    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h1 class="text-2xl font-bold mb-6">Reportes y Estadísticas</h1>

            <!-- Filtros de reporte -->
            <div class="mb-6">
                <form action="{{ route('admin.reportes.filtrar') }}" method="GET" class="flex space-x-4">
                    <div class="flex-1">
                        <label class="block text-sm font-medium text-gray-700">Tipo de Reporte</label>
                        <select name="tipo" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="animales">Animales por Tipo</option>
                            <option value="donaciones">Donaciones por Mes</option>
                            <option value="solicitudes">Solicitudes por Estado</option>
                        </select>
                    </div>
                    <div class="flex-1">
                        <label class="block text-sm font-medium text-gray-700">Período</label>
                        <select name="periodo" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="mes">Este Mes</option>
                            <option value="trimestre">Este Trimestre</option>
                            <option value="ano">Este Año</option>
                        </select>
                    </div>
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-800">
                        Generar Reporte
                    </button>
                </form>
            </div>

            <!-- Reporte de Animales por Tipo -->
            <div class="mb-8">
                <h2 class="text-xl font-semibold mb-4">Animales por Tipo</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    @foreach($reportes['animales_por_tipo'] as $tipo)
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex justify-between items-center">
                            <span class="font-semibold">{{ $tipo->tipo }}</span>
                            <span class="text-green-600 font-bold">{{ $tipo->total }}</span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Reporte de Donaciones por Mes -->
            <div class="mb-8">
                <h2 class="text-xl font-semibold mb-4">Donaciones por Mes</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mes</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Donaciones</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Monto Total</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($reportes['donaciones_por_mes'] as $mes)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ date('F Y', strtotime($mes->mes)) }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm text-gray-900">{{ $mes->total }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-green-600 font-semibold">${{ number_format($mes->monto_total, 2) }}</span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Reporte de Solicitudes por Estado -->
            <div class="mb-8">
                <h2 class="text-xl font-semibold mb-4">Solicitudes por Estado</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    @foreach($reportes['solicitudes_por_estado'] as $estado)
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex justify-between items-center">
                            <span class="font-semibold">{{ ucfirst($estado->estado) }}</span>
                            <span class="text-green-600 font-bold">{{ $estado->total }}</span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Exportar reportes -->
            <div class="mt-6">
                <div class="flex space-x-4">
                    <a href="{{ route('admin.reportes.exportar', ['formato' => 'pdf']) }}" 
                       class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-800">
                        Exportar PDF
                    </a>
                    <a href="{{ route('admin.reportes.exportar', ['formato' => 'excel']) }}" 
                       class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-800">
                        Exportar Excel
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
