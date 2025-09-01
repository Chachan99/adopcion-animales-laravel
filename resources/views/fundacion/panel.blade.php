@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100 flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-lg p-8 max-w-xl w-full">
        <h1 class="text-2xl font-bold mb-4 text-center">Panel de Fundación</h1>
        <p class="text-gray-700 text-center mb-6">¡Bienvenido al panel de tu fundación! Aquí podrás gestionar tus animales, donaciones y solicitudes de adopción.</p>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
            <a href="{{ route('fundacion.animales') }}" class="bg-blue-100 hover:bg-blue-200 rounded-lg p-4 flex flex-col items-center transition">
                <svg class="w-8 h-8 text-blue-600 mb-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M8 15s1.5-2 4-2 4 2 4 2"/></svg>
                <span class="font-bold text-blue-800">Mis Animales</span>
            </a>
            <a href="{{ route('fundacion.solicitudes') }}" class="bg-cyan-100 hover:bg-cyan-200 rounded-lg p-4 flex flex-col items-center transition">
                <svg class="w-8 h-8 text-cyan-600 mb-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                <span class="font-bold text-cyan-800">Solicitudes</span>
            </a>
            <a href="{{ route('fundacion.noticias.index') }}" class="bg-purple-100 hover:bg-purple-200 rounded-lg p-4 flex flex-col items-center transition">
                <svg class="w-8 h-8 text-purple-600 mb-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 20H5a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v1m2 13a2 2 0 0 1-2-2V7m2 13a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                <span class="font-bold text-purple-800">Noticias</span>
            </a>
        </div>
        <div class="text-center text-gray-500 text-sm">Puedes acceder rápidamente a la gestión de tus recursos desde aquí.</div>
    </div>
</div>
@endsection 