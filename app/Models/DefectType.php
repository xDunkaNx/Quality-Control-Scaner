<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DefectType extends Model
{
    protected $fillable = ['code','name','requires_photo'];
}
