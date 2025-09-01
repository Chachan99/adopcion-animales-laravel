@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-cyan-100 py-12 px-4 lg:px-0">
    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-3xl shadow-xl p-8 border-2 border-cyan-200 mb-10">
            <h1 class="text-3xl font-extrabold text-blue-900 mb-6 text-center">Editar Perfil</h1>
            @if(session('status'))
                <div class="mb-4 p-4 bg-green-100 border border-green-300 text-green-800 rounded-lg">
                    {{ session('status') }}
                </div>
            @endif
            @if($errors->any())
                <div class="mb-4 p-4 bg-red-100 border border-red-300 text-red-800 rounded-lg">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form action="{{ route('profile.update-publico') }}" method="POST" class="space-y-6" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="flex flex-col items-center mb-4">
                    @if($user->imagen)
                        <img src="{{ asset('test/' . $user->imagen) }}" alt="Foto de perfil" class="w-24 h-24 rounded-full object-cover border-4 border-cyan-300 mb-2">
                    @else
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($user->nombre) }}&background=00bcd4&color=fff" alt="Avatar" class="w-24 h-24 rounded-full object-cover border-4 border-cyan-300 mb-2">
                    @endif
                    <label class="block text-sm font-semibold text-blue-900 mb-2">Foto de Perfil</label>
                    <input type="file" name="imagen" accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-cyan-50 file:text-cyan-700 hover:file:bg-cyan-100" />
                    <span class="text-xs text-gray-500 mt-1">Formatos permitidos: jpg, jpeg, png, gif. Máx: 2MB.</span>
                </div>
                <div>
                    <label for="nombre" class="block text-sm font-semibold text-blue-900 mb-2">Nombre</label>
                    <input type="text" id="nombre" name="nombre" value="{{ old('nombre', $user->nombre) }}" required
                        class="w-full px-4 py-3 rounded-lg border border-cyan-300 focus:border-cyan-500 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 transition-colors">
                </div>
                <div>
                    <label for="email" class="block text-sm font-semibold text-blue-900 mb-2">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required
                        class="w-full px-4 py-3 rounded-lg border border-cyan-300 focus:border-cyan-500 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 transition-colors">
                </div>
                <div>
                    <label for="password" class="block text-sm font-semibold text-blue-900 mb-2">Nueva Contraseña <span class="text-gray-500 text-xs">(opcional)</span></label>
                    <input type="password" id="password" name="password"
                        class="w-full px-4 py-3 rounded-lg border border-cyan-300 focus:border-cyan-500 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 transition-colors"
                        placeholder="Dejar en blanco para no cambiar">
                </div>
                <div>
                    <label for="password_confirmation" class="block text-sm font-semibold text-blue-900 mb-2">Confirmar Nueva Contraseña</label>
                    <input type="password" id="password_confirmation" name="password_confirmation"
                        class="w-full px-4 py-3 rounded-lg border border-cyan-300 focus:border-cyan-500 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 transition-colors"
                        placeholder="Repite la nueva contraseña">
                </div>
                <div class="text-center mt-8">
                    <button type="submit" class="bg-cyan-400 hover:bg-cyan-300 text-blue-900 font-extrabold px-8 py-3 rounded-full text-lg shadow-lg hover:shadow-xl transition-all duration-300 tracking-wide">
                        Guardar Cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection 