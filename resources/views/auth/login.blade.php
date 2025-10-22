<x-guest-layout>
    <section class="space-y-8">
        <div class="space-y-4 text-center">
            <a href="{{ url('/') }}" class="group mx-auto flex max-w-xs items-center justify-center gap-3 rounded-3xl border border-slate-800 bg-slate-900/60 px-5 py-3 transition hover:border-slate-600 hover:bg-slate-900/80">
                <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-gradient-to-br from-emerald-400 to-cyan-400 text-lg font-semibold text-slate-950 shadow-lg shadow-emerald-500/40 transition group-hover:scale-105">
                    ZM
                </span>
                <div class="text-left">
                    <p class="text-sm font-semibold uppercase tracking-wide text-emerald-200">Zero Merma</p>
                    <p class="text-xs text-slate-400">Control integral de mermas</p>
                </div>
            </a>

            <div class="space-y-2">
                <h1 class="text-3xl font-semibold text-white">Bienvenido de nuevo</h1>
                <p class="text-sm text-slate-400">
                    Ingresa tus credenciales para acceder al panel de control y revisar los defectos registrados.
                </p>
            </div>
        </div>

        <x-auth-session-status class="app-alert-success text-sm" :status="session('status')" />

        <div class="app-card p-6 sm:p-8">
            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf

                <div>
                    <label class="app-label" for="email">Correo electrónico</label>
                    <input
                        id="email"
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        autofocus
                        autocomplete="username"
                        class="app-input"
                        placeholder="usuario@empresa.com"
                    >
                    <x-input-error :messages="$errors->get('email')" class="mt-2 text-xs text-rose-300" />
                </div>

                <div>
                    <label class="app-label" for="password">Contraseña</label>
                    <input
                        id="password"
                        type="password"
                        name="password"
                        required
                        autocomplete="current-password"
                        class="app-input"
                        placeholder="••••••••"
                    >
                    <x-input-error :messages="$errors->get('password')" class="mt-2 text-xs text-rose-300" />
                </div>

                <div class="flex items-center justify-between gap-3 text-xs text-slate-400">
                    <label for="remember_me" class="flex items-center gap-2">
                        <input
                            id="remember_me"
                            type="checkbox"
                            name="remember"
                            class="h-4 w-4 rounded border-slate-700 bg-slate-900 text-emerald-400 focus:ring-emerald-500 focus:ring-offset-0"
                        >
                        <span class="select-none">Recordarme</span>
                    </label>

                    {{-- @if (Route::has('password.request'))
                        <a
                            href="{{ route('password.request') }}"
                            class="font-medium text-emerald-200 hover:text-emerald-100 hover:underline"
                        >
                            ¿Olvidaste tu contraseña?
                        </a>
                    @endif --}}
                </div>

                <button type="submit" class="app-cta w-full justify-center">
                    Iniciar sesión
                </button>
            </form>
        </div>
    </section>
</x-guest-layout>
