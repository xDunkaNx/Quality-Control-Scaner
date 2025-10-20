<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Nuevo usuario
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if ($errors->any())
                        <div class="mb-4 rounded-md bg-red-50 p-4 text-red-800">
                            <p class="font-semibold">Revisa los errores:</p>
                            <ul class="mt-2 list-disc list-inside space-y-1 text-sm">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('users.store') }}" class="space-y-4">
                        @csrf

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nombre</label>
                            <input
                                type="text"
                                name="name"
                                value="{{ old('name') }}"
                                class="mt-1 block w-full border rounded px-3 py-2"
                                required
                            />
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Correo</label>
                            <input
                                type="email"
                                name="email"
                                value="{{ old('email') }}"
                                class="mt-1 block w-full border rounded px-3 py-2"
                                required
                            />
                        </div>

                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Contraseña</label>
                                <input
                                    type="password"
                                    name="password"
                                    class="mt-1 block w-full border rounded px-3 py-2"
                                    required
                                />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Confirmar contraseña</label>
                                <input
                                    type="password"
                                    name="password_confirmation"
                                    class="mt-1 block w-full border rounded px-3 py-2"
                                    required
                                />
                            </div>
                        </div>

                        <div>
                            <span class="block text-sm font-medium text-gray-700 mb-2">Roles</span>
                            <div class="space-y-2">
                                @forelse ($roles as $role)
                                    <label class="flex items-center gap-2">
                                        <input
                                            type="checkbox"
                                            name="roles[]"
                                            value="{{ $role->name }}"
                                            @checked(in_array($role->name, old('roles', [])))
                                        />
                                        <span>{{ $role->name }}</span>
                                    </label>
                                @empty
                                    <p class="text-sm text-gray-500">
                                        No hay roles configurados.
                                    </p>
                                @endforelse
                            </div>
                        </div>

                        <div class="flex gap-3 justify-end">
                            <a href="{{ route('users.index') }}" class="px-4 py-2 border rounded">
                                Cancelar
                            </a>
                            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 transition">
                                Guardar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
