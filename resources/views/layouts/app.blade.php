<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="bg-gray-100">
            <!-- Menú de navegación -->
            <div class="bg-gray-800 text-white">
                <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8">
                    <div class="relative flex items-center justify-between h-16">
                        <!-- Logo y nombre de la app -->
                        <div class="absolute inset-y-0 left-0 flex items-center sm:hidden">
                            <!-- Menu Mobile -->
                            <button type="button" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white" aria-controls="mobile-menu" aria-expanded="false">
                                <span class="sr-only">Open main menu</span>
                                <svg class="block h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                                </svg>
                            </button>
                        </div>
                        <!-- Logo -->
                        <div class="flex-1 flex items-center justify-center sm:items-stretch sm:justify-start">
                            <div class="flex-shrink-0 text-xl font-bold">
                                Quality Control Scanner
                            </div>
                            <div class="hidden sm:block sm:ml-6">
                                <div class="flex space-x-4">
                                    <a href="{{ route('defects.index') }}" class="text-gray-300 hover:bg-gray-700 hover:text-white px-3 py-2 rounded-md text-sm font-medium">Defectos</a>
                                    <a href="{{ route('defects.scan') }}" class="text-gray-300 hover:bg-gray-700 hover:text-white px-3 py-2 rounded-md text-sm font-medium">Scanear</a>
                                    <a href="{{ route('reports.defects.weekly') }}" class="text-gray-300 hover:bg-gray-700 hover:text-white px-3 py-2 rounded-md text-sm font-medium">Reportes</a>

                                    <!-- Menú de usuario -->
                                </div>
                            </div>
                        </div>
                        
                        <div class="relative text-gray-300 hover:bg-gray-700 hover:text-white px-3 py-2 rounded-md text-sm font-medium">
                            <button class="text-white" aria-haspopup="true">
                                {{ Auth::user()->name }}
                            </button>
                            <div class="absolute right-0 w-48 mt-2 origin-top-right bg-white shadow-lg rounded-md hidden" id="user-dropdown">
                                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700">Perfil</a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block px-4 py-2 text-sm text-gray-700 w-full text-left">
                                        Cerrar sesión
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Menú móvil -->
            <div class="sm:hidden" id="mobile-menu">
                <div class="px-2 pt-2 pb-3 space-y-1">
                    <a href="{{ route('defects.index') }}" class="text-gray-300 hover:bg-gray-700 hover:text-white block px-3 py-2 rounded-md text-base font-medium">Defectos</a>
                    <a href="{{ route('defects.scan') }}" class="text-gray-300 hover:bg-gray-700 hover:text-white block px-3 py-2 rounded-md text-base font-medium">Scanear</a>
                    {{-- <a href="{{ route('reports.defects.weekly') }}" class="text-gray-300 hover:bg-gray-700 hover:text-white block px-3 py-2 rounded-md text-base font-medium">Reportes</a> --}}

                    <!-- Menú de usuario para móvil -->
                    <a href="{{ route('profile.edit') }}" class="text-gray-300 hover:bg-gray-700 hover:text-white block px-3 py-2 rounded-md text-base font-medium">Perfil</a>
                    <a href="{{ route('logout') }}" class="text-gray-300 hover:bg-gray-700 hover:text-white block px-3 py-2 rounded-md text-base font-medium">Cerrar sesión</a>
                </div>
            </div>
        </div>

        <!-- Page Heading -->
        @isset($header)
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <!-- Page Content -->
        <main>
            {{ $slot }}
        </main>
    </div>


</body>
</html>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const userButton = document.querySelector('[aria-haspopup="true"]');
        const dropdownMenu = document.querySelector('.absolute.right-0.w-48.mt-2.origin-top-right.bg-white.shadow-lg.rounded-md');

        if (userButton && dropdownMenu) {
            userButton.addEventListener('click', function () {
                dropdownMenu.classList.toggle('hidden');
            });
        }

        // Cerrar el menú si se hace clic fuera de él
        document.addEventListener('click', function (event) {
            if (!dropdownMenu.contains(event.target) && !userButton.contains(event.target)) {
                dropdownMenu.classList.add('hidden');
            }
        });
    });
</script>
