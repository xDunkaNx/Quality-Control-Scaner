<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class LocationController extends Controller
{
    public function index(Request $request): View
    {
        $locations = Location::query()
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

        return view('locations.index', [
            'locations' => $locations,
        ]);
    }

    public function create(): View
    {
        return view('locations.create', [
            'parents' => Location::orderBy('name')->get(['code', 'name']),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'code' => ['required', 'string', 'max:64', 'unique:locations,code'],
            'name' => ['required', 'string', 'max:255'],
            'parent_code' => [
                'nullable',
                'string',
                'max:64',
                Rule::exists('locations', 'code'),
                'different:code',
            ],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
        ]);

        Location::create($data);

        return redirect()
            ->route('locations.index')
            ->with('ok', 'Ubicación creada correctamente.');
    }

    public function edit(Location $location): View
    {
        return view('locations.edit', [
            'location' => $location,
            'parents' => Location::whereKeyNot($location->id)
                ->orderBy('name')
                ->get(['code', 'name']),
        ]);
    }

    public function update(Request $request, Location $location): RedirectResponse
    {
        $data = $request->validate([
            'code' => [
                'required',
                'string',
                'max:64',
                Rule::unique('locations', 'code')->ignore($location->id),
            ],
            'name' => ['required', 'string', 'max:255'],
            'parent_code' => [
                'nullable',
                'string',
                'max:64',
                Rule::exists('locations', 'code'),
                'different:code',
            ],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
        ]);

        $location->update($data);

        return redirect()
            ->route('locations.index')
            ->with('ok', 'Ubicación actualizada correctamente.');
    }

    public function destroy(Location $location): RedirectResponse
    {
        if ($location->defects()->exists()) {
            return back()->with('error', 'No se puede eliminar una ubicación asociada a defectos.');
        }

        $hasChildren = Location::where('parent_code', $location->code)->exists();

        if ($hasChildren) {
            return back()->with('error', 'No se puede eliminar una ubicación que tiene sububicaciones.');
        }

        $location->delete();

        return redirect()
            ->route('locations.index')
            ->with('ok', 'Ubicación eliminada correctamente.');
    }
}

