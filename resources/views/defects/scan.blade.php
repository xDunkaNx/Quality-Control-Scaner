<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Escaneo rápido de defectos (pistola)
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            @if(session('ok'))
                <div class="mb-4 rounded-md bg-green-50 p-4 text-green-800">{{ session('ok') }}</div>
            @endif
            @if(session('warning'))
                <div class="mb-4 rounded-md bg-yellow-50 p-4 text-yellow-800">{{ session('warning') }}</div>
            @endif
            @if($errors->any())
                <div class="mb-4 rounded-md bg-red-50 p-4 text-red-800">
                    <ul class="list-disc pl-5">
                        @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form id="scan-form" method="POST" action="{{ route('defects.store') }}" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Código de barras</label>
                            <input
                                id="barcode"
                                name="barcode"
                                type="text"
                                class=" mt-1 w-full border rounded p-2"
                                autocomplete="off"
                                autocapitalize="off"
                                spellcheck="false"
                                inputmode="none"     {{-- evita teclado móvil si el browser respeta --}}
                                autofocus
                                required
                            >
                            <p class="text-xs text-gray-500 mt-1">Enfoca el cursor aquí y escanea. La pistola escribe los dígitos y suele enviar Enter al final.</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nombre (si no existe)</label>
                            <input name="name" class="mt-1 w-full border rounded p-2" value="{{ old('name') }}">
                        </div>

                    </div>
                    {{-- 1) Campo para pistola --}}
                    {{-- 2) Datos mínimos para guardar rápido --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tipo de defecto</label>
                            <select name="defect_type_id" id="defect_type_id" class="mt-1 w-full border rounded p-2" required>
                                @foreach($types as $t)
                                    <option value="{{ $t->id }}">{{ $t->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Ubicación</label>
                            <select name="location_id" id="location_id" class="mt-1 w-full border rounded p-2">
                                <option value="">—</option>
                                @foreach($locations as $loc)
                                    <option value="{{ $loc->id }}">{{ $loc->name }}</option>
                                @endforeach
                            </select>
                            {{-- <input name="location_text" id="location_text" class="mt-2 w-full border rounded p-2" placeholder="Texto libre (opcional)"> --}}
                        </div>
                        {{-- <div>
                            <label class="block text-sm font-medium text-gray-700">Severidad (1-5)</label>
                            <input type="number" name="severity" id="severity" min="1" max="5" value="1" class="mt-1 w-full border rounded p-2">
                        </div> --}}
                        <div>
                            <label for="unit" class="block text-sm font-medium text-gray-700">Tipo de unidad</label>
                            <select name="unit" id="unit" class="mt-1 w-full border rounded p-2">
                                @foreach(['unidad','pack','disp','docena','bolsa','caja'] as $option)
                                <option value="{{ $option }}" @selected(old('unit') === $option)>{{ ucfirst($option) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">                       
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Lote (opcional)</label>
                            <input name="lot" id="lot" class="mt-1 w-full border rounded p-2">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Notas (opcional)</label>
                            <input name="notes" id="notes" class="mt-1 w-full border rounded p-2">
                        </div>
                    </div>
                    <div>

                        <label class="block text-sm font-medium text-gray-700">Foto (opcional)</label>
                        <input type="file" name="photo" accept="image/*" class="mt-1 w-full border rounded p-2">
                    </div>

                    {{-- Opcional: forzar si detecta duplicado --}}
                    <label class="inline-flex items-center">
                        <input type="checkbox" class="rounded mr-2" value="1" name="force" id="force">
                        <span>Registrar aunque se detecte duplicado (últimos 10 min)</span>
                    </label>

                    <div class="flex items-center gap-3">
                        <button id="submit-btn" class="px-4 py-2 bg-indigo-600 text-white rounded">Guardar</button>

                        {{-- Modo autosubmit: cuando la pistola envía Enter, se intenta enviar si hay datos mínimos --}}
                        <label class="inline-flex items-center text-sm">
                            <input type="checkbox" id="autosubmit" class="rounded mr-2" checked>
                            Auto-guardar al presionar Enter del scanner
                        </label>

                        {{-- Mantener foco siempre en el input --}}
                        <label class="inline-flex items-center text-sm">
                            <input type="checkbox" id="lockFocus" class="rounded mr-2" checked>
                            Mantener foco en “Código de barras”
                        </label>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- JS ultra-liviano para pistola --}}
    <script>
        (function(){
            const barcode = document.getElementById('barcode');
            const form = document.getElementById('scan-form');
            const autosubmit = document.getElementById('autosubmit');
            const lockFocus = document.getElementById('lockFocus');
            const defectType = document.getElementById('defect_type_id');

            // 1) Mantener foco en el input (útil en piso)
            const focusBarcode = () => { if (lockFocus.checked) barcode.focus({ preventScroll:true }); };
            window.addEventListener('load', focusBarcode);
            document.addEventListener('visibilitychange', focusBarcode);
            document.addEventListener('click', (e)=> {
                // si hacen click fuera de inputs, re-enfoca
                if (lockFocus.checked && !e.target.closest('input,select,textarea,button')) focusBarcode();
            });

            // 2) Si la pistola envía Enter al final del código, intentar enviar
            barcode.addEventListener('keydown', (e) => {
                if (e.key === 'Enter' && autosubmit.checked) {
                    e.preventDefault();
                    // Valida mínimos: barcode + defect_type
                    if (barcode.value.trim().length > 0 && defectType.value) {
                        form.submit();
                    }
                }
            });

            // 3) (Opcional) tras enviar/volver con éxito, limpia y re-enfoca
            @if(session('ok'))
                barcode.value = '';
                // puedes limpiar otros campos si quieres que quede todo listo para el siguiente scan:
                // document.getElementById('notes').value = '';
                // document.getElementById('lot').value = '';
                focusBarcode();
            @endif
        })();
    </script>
</x-app-layout>
