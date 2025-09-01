@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100">
    <div class="max-w-5xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h1 class="text-2xl font-bold mb-6 text-blue-900">Donaciones Recibidas</h1>
            @if($donaciones->isEmpty())
                <p class="text-gray-600">Aún no has recibido donaciones.</p>
            @else
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white">
                    <thead>
                        <tr>
                            <th class="px-4 py-2 text-left">ID</th>
                            <th class="px-4 py-2 text-left">Usuario</th>
                            <th class="px-4 py-2 text-left">Monto</th>
                            <th class="px-4 py-2 text-left">Fecha</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($donaciones as $donacion)
                        <tr class="border-b">
                            <td class="px-4 py-2">{{ $donacion->id }}</td>
                            <td class="px-4 py-2">{{ $donacion->usuario->nombre ?? 'Anónimo' }}</td>
                            <td class="px-4 py-2">${{ number_format($donacion->monto, 2) }}</td>
                            <td class="px-4 py-2">{{ $donacion->created_at->format('d/m/Y') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection 