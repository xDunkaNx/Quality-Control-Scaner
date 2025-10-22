<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Zero Merma') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="app-shell">
            <nav class="sticky top-0 z-40 border-b border-slate-800 bg-slate-950/80 backdrop-blur relative">
                @php
                    $navItems = [
                        [
                            'label' => 'Dashboard',
                            'href' => route('dashboard'),
                            'active' => request()->routeIs('dashboard'),
                        ],
                        [
                            'label' => 'Defectos',
                            'href' => route('defects.index'),
                            'active' => request()->routeIs('defects.index'),
                        ],
                        [
                            'label' => 'Escáner',
                            'href' => route('defects.scan'),
                            'active' => request()->routeIs('defects.scan'),
                        ],
                        [
                            'label' => 'Reportes',
                            'href' => route('reports.defects.weekly'),
                            'active' => request()->routeIs('reports.defects.*'),
                            'can' => 'view reports',
                        ],
                    ];

                    if (auth()->user()?->can('manage users')) {
                        $navItems[] = [
                            'label' => 'Usuarios',
                            'href' => route('users.index'),
                            'active' => request()->routeIs('users.*'),
                        ];
                    }

                    if (auth()->user()?->can('manage catalogs')) {
                        $navItems[] = [
                            'label' => 'Tipos de defecto',
                            'href' => route('defect-types.index'),
                            'active' => request()->routeIs('defect-types.*'),
                        ];
                        $navItems[] = [
                            'label' => 'Ubicaciones',
                            'href' => route('locations.index'),
                            'active' => request()->routeIs('locations.*'),
                        ];
                    }

                    $initials = strtoupper(mb_substr(auth()->user()->name ?? '', 0, 1));
                @endphp

                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex h-16 items-center justify-between">
                        <div class="flex items-center gap-6">
                            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 text-slate-200 hover:text-white">
                                <span class="flex h-10 w-10 items-center justify-center rounded-2xl bg-gradient-to-br from-emerald-400 to-cyan-400 text-base font-semibold text-slate-950 shadow-lg shadow-emerald-500/40">
                                    ZM
                                </span>
                                <div class="hidden sm:block">
                                    <p class="text-sm font-semibold tracking-wide text-emerald-200">Zero Merma</p>
                                    <p class="text-xs text-slate-400">Control integral de mermas</p>
                                </div>
                            </a>

                            <div class="hidden md:flex items-center gap-1">
                                @foreach ($navItems as $item)
                                    @if (!isset($item['can']) || auth()->user()?->can($item['can']))
                                        <a href="{{ $item['href'] }}"
                                            class="{{ $item['active'] ? 'bg-slate-800/70 text-white shadow-inner shadow-emerald-500/20' : 'text-slate-300 hover:text-white hover:bg-slate-800/50' }} rounded-xl px-4 py-2 text-sm font-medium transition">
                                            {{ $item['label'] }}
                                        </a>
                                    @endif
                                @endforeach
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            <div class="hidden md:flex items-center gap-3 rounded-full border border-slate-800/80 bg-slate-900/80 px-3 py-1.5">
                                <span class="flex h-10 w-10 items-center justify-center rounded-full bg-slate-800 text-sm font-semibold text-emerald-200">
                                    {{ $initials }}
                                </span>
                                <div class="text-right">
                                    <p class="text-xs font-semibold text-slate-200 leading-tight">{{ auth()->user()->name }}</p>
                                    <p class="text-[11px] text-slate-400 leading-tight">{{ auth()->user()->email }}</p>
                                </div>
                                <button type="button" class="rounded-full border border-slate-700/60 bg-slate-900/60 p-2 text-slate-300 hover:text-white focus:outline-none focus:ring-2 focus:ring-emerald-400/50" data-toggle="user-menu" aria-haspopup="true" aria-expanded="false">
                                    <svg class="h-4 w-4" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M6 8L10 12L14 8" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </button>
                            </div>

                            <button type="button" class="md:hidden inline-flex items-center justify-center rounded-xl border border-slate-700 bg-slate-900/80 p-2 text-slate-200 hover:text-white focus:outline-none focus:ring-2 focus:ring-emerald-400/50" data-toggle="mobile-menu" aria-controls="mobile-menu" aria-expanded="false">
                                <span class="sr-only">Abrir menú</span>
                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <div id="mobile-menu" class="md:hidden hidden border-t border-slate-800/80 bg-slate-950/95">
                    <div class="px-4 py-4 space-y-2">
                        <div class="flex items-center gap-3 rounded-2xl border border-slate-800 bg-slate-900/80 px-4 py-3">
                            <span class="flex h-10 w-10 items-center justify-center rounded-full bg-slate-800 text-sm font-semibold text-emerald-200">
                                {{ $initials }}
                            </span>
                            <div>
                                <p class="text-sm font-semibold text-slate-200">{{ auth()->user()->name }}</p>
                                <p class="text-xs text-slate-400">{{ auth()->user()->email }}</p>
                            </div>
                        </div>

                        @foreach ($navItems as $item)
                            @if (!isset($item['can']) || auth()->user()?->can($item['can']))
                                <a href="{{ $item['href'] }}"
                                   class="{{ $item['active'] ? 'bg-slate-800/70 text-white' : 'text-slate-300 hover:bg-slate-800/60 hover:text-white' }} block rounded-xl px-4 py-3 text-sm font-medium transition">
                                    {{ $item['label'] }}
                                </a>
                            @endif
                        @endforeach

                        <div class="flex gap-2">
                            <a href="{{ route('profile.edit') }}" class="app-cta-secondary flex-1 text-center text-xs uppercase tracking-wide">
                                Perfil
                            </a>
                            <form method="POST" action="{{ route('logout') }}" class="flex-1">
                                @csrf
                                <button type="submit" class="app-cta flex w-full items-center justify-center text-xs uppercase tracking-wide">
                                    Cerrar sesión
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <div id="user-menu" class="hidden absolute right-6 top-20 w-56 rounded-2xl border border-slate-800 bg-slate-950/95 p-3 shadow-2xl shadow-slate-950/50">
                    <a href="{{ route('profile.edit') }}" class="app-cta-secondary mb-2 w-full justify-start text-xs uppercase tracking-wider">
                        Perfil
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="app-cta w-full justify-start text-xs uppercase tracking-wider">
                            Cerrar sesión
                        </button>
                    </form>
                </div>
            </nav>

            @isset($header)
                <header class="border-b border-slate-800/60 bg-slate-900/40">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                            {{ $header }}
                        </div>
                    </div>
                </header>
            @endisset

            <main class="py-10">
                {{ $slot }}
            </main>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const mobileToggle = document.querySelector('[data-toggle="mobile-menu"]');
                const mobileMenu = document.getElementById('mobile-menu');
                const userToggle = document.querySelector('[data-toggle="user-menu"]');
                const userMenu = document.getElementById('user-menu');

                if (mobileToggle && mobileMenu) {
                    mobileToggle.addEventListener('click', (event) => {
                        event.stopPropagation();
                        const isHidden = mobileMenu.classList.contains('hidden');
                        mobileMenu.classList.toggle('hidden', !isHidden);
                        mobileToggle.setAttribute('aria-expanded', String(isHidden));
                    });
                }

                if (userToggle && userMenu) {
                    userToggle.addEventListener('click', (event) => {
                        event.stopPropagation();
                        const isHidden = userMenu.classList.contains('hidden');
                        userMenu.classList.toggle('hidden', !isHidden);
                        userToggle.setAttribute('aria-expanded', String(isHidden));
                    });
                }

                document.addEventListener('click', (event) => {
                    if (mobileMenu && !mobileMenu.classList.contains('hidden')) {
                        if (!mobileMenu.contains(event.target) && event.target !== mobileToggle) {
                            mobileMenu.classList.add('hidden');
                            mobileToggle?.setAttribute('aria-expanded', 'false');
                        }
                    }

                    if (userMenu && !userMenu.classList.contains('hidden')) {
                        if (!userMenu.contains(event.target) && event.target !== userToggle) {
                            userMenu.classList.add('hidden');
                            userToggle?.setAttribute('aria-expanded', 'false');
                        }
                    }
                });
            });
        </script>
    </body>
</html>
