@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100">
    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h1 class="text-2xl font-bold mb-6">Solicitudes de Adopción</h1>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Usuario</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Animal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fundación</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fecha</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($solicitudes as $solicitud)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $solicitud->id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $solicitud->usuario->nombre ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $solicitud->animal->nombre ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $solicitud->fundacion->nombre ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-block px-2 py-1 rounded-full text-xs font-bold
                                    @if($solicitud->estado === 'pendiente') bg-yellow-100 text-yellow-800
                                    @elseif($solicitud->estado === 'aceptado') bg-green-100 text-green-800
                                    @else bg-red-100 text-red-800 @endif">
                                    {{ ucfirst($solicitud->estado) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $solicitud->created_at->format('d/m/Y H:i') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                @if($solicitud->estado === 'pendiente')
                                    <div class="flex space-x-2">
                                        <form action="{{ route('admin.solicitudes.estado', ['id' => $solicitud->id, 'estado' => 'aceptado']) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="bg-green-600 text-white px-3 py-1 rounded hover:bg-green-800">Aceptar</button>
                                        </form>
                                        <form action="{{ route('admin.solicitudes.estado', ['id' => $solicitud->id, 'estado' => 'rechazado']) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="bg-red-600 text-white px-3 py-1 rounded hover:bg-red-800">Rechazar</button>
                                        </form>
                                    </div>
                                @else
                                    <span class="text-gray-400">Sin acciones</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection 