<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DefectType extends Model
{
    protected $fillable = ['code','name','requires_photo'];

    public function defects(): HasMany
    {
        return $this->hasMany(Defect::class);
    }
}
