<x-app-layout>
    <x-slot name="header">
        <div class="space-y-2">
            <span class="app-badge">Reportes</span>
            <div>
                <h2 class="text-2xl font-semibold text-white">Reporte semanal de defectos</h2>
                <p class="text-sm text-slate-400">Analiza los productos con mayor recurrencia de incidencias durante el periodo seleccionado.</p>
            </div>
        </div>
    </x-slot>

    @php
        $statusOptions = [
            'open' => 'Abierto',
            'review' => 'Revisión',
            'closed' => 'Cerrado',
            'scrapped' => 'Desechado',
        ];
    @endphp

    <section class="app-page py-8 space-y-6">
        <form method="GET" class="app-card p-6 sm:p-8">
            <div class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
                <div class="space-y-2">
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Filtros</p>
                    <h3 class="text-lg font-semibold text-white">Acota la búsqueda por estado, tipo o fechas</h3>
                    <p class="text-xs text-slate-500">El listado agrupa los defectos por producto y muestra el total detectado.</p>
                </div>

                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('reports.defects.weekly') }}" class="app-cta-secondary">
                        Limpiar filtros
                    </a>
                    <button class="app-cta">
                        Aplicar
                    </button>
                </div>
            </div>

            <div class="mt-6 grid gap-4 md:grid-cols-2 lg:grid-cols-6">
                <div>
                    <label class="app-label" for="status">Estado</label>
                    <select name="status" id="status" class="app-select">
                        <option value="">Todos</option>
                        @foreach($statusOptions as $value => $label)
                            <option value="{{ $value }}" @selected(request('status') === $value)>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="app-label" for="type_id">Tipo</label>
                    <select name="type_id" id="type_id" class="app-select">
                        <option value="">Todos</option>
                        @foreach($types as $t)
                            <option value="{{ $t->id }}" @selected(request('type_id') == $t->id)>
                                {{ $t->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="app-label" for="loc_id">Ubicación</label>
                    <select name="loc_id" id="loc_id" class="app-select">
                        <option value="">Todas</option>
                        @foreach($locs as $l)
                            <option value="{{ $l->id }}" @selected(request('loc_id') == $l->id)>
                                {{ $l->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="app-label" for="date_from">Desde</label>
                    <input
                        type="date"
                        name="date_from"
                        id="date_from"
                        value="{{ request('date_from') }}"
                        class="app-input"
                    >
                </div>

                <div>
                    <label class="app-label" for="date_to">Hasta</label>
                    <input
                        type="date"
                        name="date_to"
                        id="date_to"
                        value="{{ request('date_to') }}"
                        class="app-input"
                    >
                </div>
            </div>
        </form>

        <div class="app-data-card space-y-6">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Resultados</p>
                    <h3 class="text-xl font-semibold text-white">Resumen de defectos por producto</h3>
                    <p class="text-xs text-slate-500">Incluye información por tipo, ubicación y cantidad consolidada.</p>
                </div>
                <span class="app-pill">
                    {{ number_format($defects->total()) }} registros
                </span>
            </div>

            <div class="app-table-wrapper">
                <div class="overflow-x-auto">
                    <table class="app-table">
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th>Código</th>
                                <th>Tipo</th>
                                <th>Ubicación</th>
                                <th class="text-right">Cantidad</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($defects as $d)
                                <tr>
                                    <td class="text-slate-100">
                                        {{ $d->product->name }}
                                    </td>
                                    <td class="font-mono text-xs text-slate-300">
                                        {{ $d->product->barcode }}
                                    </td>
                                    <td>{{ $d->type->name }}</td>
                                    <td>{{ $d->location->name ?? $d->location_text ?? '—' }}</td>
                                    <td class="text-right">
                                        <span class="app-pill border-emerald-400/40 bg-emerald-500/10 text-emerald-100">
                                            {{ number_format($d->total_defects) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-6 text-center text-slate-500">
                                        No se encontraron defectos para los filtros seleccionados.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <p class="text-xs text-slate-500">
                    Página {{ $defects->currentPage() }} de {{ $defects->lastPage() }}
                </p>
                <div class="self-end">
                    {{ $defects->links() }}
                </div>
            </div>
        </div>
    </section>
</x-app-layout>
