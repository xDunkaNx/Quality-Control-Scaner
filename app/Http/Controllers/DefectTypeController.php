<?php

namespace App\Http\Controllers;

use App\Models\DefectType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class DefectTypeController extends Controller
{
    public function index(Request $request): View
    {
        $types = DefectType::query()
            ->when(
                $request->filled('search'),
                function ($query) use ($request) {
                    $term = $request->string('search');
                    $query->where(function ($builder) use ($term) {
                        $builder->where('name', 'like', "%{$term}%")
                            ->orWhere('code', 'like', "%{$term}%");
                    });
                }
            )
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('defect-types.index', [
            'types' => $types,
        ]);
    }

    public function create(): View
    {
        return view('defect-types.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'code' => ['required', 'string', 'max:64', 'unique:defect_types,code'],
            'name' => ['required', 'string', 'max:255'],
            'requires_photo' => ['nullable', 'boolean'],
        ]);

        $data['requires_photo'] = (bool) ($data['requires_photo'] ?? false);

        DefectType::create($data);

        return redirect()
            ->route('defect-types.index')
            ->with('ok', 'Tipo de defecto creado correctamente.');
    }

    public function edit(DefectType $defectType): View
    {
        return view('defect-types.edit', [
            'defectType' => $defectType,
        ]);
    }

    public function update(Request $request, DefectType $defectType): RedirectResponse
    {
        $data = $request->validate([
            'code' => [
                'required',
                'string',
                'max:64',
                Rule::unique('defect_types', 'code')->ignore($defectType->id),
            ],
            'name' => ['required', 'string', 'max:255'],
            'requires_photo' => ['nullable', 'boolean'],
        ]);

        $data['requires_photo'] = (bool) ($data['requires_photo'] ?? false);

        $defectType->update($data);

        return redirect()
            ->route('defect-types.index')
            ->with('ok', 'Tipo de defecto actualizado correctamente.');
    }

    public function destroy(DefectType $defectType): RedirectResponse
    {
        if ($defectType->defects()->exists()) {
            return back()->with('error', 'No se puede eliminar un tipo que está asociado a defectos.');
        }

        $defectType->delete();

        return redirect()
            ->route('defect-types.index')
            ->with('ok', 'Tipo de defecto eliminado correctamente.');
    }
}

