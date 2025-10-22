<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Nueva ubicación
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

                    <form method="POST" action="{{ route('locations.store') }}" class="space-y-4">
                        @csrf

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Código</label>
                            <input
                                type="text"
                                name="code"
                                value="{{ old('code') }}"
                                class="mt-1 block w-full border rounded px-3 py-2 font-mono"
                                placeholder="EJ: SUC_TRUJILLO"
                                required
                            />
                        </div>

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
                            <label class="block text-sm font-medium text-gray-700 mb-1">Ubicación superior</label>
                            <select
                                name="parent_code"
                                class="block w-full border rounded px-3 py-2"
                            >
                                <option value="">Sin jerarquía</option>
                                @foreach ($parents as $parent)
                                    <option
                                        value="{{ $parent->code }}"
                                        @selected(old('parent_code') === $parent->code)
                                    >
                                        {{ $parent->name }} ({{ $parent->code }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Latitud</label>
                                <input
                                    type="number"
                                    name="latitude"
                                    value="{{ old('latitude') }}"
                                    step="0.000001"
                                    class="mt-1 block w-full border rounded px-3 py-2"
                                />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Longitud</label>
                                <input
                                    type="number"
                                    name="longitude"
                                    value="{{ old('longitude') }}"
                                    step="0.000001"
                                    class="mt-1 block w-full border rounded px-3 py-2"
                                />
                            </div>
                        </div>

                        <div class="flex gap-3 justify-end">
                            <a href="{{ route('locations.index') }}" class="px-4 py-2 border rounded">
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

