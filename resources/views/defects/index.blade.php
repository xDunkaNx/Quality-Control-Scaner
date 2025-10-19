<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Defectos
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
           <form method="GET" class="mb-4 flex flex-wrap gap-2">
                <select name="status" class="border rounded p-2">
                    <option value="">Estado</option>
                    @foreach(['open'=>'Abierto','review'=>'Revisión','closed'=>'Cerrado','scrapped'=>'Desechado'] as $k=>$v)
                    <option value="{{ $k }}" @selected(request('status')===$k)>{{ $v }}</option>
                    @endforeach
                </select>

                <select name="type_id" class="border rounded p-2">
                    <option value="">Tipo</option>
                    @foreach($types as $t)
                    <option value="{{ $t->id }}" @selected(request('type_id')==$t->id)>{{ $t->name }}</option>
                    @endforeach
                </select>

                <select name="loc_id" class="border rounded p-2">
                    <option value="">Ubicación</option>
                    @foreach($locs as $l)
                    <option value="{{ $l->id }}" @selected(request('loc_id')==$l->id)>{{ $l->name }}</option>
                    @endforeach
                </select>

                <input type="date" name="date_from" value="{{ request('date_from') }}" class="border rounded p-2" />
                <input type="date" name="date_to"   value="{{ request('date_to') }}"   class="border rounded p-2" />

                <button class="px-3 py-2 bg-gray-800 text-white rounded">Filtrar</button>
                <a href="{{ route('defects.index') }}" class="px-3 py-2 border rounded">Limpiar</a>
                <a href="{{ route('reports.defects.exportCsv', request()->only(['status','type_id','loc_id','date_from','date_to'])) }}"
                    class="px-3 py-2 bg-emerald-600 text-white rounded">
                    Exportar CSV
                </a>
            </form>
            @if(session('ok'))
                <div class="mb-4 rounded-md bg-green-50 p-4 text-green-800">
                    {{ session('ok') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-4">
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead>
                                <tr class="text-left border-b">
                                    <th class="py-2 pr-4">Fecha</th>
                                    <th class="py-2 pr-4">Producto</th>
                                    <th class="py-2 pr-4">Tipo</th>
                                    <th class="py-2 pr-4">Ubicación</th>
                                    <th class="py-2 pr-4">Lote</th>
                                    <th class="py-2 pr-4">Tipo de unidad</th>
                                    {{-- <th class="py-2 pr-4">Sev</th> --}}
                                    <th class="py-2 pr-4">Estado</th>
                                    <th>Foto</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($defects as $d)
                                    <tr class="border-b">
                                        <td class="py-2 pr-4">{{ $d->found_at?->format('Y-m-d H:i') }}</td>
                                        <td class="py-2 pr-4">{{ $d->product->name }} <span class="text-gray-500">({{ $d->product->barcode }})</span></td>
                                        <td class="py-2 pr-4">{{ $d->type->name }}</td>
                                        <td class="py-2 pr-4">{{ $d->location->name ?? $d->location_text ?? '—' }}</td>
                                        <td class="py-2 pr-4">{{ $d->lot ?? '—' }}</td>
                                        <td class="py-2 pr-4">{{ $d->unit ?? '—' }}</td>
                                        {{-- <td class="py-2 pr-4">{{ $d->severity }}</td> --}}
                                        <td>
                                        @can('manage defects')
                                            <form method="POST" action="{{ route('defects.updateStatus',$d) }}">
                                            @csrf @method('PATCH')
                                            <select name="status" class="border rounded p-1 text-sm" onchange="this.form.submit()">
                                                @foreach(['open'=>'Abierto','review'=>'Revisión','closed'=>'Cerrado','scrapped'=>'Desechado'] as $k=>$label)
                                                <option value="{{ $k }}" @selected($d->status===$k)>{{ $label }}</option>
                                                @endforeach
                                            </select>
                                            </form>
                                        @else
                                            {{ $d->status }}
                                        @endcan
                                        </td>
                                        <td>
                                            @if($d->photo_path)
                                                <a href="{{ asset('storage/'.$d->photo_path) }}" target="_blank" class="text-indigo-600 underline">Foto</a>
                                            @else
                                                <span class="text-gray-400">—</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $defects->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
