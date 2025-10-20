<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Usuarios
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
                        placeholder="Buscar nombre o correo"
                        class="border rounded px-3 py-2 w-64"
                    />
                    <button class="px-4 py-2 bg-gray-800 text-white rounded">
                        Buscar
                    </button>
                    @if (request('search'))
                        <a href="{{ route('users.index') }}" class="px-4 py-2 border rounded">
                            Limpiar
                        </a>
                    @endif
                </form>

                <a
                    href="{{ route('users.create') }}"
                    class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 transition"
                >
                    Nuevo usuario
                </a>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-4">
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead>
                                <tr class="text-left border-b">
                                    <th class="py-2 pr-4">Nombre</th>
                                    <th class="py-2 pr-4">Correo</th>
                                    <th class="py-2 pr-4">Roles</th>
                                    <th class="py-2 pr-4">Creado</th>
                                    <th class="py-2 pr-4">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($users as $user)
                                    <tr class="border-b">
                                        <td class="py-2 pr-4">{{ $user->name }}</td>
                                        <td class="py-2 pr-4">{{ $user->email }}</td>
                                        <td class="py-2 pr-4">
                                            @if ($user->roles->isEmpty())
                                                <span class="text-gray-400">Sin rol</span>
                                            @else
                                                {{ $user->roles->pluck('name')->implode(', ') }}
                                            @endif
                                        </td>
                                        <td class="py-2 pr-4">
                                            {{ $user->created_at?->format('Y-m-d') ?? '—' }}
                                        </td>
                                        <td class="py-2 pr-4 space-x-2">
                                            <a
                                                href="{{ route('users.edit', $user) }}"
                                                class="text-indigo-600 hover:underline"
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
                                                <button type="submit" class="text-red-600 hover:underline">
                                                    Eliminar
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="py-4 text-center text-gray-500">
                                            No hay usuarios registrados.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $users->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
