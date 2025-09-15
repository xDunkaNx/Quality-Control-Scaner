<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Registrar defecto
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            @if(session('warning'))
                <div class="mb-4 rounded-md bg-yellow-50 p-4 text-yellow-800">
                    {{ session('warning') }}
                </div>
            @endif
            @if($errors->any())
                <div class="mb-4 rounded-md bg-red-50 p-4 text-red-800">
                    <ul class="list-disc pl-5">
                        @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form method="POST" action="{{ route('defects.store') }}" enctype="multipart/form-data" class="space-y-4">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Código de barras</label>
                            <input name="barcode" class="mt-1 w-full border rounded p-2" required value="{{ old('barcode') }}">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">SKU (opcional)</label>
                            <input name="sku" class="mt-1 w-full border rounded p-2" value="{{ old('sku') }}">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nombre (si no existe)</label>
                            <input name="name" class="mt-1 w-full border rounded p-2" value="{{ old('name') }}">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tipo de defecto</label>
                            <select name="defect_type_id" class="mt-1 w-full border rounded p-2" required>
                                @foreach($types as $t)
                                    <option value="{{ $t->id }}">{{ $t->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Ubicación</label>
                            <select name="location_id" class="mt-1 w-full border rounded p-2">
                                <option value="">-- Selecciona --</option>
                                @foreach($locations as $loc)
                                    <option value="{{ $loc->id }}">{{ $loc->name }}</option>
                                @endforeach
                            </select>
                            <input name="location_text" class="mt-2 w-full border rounded p-2" value="{{ old('location_text') }}" placeholder="Ej. Góndola 7">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Severidad (1-5)</label>
                            <input type="number" name="severity" min="1" max="5" value="{{ old('severity',1) }}" class="mt-1 w-full border rounded p-2">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Lote</label>
                            <input name="lot" class="mt-1 w-full border rounded p-2" value="{{ old('lot') }}">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Notas</label>
                            <input name="notes" class="mt-1 w-full border rounded p-2" value="{{ old('notes') }}">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Foto (opcional)</label>
                        <input type="file" name="photo" accept="image/*" class="mt-1 w-full border rounded p-2">
                    </div>

                    <label class="inline-flex items-center">
                        <input type="checkbox" class="rounded mr-2" value="1" name="force">
                        <span>Registrar aunque se detecte duplicado (10 min)</span>
                    </label>

                    <div>
                        <button class="px-4 py-2 bg-indigo-600 text-white rounded">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
