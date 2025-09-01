@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100">
    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h1 class="text-2xl font-bold mb-6">Donaciones Recibidas</h1>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Donante</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Monto</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Método</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fecha</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($donaciones as $donacion)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $donacion->id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $donacion->usuario->nombre ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-green-600 font-semibold">${{ number_format($donacion->monto, 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $donacion->metodo }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $donacion->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection 