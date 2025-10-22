<x-app-layout>
    <x-slot name="header">
        <div class="space-y-3">
            <span class="app-badge">Escaneo inteligente</span>
            <div>
                <h1 class="text-2xl font-semibold text-white">Registrar defectos con pistola de códigos</h1>
                <p class="text-sm text-slate-400">Captura mermas en segundos: escanea, verifica y guarda sin perder el foco.</p>
            </div>
        </div>
    </x-slot>

    <section class="px-4 sm:px-6 lg:px-8">
        <div class="mx-auto flex max-w-6xl flex-col gap-6">
            @if(session('ok'))
                <div class="rounded-3xl border border-emerald-400/40 bg-emerald-500/10 px-6 py-4 text-sm text-emerald-100 shadow-lg shadow-emerald-500/20">
                    {{ session('ok') }}
                </div>
            @endif

            @if(session('warning'))
                <div class="rounded-3xl border border-amber-400/40 bg-amber-500/10 px-6 py-4 text-sm text-amber-100 shadow-lg shadow-amber-500/20">
                    {{ session('warning') }}
                </div>
            @endif

            @if($errors->any())
                <div class="rounded-3xl border border-rose-500/40 bg-rose-500/10 px-6 py-4 text-sm text-rose-100 shadow-lg shadow-rose-900/40">
                    <p class="font-semibold mb-2">Antes de continuar revisa:</p>
                    <ul class="list-disc space-y-1 pl-5 text-rose-200">
                        @foreach($errors->all() as $e)
                            <li>{{ $e }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="grid gap-6 lg:grid-cols-[minmax(0,7fr)_minmax(0,5fr)]">
                <div class="app-card p-8">
                    <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
                        <div>
                            <p class="text-sm font-semibold text-slate-300">Ciclo recomendado</p>
                            <p class="text-xs text-slate-500">1) Escanea · 2) Ajusta campos · 3) Guarda o deja que el Enter lo haga por ti.</p>
                        </div>
                        <div class="flex items-center gap-2 rounded-full border border-slate-700/60 bg-slate-900/80 px-3 py-1.5 text-xs text-slate-300">
                            <span class="rounded-full bg-emerald-400/20 px-2 py-0.5 font-semibold text-emerald-200">Shift + Enter</span>
                            <span>Evita el envío automático</span>
                        </div>
                    </div>

                    <form id="scan-form" method="POST" action="{{ route('defects.store') }}" enctype="multipart/form-data" class="space-y-8">
                        @csrf

                        <div class="grid gap-6 lg:grid-cols-[minmax(0,3fr)_minmax(0,2fr)]">
                            <div>
                                <label class="app-label" for="barcode">Código de barras</label>
                                <div class="relative mt-2">
                                    <input
                                        id="barcode"
                                        name="barcode"
                                        type="text"
                                        class="app-input pr-12 text-lg font-semibold tracking-wide"
                                        autocomplete="off"
                                        autocapitalize="off"
                                        spellcheck="false"
                                        inputmode="none"
                                        autofocus
                                        required
                                    >
                                    <span class="pointer-events-none absolute inset-y-0 right-4 flex items-center text-emerald-300/70">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h1m2 0h1m3 0h1m2 0h1m3 0h1M4 18h16M5 6v12m4-12v12m4-12v12m4-12v12" />
                                        </svg>
                                    </span>
                                </div>
                                <p class="mt-2 text-xs text-slate-400">Mantén el cursor aquí y escanea. La pistola envía Enter automáticamente al finalizar.</p>
                            </div>

                            <div class="app-fieldset">
                                <p class="app-label">Producto detectado</p>
                                <p class="mt-2 text-sm text-slate-400">Si el artículo ya existe, completaremos su nombre al momento de leer el código.</p>
                                <input
                                    name="name"
                                    id="name"
                                    class="app-input mt-4"
                                    placeholder="Nombre temporal (opcional)"
                                    value="{{ old('name') }}"
                                >
                            </div>
                        </div>

                        <div class="grid gap-6 md:grid-cols-2">
                            <div class="space-y-4">
                                <div>
                                    <label class="app-label" for="defect_type_id">Tipo de defecto</label>
                                    <select name="defect_type_id" id="defect_type_id" class="app-select" required>
                                        @foreach($types as $t)
                                            <option value="{{ $t->id }}">{{ $t->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="app-label" for="unit">Tipo de unidad</label>
                                    <select name="unit" id="unit" class="app-select">
                                        @foreach(['unidad','pack','disp','docena','bolsa','caja'] as $option)
                                            <option value="{{ $option }}" @selected(old('unit') === $option)>{{ ucfirst($option) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <div>
                                    <label class="app-label" for="location_id">Ubicación</label>
                                    <select name="location_id" id="location_id" class="app-select">
                                        <option value="">Seleccionar…</option>
                                        @foreach($locations as $loc)
                                            <option value="{{ $loc->id }}">{{ $loc->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="app-label" for="lot">Lote (opcional)</label>
                                    <input name="lot" id="lot" class="app-input" placeholder="Ej: L2304A">
                                </div>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <div>
                                <label class="app-label" for="notes">Notas para el equipo</label>
                                <textarea name="notes" id="notes" rows="2" class="app-input" placeholder="Describe daños visibles, fecha de caducidad, etc.">{{ old('notes') }}</textarea>
                            </div>

                            <div class="grid gap-4 lg:grid-cols-[minmax(0,2fr)_minmax(0,3fr)]">
                                <div>
                                    <label class="app-label" for="photo">Foto de evidencia</label>
                                    <input type="file" name="photo" id="photo" accept="image/*" class="app-input file:mr-3 file:rounded-lg file:border-0 file:bg-emerald-400/20 file:px-3 file:py-2 file:text-emerald-200 hover:file:bg-emerald-400/30">
                                    <p class="mt-2 text-xs text-slate-500">Formatos permitidos: JPG, PNG (máx. 4 MB)</p>
                                </div>

                                <div class="flex flex-wrap items-center gap-3 rounded-2xl border border-slate-800 bg-slate-900/60 px-4 py-4 text-sm text-slate-300">
                                    <label class="inline-flex items-center gap-2">
                                        <input type="checkbox" class="h-4 w-4 rounded border-slate-700 bg-slate-900 text-emerald-400 focus:ring-emerald-400/50" value="1" name="force" id="force">
                                        <span>Forzar guardado si se detecta duplicado (últimos 10 min)</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-col gap-4 rounded-2xl border border-slate-800 bg-slate-900/60 p-6">
                            <div class="flex flex-wrap items-center justify-between gap-4">
                                <button id="submit-btn" type="button" class="app-cta">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16.242V19a1 1 0 001 1h2.758a1 1 0 00.707-.293l9.243-9.243a2 2 0 00-2.828-2.828l-9.243 9.243A1 1 0 006 17.828 1 1 0 014 16.242z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 5a2 2 0 112.828 2.828L9 16.657" />
                                    </svg>
                                    Guardar defectos
                                </button>

                                <div class="flex flex-wrap items-center gap-4 text-xs">
                                    <label class="inline-flex items-center gap-2 rounded-full border border-emerald-400/40 bg-emerald-400/10 px-3 py-1 font-medium text-emerald-200">
                                        <input type="checkbox" id="autosubmit" class="h-4 w-4 rounded border-emerald-400/40 bg-slate-900 text-emerald-400 focus:ring-emerald-400/60" checked>
                                        Auto-guardar al leer Enter
                                    </label>

                                    <label class="inline-flex items-center gap-2 rounded-full border border-sky-400/40 bg-sky-400/10 px-3 py-1 font-medium text-sky-200">
                                        <input type="checkbox" id="lockFocus" class="h-4 w-4 rounded border-sky-400/40 bg-slate-900 text-sky-300 focus:ring-sky-400/60" checked>
                                        Mantener foco en el escáner
                                    </label>
                                </div>
                            </div>

                            <p class="text-[11px] text-slate-500">
                                Consejo: si necesitas completar campos adicionales, desmarca el auto-guardado temporalmente. Al volver a activarlo la pistola registrará de nuevo sin tocar el mouse.
                            </p>
                        </div>
                    </form>
                </div>

                <aside class="app-card flex flex-col justify-between p-8">
                    <div class="space-y-6">
                        <div class="rounded-2xl border border-emerald-500/40 bg-emerald-500/5 p-5">
                            <p class="text-xs font-semibold uppercase tracking-widest text-emerald-200">Modo ultra-rápido</p>
                            <h3 class="mt-3 text-lg font-semibold text-slate-50">Escanea y confirma en menos de 5 segundos</h3>
                            <p class="mt-2 text-sm text-slate-300">Haz que el proceso sea fluido: evita usar el teclado y apóyate en las alertas para capturar defectos repetidos.</p>
                        </div>

                        <div class="space-y-3">
                            <h4 class="app-section-title">Buenas prácticas</h4>
                            <ul class="space-y-3 text-sm text-slate-300">
                                <li class="flex items-start gap-3">
                                    <span class="mt-0.5 flex h-6 w-6 items-center justify-center rounded-full bg-emerald-400/10 text-xs font-semibold text-emerald-200">1</span>
                                    Escanea el código y confirma que el nombre coincida. Ajusta manualmente si el producto es nuevo.
                                </li>
                                <li class="flex items-start gap-3">
                                    <span class="mt-0.5 flex h-6 w-6 items-center justify-center rounded-full bg-emerald-400/10 text-xs font-semibold text-emerald-200">2</span>
                                    Selecciona el tipo de defecto y la ubicación exacta; así los reportes semanales llegan balanceados.
                                </li>
                                <li class="flex items-start gap-3">
                                    <span class="mt-0.5 flex h-6 w-6 items-center justify-center rounded-full bg-emerald-400/10 text-xs font-semibold text-emerald-200">3</span>
                                    Usa fotos solo cuando el daño sea visual. Si el tipo marcado requiere imagen lo indicaremos al intentar guardar.
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="mt-8 rounded-2xl border border-slate-800 bg-slate-950/60 p-5">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs uppercase tracking-wider text-slate-400">Shift + <span class="text-slate-200">Enter</span></p>
                                <p class="text-sm font-semibold text-slate-100">Pausa temporalmente el auto-guardado</p>
                            </div>
                            <div class="rounded-full border border-slate-800 bg-slate-900/80 px-3 py-2 text-sm font-semibold text-emerald-200">
                                Consejo
                            </div>
                        </div>
                        <p class="mt-3 text-xs text-slate-400">Úsalo mientras revisas lotes o agregas notas extensas. Al reactivar el check, el formulario vuelve al modo turbo.</p>
                    </div>
                </aside>
            </div>
        </div>
    </section>

    <script>
        (function(){
            const barcode = document.getElementById('barcode');
            const form = document.getElementById('scan-form');
            const autosubmit = document.getElementById('autosubmit');
            const lockFocus = document.getElementById('lockFocus');
            const defectType = document.getElementById('defect_type_id');
            const submitBtn = document.getElementById('submit-btn');

            form.reset();

            const focusBarcode = () => {
                if (lockFocus.checked) {
                    barcode.focus({ preventScroll: true });
                }
            };

            window.addEventListener('load', focusBarcode);
            document.addEventListener('visibilitychange', focusBarcode);
            document.addEventListener('click', (event) => {
                if (lockFocus.checked && !event.target.closest('input,select,textarea,button,label')) {
                    focusBarcode();
                }
            });

            submitBtn.addEventListener('click', () => {
                const barcodeValue = barcode.value.trim();
                if (!barcodeValue.length) {
                    alert('Debes ingresar o escanear un código de barras.');
                    return focusBarcode();
                }
                if (!defectType.value) {
                    alert('Selecciona un tipo de defecto.');
                    return defectType.focus();
                }

                form.submit();
            });

            barcode.addEventListener('keyup', async (event) => {
                if (event.key === 'Enter' && autosubmit.checked) {
                    event.preventDefault();

                    const barcodeValue = barcode.value.trim();
                    if (!barcodeValue.length || !defectType.value) {
                        return;
                    }

                    try {
                        const response = await fetch("{{ route('defects.getName') }}", {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "Accept": "application/json",
                                "X-CSRF-TOKEN": "{{ csrf_token() }}"
                            },
                            body: JSON.stringify({ barcode: barcodeValue })
                        });

                        if (response.ok) {
                            const data = await response.json();
                            const nameInput = document.getElementById('name');
                            if (nameInput && data?.name) {
                                nameInput.value = data.name;
                            }
                        }
                    } catch (error) {
                        console.error("Error al consultar el nombre:", error);
                    }
                }
            });

            @if(session('ok'))
                barcode.value = '';
                focusBarcode();
            @endif
        })();
    </script>
</x-app-layout>
