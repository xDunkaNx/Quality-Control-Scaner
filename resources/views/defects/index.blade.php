<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Defectos
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
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
                                    <th class="py-2 pr-4">Sev</th>
                                    <th class="py-2 pr-4">Estado</th>
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
                                        <td class="py-2 pr-4">{{ $d->severity }}</td>
                                        <td class="py-2 pr-4">{{ $d->status }}</td>
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
