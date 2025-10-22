<x-app-layout>
    <x-slot name="header">
        <div class="space-y-2">
            <span class="app-badge">Catálogo</span>
            <div>
                <h2 class="text-2xl font-semibold text-white">Nuevo tipo de defecto</h2>
                <p class="text-sm text-slate-400">Define códigos cortos y nombres claros para mantener consistencia en los reportes.</p>
            </div>
        </div>
    </x-slot>

    <section class="app-page py-8">
        <div class="mx-auto max-w-3xl space-y-6">
            <div class="app-data-card">
                @if ($errors->any())
                    <div class="app-alert-danger mb-6">
                        <p class="font-semibold mb-2 text-rose-100">Revisa los campos:</p>
                        <ul class="list-disc list-inside space-y-1 text-sm text-rose-100/90">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('defect-types.store') }}" class="space-y-6">
                    @csrf

                    <div class="grid gap-6 sm:grid-cols-2">
                        <div>
                            <label class="app-label" for="code">Código</label>
                            <input
                                type="text"
                                id="code"
                                name="code"
                                value="{{ old('code') }}"
                                class="app-input font-mono uppercase"
                                placeholder="Ej: PKG"
                                required
                            />
                        </div>

                        <div>
                            <label class="app-label" for="name">Nombre</label>
                            <input
                                type="text"
                                id="name"
                                name="name"
                                value="{{ old('name') }}"
                                class="app-input"
                                required
                            />
                        </div>
                    </div>

                    <label class="inline-flex items-center gap-3 rounded-2xl border border-slate-800 bg-slate-950/60 px-4 py-3 text-sm text-slate-200">
                        <input
                            type="checkbox"
                            name="requires_photo"
                            value="1"
                            class="h-4 w-4 rounded border-slate-700 bg-slate-900 text-emerald-400 focus:ring-emerald-400/60"
                            @checked(old('requires_photo'))
                        />
                        <span>Requiere foto para registrar</span>
                    </label>

                    <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                        <a href="{{ route('defect-types.index') }}" class="app-cta-secondary">
                            Cancelar
                        </a>
                        <button type="submit" class="app-cta">
                            Guardar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>
</x-app-layout>
