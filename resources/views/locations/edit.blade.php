<x-app-layout>
    <x-slot name="header">
        <div class="space-y-2">
            <span class="app-badge">Catálogo</span>
            <div>
                <h2 class="text-2xl font-semibold text-white">Editar ubicación</h2>
                <p class="text-sm text-slate-400">Actualiza códigos, jerarquías y coordenadas para mantener el mapa de mermas al día.</p>
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

                <form method="POST" action="{{ route('locations.update', $location) }}" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div class="grid gap-6 sm:grid-cols-2">
                        <div>
                            <label class="app-label" for="code">Código</label>
                            <input
                                type="text"
                                id="code"
                                name="code"
                                value="{{ old('code', $location->code) }}"
                                class="app-input font-mono uppercase"
                                required
                            />
                        </div>

                        <div>
                            <label class="app-label" for="name">Nombre</label>
                            <input
                                type="text"
                                id="name"
                                name="name"
                                value="{{ old('name', $location->name) }}"
                                class="app-input"
                                required
                            />
                        </div>
                    </div>

                    <div>
                        <label class="app-label" for="parent_code">Ubicación superior</label>
                        <select
                            name="parent_code"
                            id="parent_code"
                            class="app-select"
                        >
                            <option value="">Sin jerarquía</option>
                            @foreach ($parents as $parent)
                                <option
                                    value="{{ $parent->code }}"
                                    @selected(old('parent_code', $location->parent_code) === $parent->code)
                                >
                                    {{ $parent->name }} ({{ $parent->code }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid gap-6 sm:grid-cols-2">
                        <div>
                            <label class="app-label" for="latitude">Latitud</label>
                            <input
                                type="number"
                                id="latitude"
                                name="latitude"
                                value="{{ old('latitude', $location->latitude) }}"
                                step="0.000001"
                                class="app-input"
                            />
                        </div>
                        <div>
                            <label class="app-label" for="longitude">Longitud</label>
                            <input
                                type="number"
                                id="longitude"
                                name="longitude"
                                value="{{ old('longitude', $location->longitude) }}"
                                step="0.000001"
                                class="app-input"
                            />
                        </div>
                    </div>

                    <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                        <a href="{{ route('locations.index') }}" class="app-cta-secondary">
                            Cancelar
                        </a>
                        <button type="submit" class="app-cta">
                            Actualizar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>
</x-app-layout>
