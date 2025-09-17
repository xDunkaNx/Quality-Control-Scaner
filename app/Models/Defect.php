<?php

// app/Models/Defect.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Defect extends Model
{
    use LogsActivity;
    protected $fillable = [
        'product_id','defect_type_id','location_id','location_text',
        'severity','status','lot','notes','photo_path','found_at',
        'reported_by','resolved_at'
    ];
    protected $casts = [
        'found_at' => 'datetime',
    ];
    // Especifica los atributos que quieres auditar
    protected static $logAttributes = [
        'product_id', 'defect_type_id', 'location_id', 'location_text', 'lot',
        'notes', 'severity', 'status', 'found_at', 'reported_by', 'photo_path'
    ];

    // Solo guarda los cambios "sucios" (los que realmente han cambiado)
    protected static $logOnlyDirty = true;

    // No guardar logs vacíos
    protected static $submitEmptyLogs = false;

    // Métodos opcionales para configurar el nombre del log y otros detalles
    public function getActivitylogOptions(): \Spatie\Activitylog\LogOptions
    {
        return \Spatie\Activitylog\LogOptions::defaults()
            ->useLogName('defects')  // Nombre de la actividad
            ->logOnly(static::$logAttributes)  // Solo los atributos especificados
            ->logOnlyDirty()  // Solo los atributos "sucios"
            ->dontSubmitEmptyLogs();  // Evitar logs vacíos
    }
        public function product(){ return $this->belongsTo(Product::class); }
    public function type(){ return $this->belongsTo(DefectType::class,'defect_type_id'); }
    public function location(){ return $this->belongsTo(Location::class); }
    public function reporter(){ return $this->belongsTo(User::class,'reported_by'); }
}

