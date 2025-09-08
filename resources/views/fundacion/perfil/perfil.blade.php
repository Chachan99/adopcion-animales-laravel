@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100">
    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h1 class="text-2xl font-bold mb-6">Mi Fundación</h1>
            
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Imagen de la fundación -->
                <div>
                    <img src="{{ $fundacion->imagen ? asset($fundacion->imagen) : asset('img/defaults/fundacion-default.jpg') }}"
                         alt="{{ $fundacion->nombre }}"
                         class="w-full h-64 object-cover rounded-lg mb-4">
                </div>

                <!-- Información de la fundación -->
                <div>
                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="font-semibold">Nombre:</span>
                            <span>{{ $fundacion->nombre }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="font-semibold">Descripción:</span>
                            <span>{{ $fundacion->descripcion }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="font-semibold">Dirección:</span>
                            <span>{{ $fundacion->direccion }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="font-semibold">Teléfono:</span>
                            <span>{{ $fundacion->telefono }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="font-semibold">Email:</span>
                            <span>{{ $fundacion->email }}</span>
                        </div>
                        @if($fundacion->sitio_web)
                        <div class="flex justify-between items-center">
                            <span class="font-semibold">Sitio Web:</span>
                            <a href="{{ $fundacion->sitio_web }}" target="_blank" class="text-blue-600 hover:text-blue-800">{{ $fundacion->sitio_web }}</a>
                        </div>
                        @endif
                        @if($fundacion->facebook)
                        <div class="flex justify-between items-center">
                            <span class="font-semibold">Facebook:</span>
                            <a href="{{ $fundacion->facebook }}" target="_blank" class="text-blue-600 hover:text-blue-800">Facebook</a>
                        </div>
                        @endif
                        @if($fundacion->instagram)
                        <div class="flex justify-between items-center">
                            <span class="font-semibold">Instagram:</span>
                            <a href="{{ $fundacion->instagram }}" target="_blank" class="text-blue-600 hover:text-blue-800">Instagram</a>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="mt-6 flex justify-end">
                <a href="{{ route('fundacion.perfil.editar') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-800">
                    Editar Perfil
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
