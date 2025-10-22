<x-app-layout>
    <x-slot name="header">
        <div class="space-y-2">
            <span class="app-badge">Catálogo</span>
            <div>
                <h2 class="text-2xl font-semibold text-white">Tipos de defecto</h2>
                <p class="text-sm text-slate-400">Administra las categorías utilizadas durante el registro de mermas.</p>
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
                    <h3 class="text-lg font-semibold text-white">Filtra por código o nombre</h3>
                </div>

                <a
                    href="{{ route('defect-types.create') }}"
                    class="app-cta"
                >
                    Nuevo tipo
                </a>
            </div>

            <form method="GET" class="mt-6 flex flex-col gap-3 sm:flex-row sm:items-center">
                <div class="flex flex-1 items-center gap-3">
                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Ej: etiqueta, empaque, caducado..."
                        class="app-input"
                    />
                    <button class="app-cta-secondary">
                        Buscar
                    </button>
                </div>
                @if (request('search'))
                    <a href="{{ route('defect-types.index') }}" class="app-cta-secondary">
                        Limpiar
                    </a>
                @endif
            </form>
        </div>

        <div class="app-data-card space-y-6">
            <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Resultados</p>
                    <h3 class="text-lg font-semibold text-white">Listado de tipos disponibles</h3>
                </div>
                <span class="app-pill">{{ number_format($types->total()) }} tipos</span>
            </div>

            <div class="app-table-wrapper">
                <div class="overflow-x-auto">
                    <table class="app-table">
                        <thead>
                            <tr>
                                <th>Código</th>
                                <th>Nombre</th>
                                <th>Requiere foto</th>
                                <th>Actualizado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($types as $type)
                                <tr>
                                    <td class="font-mono text-xs">{{ $type->code }}</td>
                                    <td>{{ $type->name }}</td>
                                    <td>
                                        <span class="app-pill {{ $type->requires_photo ? 'border-emerald-400/40 bg-emerald-500/10 text-emerald-100' : 'border-slate-700/60 bg-slate-900/60 text-slate-300' }}">
                                            {{ $type->requires_photo ? 'Sí' : 'No' }}
                                        </span>
                                    </td>
                                    <td>{{ $type->updated_at?->format('Y-m-d') ?? '—' }}</td>
                                    <td class="space-x-3">
                                        <a
                                            href="{{ route('defect-types.edit', $type) }}"
                                            class="text-emerald-300 hover:underline"
                                        >
                                            Editar
                                        </a>
                                        <form
                                            action="{{ route('defect-types.destroy', $type) }}"
                                            method="POST"
                                            class="inline"
                                            onsubmit="return confirm('¿Eliminar este tipo de defecto?');"
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
                                        No hay tipos de defecto registrados.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                <p class="text-xs text-slate-500">
                    Página {{ $types->currentPage() }} de {{ $types->lastPage() }}
                </p>
                <div class="self-end">
                    {{ $types->links() }}
                </div>
            </div>
        </div>
    </section>
</x-app-layout>
