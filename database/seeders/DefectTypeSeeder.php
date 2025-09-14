<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DefectTypeSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('defect_types')->upsert([
            ['code'=>'PKG','name'=>'Empaque dañado','requires_photo'=>false],
            ['code'=>'EXP','name'=>'Vencido','requires_photo'=>true],
            ['code'=>'BRK','name'=>'Roto','requires_photo'=>false],
            ['code'=>'LBL','name'=>'Etiqueta ilegible','requires_photo'=>false],
        ], ['code'], ['name','requires_photo']);
    }
}
