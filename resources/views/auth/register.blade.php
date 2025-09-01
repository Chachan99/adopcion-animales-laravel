<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gradient-to-br from-blue-50 to-cyan-100 min-h-screen">
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gradient-to-br from-blue-50 to-cyan-100">
        <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-2xl overflow-hidden sm:rounded-2xl border-2 border-cyan-200">
            <div class="text-center mb-8">
                <div class="flex justify-center mb-4">
                    <svg class="w-12 h-12 text-cyan-500" fill="currentColor" viewBox="0 0 24 24">
                        <circle cx="6" cy="10" r="2"/>
                        <circle cx="18" cy="10" r="2"/>
                        <circle cx="12" cy="4" r="2.5"/>
                        <ellipse cx="12" cy="17" rx="5" ry="7"/>
                    </svg>
                </div>
                <h2 class="text-3xl font-extrabold text-blue-900 mb-2">Crear Cuenta</h2>
                <p class="text-cyan-700">Únete a nuestra comunidad de adopción</p>
            </div>

            <form method="POST" action="{{ route('register') }}" novalidate>
                @csrf

                <!-- Name -->
                <div class="mb-6">
                    <label for="name" class="block text-sm font-semibold text-blue-900 mb-2">{{ __("Nombre") }}</label>
                    <input
                        id="name"
                        type="text"
                        name="name"
                        value="{{ old('name') }}"
                        required
                        autofocus
                        autocomplete="name"
                        class="w-full px-4 py-3 rounded-lg border border-cyan-300 focus:border-cyan-500 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 transition-colors"
                        placeholder="Tu nombre completo"
                    />
                    <x-input-error :messages="$errors->get('name')" class="mt-2 text-red-600 text-sm" />
                </div>

                <!-- Email Address -->
                <div class="mb-6">
                    <label for="email" class="block text-sm font-semibold text-blue-900 mb-2">{{ __("Email") }}</label>
                    <input
                        id="email"
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        autocomplete="username"
                        class="w-full px-4 py-3 rounded-lg border border-cyan-300 focus:border-cyan-500 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 transition-colors"
                        placeholder="tu@email.com"
                    />
                    <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-600 text-sm" />
                </div>

                <!-- Password -->
                <div class="mb-6">
                    <label for="password" class="block text-sm font-semibold text-blue-900 mb-2">{{ __("Contraseña") }}</label>
                    <input
                        id="password"
                        type="password"
                        name="password"
                        required
                        autocomplete="new-password"
                        class="w-full px-4 py-3 rounded-lg border border-cyan-300 focus:border-cyan-500 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 transition-colors"
                        placeholder="••••••••"
                    />
                    <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-600 text-sm" />
                </div>

                <!-- Confirm Password -->
                <div class="mb-6">
                    <label for="password_confirmation" class="block text-sm font-semibold text-blue-900 mb-2">{{ __("Confirmar Contraseña") }}</label>
                    <input
                        id="password_confirmation"
                        type="password"
                        name="password_confirmation"
                        required
                        autocomplete="new-password"
                        class="w-full px-4 py-3 rounded-lg border border-cyan-300 focus:border-cyan-500 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 transition-colors"
                        placeholder="••••••••"
                    />
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-red-600 text-sm" />
                </div>

                <div class="flex flex-col sm:flex-row items-center justify-between gap-4 mb-6">
                    <button type="submit" class="w-full sm:w-auto bg-gradient-to-r from-blue-900 to-cyan-400 text-white px-8 py-3 rounded-full font-bold shadow-lg hover:from-cyan-600 hover:to-blue-800 transition-all duration-200">
                        {{ __("Registrarse") }}
                    </button>
                </div>

                <div class="text-center">
                    <p class="text-sm text-gray-600">
                        ¿Ya tienes cuenta? 
                        <a href="{{ route('login') }}" class="text-cyan-600 hover:text-cyan-800 font-semibold underline">
                            Inicia sesión aquí
                        </a>
                    </p>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
