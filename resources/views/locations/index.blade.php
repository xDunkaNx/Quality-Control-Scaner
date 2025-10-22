<x-app-layout>
    <x-slot name="header">
        <div class="space-y-2">
            <span class="app-badge">Catálogo</span>
            <div>
                <h2 class="text-2xl font-semibold text-white">Ubicaciones</h2>
                <p class="text-sm text-slate-400">Organiza sucursales, almacenes y zonas para ubicar cada merma sin ambigüedades.</p>
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
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Buscar</p>
                    <h3 class="text-lg font-semibold text-white">Filtra por código, nombre o jerarquía</h3>
                </div>

                <a
                    href="{{ route('locations.create') }}"
                    class="app-cta"
                >
                    Nueva ubicación
                </a>
            </div>

            <form method="GET" class="mt-6 flex flex-col gap-3 sm:flex-row sm:items-center">
                <div class="flex flex-1 items-center gap-3">
                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Ej: Almacén A, Gondola-07, SUC_TRUJILLO..."
                        class="app-input"
                    />
                    <button class="app-cta-secondary">
                        Buscar
                    </button>
                </div>
                @if (request('search'))
                    <a href="{{ route('locations.index') }}" class="app-cta-secondary">
                        Limpiar
                    </a>
                @endif
            </form>
        </div>

        <div class="app-data-card space-y-6">
            <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Resultados</p>
                    <h3 class="text-lg font-semibold text-white">Listado de ubicaciones activas</h3>
                </div>
                <span class="app-pill">{{ number_format($locations->total()) }} ubicaciones</span>
            </div>

            <div class="app-table-wrapper">
                <div class="overflow-x-auto">
                    <table class="app-table">
                        <thead>
                            <tr>
                                <th>Código</th>
                                <th>Nombre</th>
                                <th>Superior</th>
                                <th>Coordenadas</th>
                                <th>Actualizado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($locations as $location)
                                <tr>
                                    <td class="font-mono text-xs">{{ $location->code }}</td>
                                    <td>{{ $location->name }}</td>
                                    <td>
                                        @if ($location->parent_code)
                                            <span class="app-pill">{{ $location->parent_code }}</span>
                                        @else
                                            <span class="text-slate-500">Raíz</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($location->latitude && $location->longitude)
                                            <span class="font-mono text-xs text-slate-300">
                                                {{ number_format($location->latitude, 6) }},
                                                {{ number_format($location->longitude, 6) }}
                                            </span>
                                        @else
                                            <span class="text-slate-500">—</span>
                                        @endif
                                    </td>
                                    <td>{{ $location->updated_at?->format('Y-m-d') ?? '—' }}</td>
                                    <td class="space-x-3">
                                        <a
                                            href="{{ route('locations.edit', $location) }}"
                                            class="text-emerald-300 hover:underline"
                                        >
                                            Editar
                                        </a>
                                        <form
                                            action="{{ route('locations.destroy', $location) }}"
                                            method="POST"
                                            class="inline"
                                            onsubmit="return confirm('¿Eliminar esta ubicación?');"
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
                                    <td colspan="6" class="py-6 text-center text-slate-500">
                                        No hay ubicaciones registradas.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                <p class="text-xs text-slate-500">
                    Página {{ $locations->currentPage() }} de {{ $locations->lastPage() }}
                </p>
                <div class="self-end">
                    {{ $locations->links() }}
                </div>
            </div>
        </div>
    </section>
</x-app-layout>
