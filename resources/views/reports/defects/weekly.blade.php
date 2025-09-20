<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Reporte Semanal de Defectos</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Filtros -->
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
                <input type="date" name="date_to" value="{{ request('date_to') }}" class="border rounded p-2" />

                <button class="px-3 py-2 bg-gray-800 text-white rounded">Filtrar</button>
                <a href="{{ route('reports.defects.weekly') }}" class="px-3 py-2 border rounded">Limpiar</a>
            </form>

            <!-- Tabla de defectos resumida -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-4 overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Producto</th>
                                <th class="px-6 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Código</th>
                                <th class="px-6 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Tipo</th>
                                <th class="px-6 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Ubicación</th>
                                <th class="px-6 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Cantidad</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($defects as $d)
                            <tr>
                                <td class="px-6 py-2">{{ $d->product->name }}</td>
                                <td class="px-6 py-2">{{ $d->product->barcode }}</td>
                                <td class="px-6 py-2">{{ $d->type->name }}</td>
                                <td class="px-6 py-2">{{ $d->location->name ?? $d->location_text ?? '—' }}</td>
                                <td class="px-6 py-2">{{ $d->total_defects }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="mt-4">
                        {{ $defects->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
