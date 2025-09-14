<?php

namespace App\Http\Controllers;

use App\Models\Defect;
use App\Models\Product;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DefectController extends Controller
{
    public function index(Request $r)
    {
        $defects = Defect::with(['product','type','location','reporter'])
            ->when($r->status, fn($q)=>$q->where('status',$r->status))
            ->when($r->date_from, fn($q)=>$q->whereDate('found_at','>=',$r->date_from))
            ->when($r->date_to, fn($q)=>$q->whereDate('found_at','<=',$r->date_to))
            ->latest('found_at')
            ->paginate(50);

        return view('defects.index', compact('defects'));
    }

    public function create()
    {
        $locations = Location::orderBy('name')->get(['id','name','code']);
        $types = \App\Models\DefectType::orderBy('name')->get(['id','name']);
        return view('defects.create', compact('locations','types'));
    }

    public function store(Request $r)
    {
        $data = $r->validate([
            'barcode'         => 'required|string|max:64',
            'sku'             => 'nullable|string|max:64',
            'name'            => 'nullable|string|max:255',
            'brand'           => 'nullable|string|max:255',
            'category'        => 'nullable|string|max:255',
            'defect_type_id'  => 'required|exists:defect_types,id',
            'location_id'     => 'nullable|exists:locations,id',
            'location_text'   => 'nullable|string|max:255',
            'severity'        => 'nullable|integer|min:1|max:5',
            'lot'             => 'nullable|string|max:128',
            'notes'           => 'nullable|string',
            'photo'           => 'nullable|image|max:4096',
        ]);

        // Producto por barcode (crea si no existe)
        $product = Product::firstOrCreate(
            ['barcode'=>$data['barcode']],
            [
                'sku'      => $data['sku'] ?? null,
                'name'     => $data['name'] ?? 'Desconocido',
                'brand'    => $data['brand'] ?? null,
                'category' => $data['category'] ?? null,
            ]
        );

        // Foto opcional
        $path = null;
        if ($r->hasFile('photo')) {
            $path = $r->file('photo')->store('defects', 'public');
        }

        // (Opcional) anti-duplicado 10 minutos por barcode + lote + ubicación
        $isDuplicate = Defect::query()
            ->where('product_id', $product->id)
            ->when($data['lot'] ?? null, fn($q,$lot)=>$q->where('lot',$lot))
            ->when($data['location_id'] ?? null, fn($q,$loc)=>$q->where('location_id',$loc))
            ->when(($data['location_id'] ?? null)===null && ($data['location_text'] ?? null),
                   fn($q,$txt)=>$q->where('location_text',$txt))
            ->where('found_at','>=', now()->subMinutes(10))
            ->exists();

        if ($isDuplicate && !$r->boolean('force')) {
            return back()->with('warning','Este producto ya fue registrado en los últimos 10 minutos en la misma ubicación. Confirma con "force=1" si deseas registrarlo igual.')
                         ->withInput();
        }

        Defect::create([
            'product_id'     => $product->id,
            'defect_type_id' => $data['defect_type_id'],
            'location_id'    => $data['location_id'] ?? null,
            'location_text'  => $data['location_text'] ?? null,
            'severity'       => $data['severity'] ?? 1,
            'status'         => 'open',
            'lot'            => $data['lot'] ?? null,
            'notes'          => $data['notes'] ?? null,
            'photo_path'     => $path,
            'found_at'       => now(),
            'reported_by'    => $r->user()->id,
        ]);

        return redirect()->route('defects.index')->with('ok','Defecto registrado.');
    }
}
