<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LocationSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('locations')->upsert([
            ['code'=>'SUC_TRUJILLO','name'=>'Sucursal Trujillo','parent_code'=>null],
            ['code'=>'ALM_A','name'=>'Almacén A','parent_code'=>'SUC_TRUJILLO'],
            ['code'=>'ALM_B','name'=>'Almacén B','parent_code'=>'SUC_TRUJILLO'],
        ], ['code'], ['name','parent_code']);
    }
}
