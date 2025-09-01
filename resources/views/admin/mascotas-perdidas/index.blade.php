@extends('layouts.app')

@section('title', 'Mascotas Perdidas - Panel de Administración')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Mascotas Perdidas</h1>
    </div>

    <!-- Filtros y búsqueda -->
    <div class="bg-white shadow-sm rounded-lg p-4 mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div class="w-full md:w-1/3">
                <input type="text" id="search" placeholder="Buscar por nombre..." class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
            </div>
            <div class="flex space-x-2">
                <select id="filter-status" class="px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">Todos los estados</option>
                    <option value="perdido">Perdido</option>
                    <option value="encontrado">Encontrado</option>
                </select>
                <button id="apply-filters" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                    Aplicar filtros
                </button>
            </div>
        </div>
    </div>

    <!-- Tabla de mascotas perdidas -->
    <div class="bg-white shadow-sm rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mascota</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Propietario</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha de Reporte</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200" id="pets-container">
                    @forelse($mascotas as $mascota)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <img class="h-10 w-10 rounded-full object-cover" src="{{ $mascota->imagen_url }}" alt="{{ $mascota->nombre }}">
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $mascota->nombre }}</div>
                                    <div class="text-sm text-gray-500">{{ ucfirst($mascota->tipo) }} • {{ $mascota->raza ? ucfirst($mascota->raza) : 'Raza no especificada' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($mascota->usuario)
                                <div class="text-sm text-gray-900">{{ $mascota->usuario->nombre }}</div>
                                <div class="text-sm text-gray-500">{{ $mascota->usuario->email }}</div>
                            @else
                                <div class="text-sm text-gray-900">Contacto: {{ $mascota->nombre_contacto ?? 'No especificado' }}</div>
                                <div class="text-sm text-gray-500">{{ $mascota->email_contacto ?? '' }}</div>
                                <div class="text-sm text-gray-500">{{ $mascota->telefono_contacto ?? '' }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $mascota->created_at->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($mascota->estado === 'encontrado')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Encontrado
                                </span>
                            @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    Perdido
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <a href="{{ route('animales-perdidos.show', $mascota->id) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Ver</a>
                            @if($mascota->estado === 'perdido')
                                <form action="{{ route('animales-perdidos.marcar-encontrado', $mascota->id) }}" method="POST" class="inline-block">
                                    @csrf
                                    <button type="submit" class="text-green-600 hover:text-green-900" onclick="return confirm('¿Marcar esta mascota como encontrada?')">
                                        Marcar como encontrada
                                    </button>
                                </form>
                            @endif
                            @if($mascota->recompensa)
                                <div class="mt-1 text-xs text-yellow-600">Recompensa: {{ $mascota->recompensa }}</div>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                            No se encontraron mascotas perdidas.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Paginación -->
        <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
            {{ $mascotas->links() }}
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search');
    const filterStatus = document.getElementById('filter-status');
    const applyFiltersBtn = document.getElementById('apply-filters');
    
    function applyFilters() {
        const searchTerm = searchInput.value.toLowerCase();
        const statusFilter = filterStatus.value.toLowerCase();
        const rows = document.querySelectorAll('#pets-container tr');
        
        rows.forEach(row => {
            const petName = row.querySelector('.text-gray-900').textContent.toLowerCase();
            const petStatus = row.querySelector('.text-xs').textContent.trim().toLowerCase();
            const matchesSearch = petName.includes(searchTerm);
            const matchesStatus = !statusFilter || petStatus === statusFilter;
            
            if (matchesSearch && matchesStatus) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }
    
    // Aplicar filtros al hacer clic en el botón
    applyFiltersBtn.addEventListener('click', applyFilters);
    
    // También se pueden aplicar los filtros al presionar Enter en el campo de búsqueda
    searchInput.addEventListener('keyup', function(e) {
        if (e.key === 'Enter') {
            applyFilters();
        }
    });
});
</script>
@endpush
@endsection
