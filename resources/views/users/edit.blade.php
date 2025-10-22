<x-app-layout>
    <x-slot name="header">
        <div class="space-y-2">
            <span class="app-badge">Administración</span>
            <div>
                <h2 class="text-2xl font-semibold text-white">Editar usuario</h2>
                <p class="text-sm text-slate-400">Actualiza datos personales, credenciales y permisos asignados.</p>
            </div>
        </div>
    </x-slot>

    <section class="app-page py-8">
        <div class="mx-auto max-w-4xl space-y-6">
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

                <form method="POST" action="{{ route('users.update', $user) }}" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div class="grid gap-6 md:grid-cols-2">
                        <div>
                            <label class="app-label" for="name">Nombre</label>
                            <input
                                type="text"
                                id="name"
                                name="name"
                                value="{{ old('name', $user->name) }}"
                                class="app-input"
                                required
                            />
                        </div>

                        <div>
                            <label class="app-label" for="email">Correo</label>
                            <input
                                type="email"
                                id="email"
                                name="email"
                                value="{{ old('email', $user->email) }}"
                                class="app-input"
                                required
                            />
                        </div>
                    </div>

                    <div class="grid gap-6 md:grid-cols-2">
                        <div>
                            <label class="app-label" for="password">Contraseña</label>
                            <input
                                type="password"
                                id="password"
                                name="password"
                                class="app-input"
                            />
                            <p class="mt-2 text-xs text-slate-500">
                                Deja en blanco para mantener la contraseña actual.
                            </p>
                        </div>
                        <div>
                            <label class="app-label" for="password_confirmation">Confirmar contraseña</label>
                            <input
                                type="password"
                                id="password_confirmation"
                                name="password_confirmation"
                                class="app-input"
                            />
                        </div>
                    </div>

                    <div class="app-fieldset space-y-4">
                        <p class="app-section-title">Roles asignados</p>
                        <div class="grid gap-3 sm:grid-cols-2">
                            @php
                                $selectedRoles = old('roles', $user->roles->pluck('name')->all());
                            @endphp
                            @forelse ($roles as $role)
                                <label class="flex items-center gap-3 rounded-2xl border border-slate-800 bg-slate-950/60 px-4 py-3 text-sm text-slate-200">
                                    <input
                                        type="checkbox"
                                        name="roles[]"
                                        value="{{ $role->name }}"
                                        class="h-4 w-4 rounded border-slate-700 bg-slate-900 text-emerald-400 focus:ring-emerald-400/60"
                                        @checked(in_array($role->name, $selectedRoles, true))
                                    />
                                    <span class="capitalize">{{ $role->name }}</span>
                                </label>
                            @empty
                                <p class="text-sm text-slate-400">
                                    No hay roles configurados.
                                </p>
                            @endforelse
                        </div>
                    </div>

                    <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                        <a href="{{ route('users.index') }}" class="app-cta-secondary">
                            Cancelar
                        </a>
                        <button type="submit" class="app-cta">
                            Guardar cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>
</x-app-layout>
