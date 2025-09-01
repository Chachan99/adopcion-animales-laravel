@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-cyan-100 py-12 px-4 lg:px-0">
    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-3xl shadow-xl p-8 border-2 border-cyan-200 mb-10">
            <h1 class="text-3xl font-extrabold text-blue-900 mb-6 text-center">Panel de Usuario</h1>
            <p class="text-lg text-gray-700 mb-8 text-center">¡Bienvenido, {{ auth()->user()->nombre }}! Desde aquí puedes gestionar tu perfil, ver tus solicitudes de adopción y tus donaciones realizadas.</p>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-center">
                <a href="{{ route('profile.edit-publico') }}" class="bg-cyan-100 hover:bg-cyan-200 rounded-2xl p-6 shadow transition flex flex-col items-center">
                    <i class="fas fa-user-edit text-3xl text-cyan-600 mb-2"></i>
                    <span class="font-bold text-blue-900">Editar Perfil</span>
                </a>
                <a href="{{ route('publico.solicitudes') }}" class="bg-cyan-100 hover:bg-cyan-200 rounded-2xl p-6 shadow transition flex flex-col items-center">
                    <i class="fas fa-paw text-3xl text-cyan-600 mb-2"></i>
                    <span class="font-bold text-blue-900">Solicitudes de Adopción</span>
                </a>
                <a href="{{ route('publico.donaciones') }}" class="bg-cyan-100 hover:bg-cyan-200 rounded-2xl p-6 shadow transition flex flex-col items-center">
                    <i class="fas fa-donate text-3xl text-cyan-600 mb-2"></i>
                    <span class="font-bold text-blue-900">Donaciones Realizadas</span>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection 