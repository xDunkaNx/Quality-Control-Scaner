<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Editar tipo de defecto
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

                    <form method="POST" action="{{ route('defect-types.update', $defectType) }}" class="space-y-4">
                        @csrf
                        @method('PUT')

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Código</label>
                            <input
                                type="text"
                                name="code"
                                value="{{ old('code', $defectType->code) }}"
                                class="mt-1 block w-full border rounded px-3 py-2 font-mono"
                                required
                            />
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nombre</label>
                            <input
                                type="text"
                                name="name"
                                value="{{ old('name', $defectType->name) }}"
                                class="mt-1 block w-full border rounded px-3 py-2"
                                required
                            />
                        </div>

                        <div>
                            <label class="inline-flex items-center gap-2">
                                <input
                                    type="checkbox"
                                    name="requires_photo"
                                    value="1"
                                    @checked(old('requires_photo', $defectType->requires_photo))
                                />
                                <span class="text-sm text-gray-700">Requiere foto para registrar</span>
                            </label>
                        </div>

                        <div class="flex gap-3 justify-end">
                            <a href="{{ route('defect-types.index') }}" class="px-4 py-2 border rounded">
                                Cancelar
                            </a>
                            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 transition">
                                Actualizar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

