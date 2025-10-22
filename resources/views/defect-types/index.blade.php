<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Tipos de defecto
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
                        <a href="{{ route('defect-types.index') }}" class="px-4 py-2 border rounded">
                            Limpiar
                        </a>
                    @endif
                </form>

                <a
                    href="{{ route('defect-types.create') }}"
                    class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 transition"
                >
                    Nuevo tipo
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
                                    <th class="py-2 pr-4">Requiere foto</th>
                                    <th class="py-2 pr-4">Actualizado</th>
                                    <th class="py-2 pr-4">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($types as $type)
                                    <tr class="border-b">
                                        <td class="py-2 pr-4 font-mono">{{ $type->code }}</td>
                                        <td class="py-2 pr-4">{{ $type->name }}</td>
                                        <td class="py-2 pr-4">
                                            <span class="inline-flex items-center px-2 py-1 rounded text-xs {{ $type->requires_photo ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-600' }}">
                                                {{ $type->requires_photo ? 'Sí' : 'No' }}
                                            </span>
                                        </td>
                                        <td class="py-2 pr-4">
                                            {{ $type->updated_at?->format('Y-m-d') ?? '—' }}
                                        </td>
                                        <td class="py-2 pr-4 space-x-2">
                                            <a
                                                href="{{ route('defect-types.edit', $type) }}"
                                                class="text-indigo-600 hover:underline"
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
                                                <button type="submit" class="text-red-600 hover:underline">
                                                    Eliminar
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="py-4 text-center text-gray-500">
                                            No hay tipos de defecto registrados.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $types->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

