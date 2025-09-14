<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['barcode','sku','name','brand','category'];
    public function defects(){ return $this->hasMany(Defect::class); }
}
