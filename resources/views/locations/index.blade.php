<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Ubicaciones
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('ok'))
                <div class="mb-4 rounded-md bg-green-50 p-4 text-green-800">
                    {{ session('ok') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-4 rounded-md bg-red-50 p-4 text-red-800">
                    {{ session('error') }}
                </div>
            @endif

            <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <form method="GET" class="flex gap-2">
                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Buscar por nombre o código"
                        class="border rounded px-3 py-2 w-64"
                    />
                    <button class="px-4 py-2 bg-gray-800 text-white rounded">
                        Buscar
                    </button>
                    @if (request('search'))
                        <a href="{{ route('locations.index') }}" class="px-4 py-2 border rounded">
                            Limpiar
                        </a>
                    @endif
                </form>

                <a
                    href="{{ route('locations.create') }}"
                    class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 transition"
                >
                    Nueva ubicación
                </a>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-4">
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead>
                                <tr class="text-left border-b">
                                    <th class="py-2 pr-4">Código</th>
                                    <th class="py-2 pr-4">Nombre</th>
                                    <th class="py-2 pr-4">Superior</th>
                                    <th class="py-2 pr-4">Coordenadas</th>
                                    <th class="py-2 pr-4">Actualizado</th>
                                    <th class="py-2 pr-4">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($locations as $location)
                                    <tr class="border-b">
                                        <td class="py-2 pr-4 font-mono">{{ $location->code }}</td>
                                        <td class="py-2 pr-4">{{ $location->name }}</td>
                                        <td class="py-2 pr-4">
                                            {{ $location->parent_code ?? '—' }}
                                        </td>
                                        <td class="py-2 pr-4">
                                            @if ($location->latitude && $location->longitude)
                                                <span class="font-mono">
                                                    {{ number_format($location->latitude, 6) }},
                                                    {{ number_format($location->longitude, 6) }}
                                                </span>
                                            @else
                                                <span class="text-gray-400">—</span>
                                            @endif
                                        </td>
                                        <td class="py-2 pr-4">
                                            {{ $location->updated_at?->format('Y-m-d') ?? '—' }}
                                        </td>
                                        <td class="py-2 pr-4 space-x-2">
                                            <a
                                                href="{{ route('locations.edit', $location) }}"
                                                class="text-indigo-600 hover:underline"
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
                                                <button type="submit" class="text-red-600 hover:underline">
                                                    Eliminar
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="py-4 text-center text-gray-500">
                                            No hay ubicaciones registradas.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $locations->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

