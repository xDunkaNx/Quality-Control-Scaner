<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Defect;
use App\Models\Product;
use App\Models\DefectType;
use App\Models\Location;
use Illuminate\Support\Carbon;

class DefectSeeder extends Seeder
{
    public function run(): void
    {
        // Asegura datos base
        $product  = Product::first() ?? Product::create([
            'barcode' => '1234567890123',
            'name'    => 'Producto demo',
            'sku'     => 'SKU-DEM-001',
            'brand'   => 'Marca X',
            'category'=> 'Limpieza',
        ]);

        $type     = DefectType::first();     // asume que ya corriste DefectTypeSeeder
        $location = Location::first();       // asume que ya corriste LocationSeeder

        // Si faltara alguno, salimos para no romper
        if (!$type || !$location) return;

        foreach (range(1, 10) as $i) {
            Defect::create([
                'product_id'     => $product->id,
                'defect_type_id' => $type->id,
                'location_id'    => $location->id,
                'lot'            => 'Lote-' . rand(100, 999),
                'notes'          => 'Nota de prueba ' . $i,
                'severity'       => rand(1, 5),
                'status'         => 'open', // valores: open|review|closed|scrapped
                'found_at'       => Carbon::now()->subDays(rand(0, 7))->subMinutes(rand(0, 1440)),
                'reported_by'    => 1,      // id del admin que creaste en el seeder
            ]);
        }
    }
}
