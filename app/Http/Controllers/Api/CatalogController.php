<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DefectType;
use App\Models\Location;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class CatalogController extends Controller
{
    public function defectTypes(Request $request): JsonResponse
    {
        $data = $request->validate([
            'search' => ['nullable', 'string', 'max:255'],
            'updated_since' => ['nullable', 'date'],
            'per_page' => ['nullable', 'integer', 'min:10', 'max:500'],
        ]);

        $perPage = $data['per_page'] ?? 100;

        $types = DefectType::query()
            ->when(
                $data['search'] ?? null,
                function ($query, string $term) {
                    $query->where(function ($builder) use ($term) {
                        $builder->where('name', 'like', "%{$term}%")
                            ->orWhere('code', 'like', "%{$term}%");
                    });
                }
            )
            ->when(
                $data['updated_since'] ?? null,
                function ($query, string $date) {
                    $query->where('updated_at', '>=', Carbon::parse($date));
                }
            )
            ->orderBy('name')
            ->paginate($perPage)
            ->withQueryString();

        return response()->json([
            'data' => $types->through(function (DefectType $type) {
                return [
                    'id' => $type->id,
                    'code' => $type->code,
                    'name' => $type->name,
                    'requires_photo' => (bool) $type->requires_photo,
                    'created_at' => optional($type->created_at)->toIso8601String(),
                    'updated_at' => optional($type->updated_at)->toIso8601String(),
                ];
            }),
            'meta' => [
                'current_page' => $types->currentPage(),
                'last_page' => $types->lastPage(),
                'per_page' => $types->perPage(),
                'total' => $types->total(),
                'next_page_url' => $types->nextPageUrl(),
            ],
        ]);
    }

    public function locations(Request $request): JsonResponse
    {
        $data = $request->validate([
            'search' => ['nullable', 'string', 'max:255'],
            'updated_since' => ['nullable', 'date'],
            'per_page' => ['nullable', 'integer', 'min:10', 'max:500'],
        ]);

        $perPage = $data['per_page'] ?? 100;

        $locations = Location::query()
            ->when(
                $data['search'] ?? null,
                function ($query, string $term) {
                    $query->where(function ($builder) use ($term) {
                        $builder->where('name', 'like', "%{$term}%")
                            ->orWhere('code', 'like', "%{$term}%");
                    });
                }
            )
            ->when(
                $data['updated_since'] ?? null,
                function ($query, string $date) {
                    $query->where('updated_at', '>=', Carbon::parse($date));
                }
            )
            ->orderBy('name')
            ->paginate($perPage)
            ->withQueryString();

        return response()->json([
            'data' => $locations->through(function (Location $location) {
                return [
                    'id' => $location->id,
                    'code' => $location->code,
                    'name' => $location->name,
                    'parent_code' => $location->parent_code,
                    'latitude' => $location->latitude !== null ? (float) $location->latitude : null,
                    'longitude' => $location->longitude !== null ? (float) $location->longitude : null,
                    'created_at' => optional($location->created_at)->toIso8601String(),
                    'updated_at' => optional($location->updated_at)->toIso8601String(),
                ];
            }),
            'meta' => [
                'current_page' => $locations->currentPage(),
                'last_page' => $locations->lastPage(),
                'per_page' => $locations->perPage(),
                'total' => $locations->total(),
                'next_page_url' => $locations->nextPageUrl(),
            ],
        ]);
    }
}

