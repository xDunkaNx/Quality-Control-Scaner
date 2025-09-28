<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LocationSeeder extends Seeder
{
    public function run(): void
    {
        // DB::table('locations')->upsert([
        //     ['code'=>'SUC_TRUJILLO','name'=>'Sucursal Trujillo','parent_code'=>null],
        //     ['code'=>'ALM_A','name'=>'Almacén A','parent_code'=>'SUC_TRUJILLO'],
        //     ['code'=>'ALM_B','name'=>'Almacén B','parent_code'=>'SUC_TRUJILLO'],
        // ], ['code'], ['name','parent_code']);
        DB::table('locations')->upsert([
            ['code'=>'HANGAR_1','name'=>'Hangar 1','parent_code'=>null],
            ['code'=>'HANGAR_2','name'=>'Hangar 2','parent_code'=>null],
            ['code'=>'HANGAR_3','name'=>'Hangar 3','parent_code'=>null],
            ['code'=>'HANGAR_4','name'=>'Hangar 4','parent_code'=>null],
            ['code'=>'HANGAR_5','name'=>'Hangar 5','parent_code'=>null]
        ], ['code'], ['name','parent_code']);
    }
}
