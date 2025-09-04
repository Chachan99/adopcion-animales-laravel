<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="alternate icon" href="{{ asset('favicon.ico') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Styles -->
    <style>
        :root {
            --primary-dark: #34495e;
            --primary-light: #ecf0f1;
            --accent: #3498db;
            --background: #f0f4f8;
            --text: #2c3e50;
        }
        
        html {
            height: 100%;
        }
        
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            font-family: 'Figtree', sans-serif;
            background-color: var(--background);
            color: var(--text);
            margin: 0;
        }
        
        .navbar {
            background-color: var(--primary-dark);
            padding: 1rem;
        }
        
        .navbar a {
            color: var(--primary-light);
            text-decoration: none;
            margin: 0 15px;
            transition: color 0.3s ease;
        }
        
        .navbar a:hover {
            color: var(--accent);
        }
        
        main {
            flex: 1;
            padding: 2rem 0;
        }
        
        .footer {
            background-color: var(--primary-dark);
            color: var(--primary-light);
            text-align: center;
            padding: 1.5rem;
            margin-top: auto;
        }
        
        /* Responsive improvements - Solo para celulares */
        @media (max-width: 640px) {
            body {
                overflow-x: hidden;
                font-size: 14px;
                line-height: 1.5;
            }
            
            .container {
                padding-left: 0.75rem;
                padding-right: 0.75rem;
            }
            
            main {
                padding-top: 0.75rem;
                padding-bottom: 0.75rem;
            }
        }
    </style>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    @include('layouts.navigation')

    <!-- Page Content -->
    <main class="container mx-auto px-4 sm:px-6 lg:px-8 py-4">
        @yield('content')
    </main>

    <footer class="footer">
        © {{ now()->year }} {{ config('app.name', 'Laravel') }}. Todos los derechos reservados.
    </footer>

    @stack('scripts')
</body>
</html>