<x-app-layout>
    <x-slot name="header">
        <div class="space-y-2">
            <span class="app-badge">Administración</span>
            <div>
                <h2 class="text-2xl font-semibold text-white">Usuarios</h2>
                <p class="text-sm text-slate-400">Gestiona cuentas y privilegios para el equipo de operaciones.</p>
            </div>
        </div>
    </x-slot>

    <section class="app-page py-8 space-y-6">
        @if (session('ok'))
            <div class="app-alert-success">
                {{ session('ok') }}
            </div>
        @endif

        @if (session('error'))
            <div class="app-alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <div class="app-card p-6 sm:p-8">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Gestión</p>
                    <h3 class="text-xl font-semibold text-white">Usuarios de la plataforma</h3>
                    <p class="text-xs text-slate-500">Controla el acceso asignando roles y permisos.</p>
                </div>

                <a
                    href="{{ route('users.create') }}"
                    class="app-cta"
                >
                    Nuevo usuario
                </a>
            </div>

            <form method="GET" class="mt-6 flex flex-col gap-3 sm:flex-row sm:items-center">
                <div class="flex flex-1 items-center gap-3">
                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Buscar nombre o correo"
                        class="app-input"
                    />
                    <button class="app-cta-secondary">
                        Buscar
                    </button>
                </div>
                @if (request('search'))
                    <a href="{{ route('users.index') }}" class="app-cta-secondary">
                        Limpiar
                    </a>
                @endif
            </form>
        </div>

        <div class="app-data-card space-y-6">
            <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Resultados</p>
                    <h3 class="text-lg font-semibold text-white">Listado de usuarios activos</h3>
                </div>
                <span class="app-pill">{{ number_format($users->total()) }} usuarios</span>
            </div>

            <div class="app-table-wrapper">
                <div class="overflow-x-auto">
                    <table class="app-table">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Correo</th>
                                <th>Roles</th>
                                <th>Creado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($users as $user)
                                <tr>
                                    <td>{{ $user->name }}</td>
                                    <td class="font-mono text-xs text-slate-300">{{ $user->email }}</td>
                                    <td>
                                        @if ($user->roles->isEmpty())
                                            <span class="text-slate-500">Sin rol</span>
                                        @else
                                            <span class="app-pill">{{ $user->roles->pluck('name')->implode(', ') }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $user->created_at?->format('Y-m-d') ?? '—' }}</td>
                                    <td class="space-x-3">
                                        <a
                                            href="{{ route('users.edit', $user) }}"
                                            class="text-emerald-300 hover:underline"
                                        >
                                            Editar
                                        </a>
                                        <form
                                            action="{{ route('users.destroy', $user) }}"
                                            method="POST"
                                            class="inline"
                                            onsubmit="return confirm('¿Eliminar este usuario?');"
                                        >
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-rose-300 hover:underline">
                                                Eliminar
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-6 text-center text-slate-500">
                                        No hay usuarios registrados.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                <p class="text-xs text-slate-500">
                    Página {{ $users->currentPage() }} de {{ $users->lastPage() }}
                </p>
                <div class="self-end">
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </section>
</x-app-layout>
