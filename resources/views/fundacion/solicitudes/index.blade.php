@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-cyan-100 py-12 px-4 lg:px-0">
    <div class="max-w-5xl mx-auto">
        <div class="bg-white rounded-3xl shadow-xl p-8 border-2 border-cyan-200 mb-10">
            <h1 class="text-3xl font-extrabold text-blue-900 mb-8 text-center">Solicitudes de Adopción</h1>
            @if(session('success'))
                <div class="mb-6 p-4 bg-green-100 border border-green-300 text-green-800 rounded-lg text-center font-semibold shadow">
                    {{ session('success') }}
                </div>
            @endif
            <div class="grid grid-cols-1 gap-6">
                @forelse($solicitudes as $solicitud)
                <div class="bg-cyan-50 rounded-2xl shadow p-6 flex flex-col md:flex-row md:items-center md:justify-between border border-cyan-100 hover:shadow-lg transition">
                    <div class="flex items-center gap-4 mb-4 md:mb-0">
                        <div class="w-14 h-14 rounded-full overflow-hidden border-2 border-cyan-300 bg-white flex items-center justify-center">
                            @if($solicitud->usuario && $solicitud->usuario->imagen)
                                <img src="{{ $solicitud->usuario->imagen_url }}" alt="Foto de perfil" class="object-cover w-full h-full">
                            @else
                                <span class="text-2xl">🐾</span>
                            @endif
                        </div>
                        <div>
                            <div class="font-bold text-blue-900 text-lg">{{ $solicitud->usuario->nombre ?? 'Usuario no registrado' }}</div>
                            <div class="text-gray-600 text-sm">Animal: <span class="font-semibold">{{ $solicitud->animal->nombre ?? '-' }}</span></div>
                        </div>
                    </div>
                    <div class="flex flex-col md:flex-row md:items-center gap-4 flex-1 justify-between">
                        <div class="flex flex-col md:flex-row md:items-center gap-4">
                            <span class="inline-block px-3 py-1 rounded-full text-xs font-bold
                                @if($solicitud->estado === 'pendiente') bg-yellow-100 text-yellow-800
                                @elseif($solicitud->estado === 'aprobado') bg-green-100 text-green-800
                                @else bg-red-100 text-red-800 @endif">
                                {{ ucfirst($solicitud->estado) }}
                            </span>
                            <span class="text-gray-500 text-xs">{{ $solicitud->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                        <div class="flex gap-2 mt-2 md:mt-0">
                            @if($solicitud->estado === 'pendiente')
                                <form action="{{ route('fundacion.solicitudes.estado', ['id' => $solicitud->id, 'estado' => 'aprobado']) }}" method="POST" onsubmit="return confirm('¿Estás seguro de aceptar esta solicitud?');">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold px-4 py-2 rounded-full shadow transition">Aceptar</button>
                                </form>
                                <form action="{{ route('fundacion.solicitudes.estado', ['id' => $solicitud->id, 'estado' => 'rechazado']) }}" method="POST" class="rechazo-form">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="comentario" value="">
                                    <button type="button" class="bg-red-500 hover:bg-red-700 text-white font-bold px-4 py-2 rounded-full shadow transition btn-rechazar">Rechazar</button>
                                </form>
                            @else
                                <span class="text-gray-400 font-semibold">Sin acciones</span>
                            @endif
                            <a href="{{ route('fundacion.solicitudes.detalle', $solicitud->id) }}" class="bg-cyan-600 hover:bg-cyan-800 text-white font-bold px-4 py-2 rounded-full shadow transition">Ver Detalles</a>
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center text-gray-500 py-12 text-lg font-semibold">No hay solicitudes de adopción registradas.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.btn-rechazar').forEach(function(btn) {
            btn.addEventListener('click', function(e) {
                const form = btn.closest('form');
                let comentario = prompt('Por favor, ingresa el motivo del rechazo (obligatorio):');
                if (comentario && comentario.trim() !== '') {
                    form.querySelector('input[name="comentario"]').value = comentario;
                    form.submit();
                } else {
                    alert('Debes ingresar un motivo para rechazar la solicitud.');
                }
            });
        });
    });
</script>
@endsection
