<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    protected $fillable = ['code','name','parent_code','latitude','longitude'];
    public function defects(){ return $this->hasMany(Defect::class); }
}
