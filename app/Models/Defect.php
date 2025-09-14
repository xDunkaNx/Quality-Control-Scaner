<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Defect extends Model
{
    protected $fillable = [
        'product_id','defect_type_id','location_id','location_text',
        'severity','status','lot','notes','photo_path','found_at',
        'reported_by','resolved_at'
    ];
    protected $casts = ['found_at'=>'datetime','resolved_at'=>'datetime'];

    public function product(){ return $this->belongsTo(Product::class); }
    public function type(){ return $this->belongsTo(DefectType::class,'defect_type_id'); }
    public function location(){ return $this->belongsTo(Location::class); }
    public function reporter(){ return $this->belongsTo(User::class,'reported_by'); }
}
