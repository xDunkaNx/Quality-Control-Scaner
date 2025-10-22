<x-app-layout>
    <x-slot name="header">
        <div class="space-y-2">
            <span class="app-badge">Operaciones</span>
            <div>
                <h2 class="text-2xl font-semibold text-white">Defectos registrados</h2>
                <p class="text-sm text-slate-400">Consulta y gestiona los hallazgos detectados en piso o almacén.</p>
            </div>
        </div>
    </x-slot>

    @php
        $statusLabels = [
            'open' => ['label' => 'Abierto', 'color' => 'bg-rose-500/20 text-rose-200 border border-rose-500/40'],
            'review' => ['label' => 'Revisión', 'color' => 'bg-amber-400/15 text-amber-200 border border-amber-400/40'],
            'closed' => ['label' => 'Cerrado', 'color' => 'bg-emerald-500/20 text-emerald-200 border border-emerald-400/40'],
            'scrapped' => ['label' => 'Desechado', 'color' => 'bg-slate-500/20 text-slate-200 border border-slate-500/40'],
        ];
    @endphp

    <section class="app-page space-y-6 py-8">
        <form method="GET" class="app-card p-6 sm:p-8">
            <div class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
                <div class="space-y-2">
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Filtra tus registros</p>
                    <h3 class="text-lg font-semibold text-white">Encuentra defectos por estado, ubicación o fechas</h3>
                </div>

                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('reports.defects.exportCsv', request()->only(['status','type_id','loc_id','date_from','date_to'])) }}"
                       class="app-cta">
                        Exportar CSV
                    </a>
                    <a href="{{ route('defects.index') }}" class="app-cta-secondary">
                        Limpiar filtros
                    </a>
                </div>
            </div>

            <div class="mt-6 grid gap-4 md:grid-cols-2 lg:grid-cols-6">
                <div>
                    <label class="app-label">Estado</label>
                    <select name="status" class="app-select">
                        <option value="">Todos</option>
                        @foreach($statusLabels as $value => $data)
                            <option value="{{ $value }}" @selected(request('status')===$value)>
                                {{ $data['label'] }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="app-label">Tipo</label>
                    <select name="type_id" class="app-select">
                        <option value="">Todos</option>
                        @foreach($types as $t)
                            <option value="{{ $t->id }}" @selected(request('type_id')==$t->id)>
                                {{ $t->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="app-label">Ubicación</label>
                    <select name="loc_id" class="app-select">
                        <option value="">Todas</option>
                        @foreach($locs as $l)
                            <option value="{{ $l->id }}" @selected(request('loc_id')==$l->id)>
                                {{ $l->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="app-label" for="date_from">Desde</label>
                    <input type="date" name="date_from" id="date_from" value="{{ request('date_from') }}" class="app-input">
                </div>

                <div>
                    <label class="app-label" for="date_to">Hasta</label>
                    <input type="date" name="date_to" id="date_to" value="{{ request('date_to') }}" class="app-input">
                </div>

                <div class="flex items-end">
                    <button class="app-cta w-full justify-center">
                        Buscar
                    </button>
                </div>
            </div>
        </form>

        @if(session('ok'))
            <div class="app-alert-success">
                {{ session('ok') }}
            </div>
        @endif

        
        <div class="app-data-card space-y-6">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Listado</p>
                    <h3 class="text-xl font-semibold text-white">Defectos registrados</h3>
                    <p class="text-xs text-slate-500">Los registros se muestran de más reciente a más antiguo.</p>
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
                                <th>Fecha</th>
                                <th>Producto</th>
                                <th>Tipo</th>
                                <th>Ubicación</th>
                                <th>Lote</th>
                                <th>Unidad</th>
                                <th>Estado</th>
                                <th>Foto</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($defects as $d)
                                <tr>
                                    <td>
                                        <span class="font-medium text-slate-100">
                                            {{ $d->found_at?->format('Y-m-d') }}
                                        </span>
                                        <span class="block text-xs text-slate-500">
                                            {{ $d->found_at?->format('H:i') }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="text-slate-100">{{ $d->product->name }}</div>
                                        <span class="text-xs text-slate-500 font-mono">{{ $d->product->barcode }}</span>
                                    </td>
                                    <td>{{ $d->type->name }}</td>
                                    <td>{{ $d->location->name ?? $d->location_text ?? '—' }}</td>
                                    <td>{{ $d->lot ?? '—' }}</td>
                                    <td>{{ $d->unit ?? '—' }}</td>
                                    <td>
                                        @can('manage defects')
                                            <form method="POST" action="{{ route('defects.updateStatus',$d) }}" class="inline-flex">
                                                @csrf
                                                @method('PATCH')
                                                <select name="status" class="app-select py-2 text-xs" onchange="this.form.submit()">
                                                    @foreach($statusLabels as $value => $data)
                                                        <option value="{{ $value }}" @selected($d->status===$value)>
                                                            {{ $data['label'] }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </form>
                                        @else
                                            <span class="app-pill {{ $statusLabels[$d->status]['color'] ?? '' }}">
                                                {{ $statusLabels[$d->status]['label'] ?? $d->status }}
                                            </span>
                                        @endcan
                                    </td>
                                    <td>
                                        @if($d->photo_path)
                                            <a href="{{ asset('storage/'.$d->photo_path) }}" target="_blank" class="text-emerald-300 underline">
                                                Ver
                                            </a>
                                        @else
                                            <span class="text-slate-500">—</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
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
