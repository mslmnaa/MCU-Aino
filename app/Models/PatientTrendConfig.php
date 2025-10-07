<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PatientTrendConfig extends Model
{
    protected $fillable = [
        'patient_id',
        'parameter_name',
        'exam_type',
        'trend_above_normal',
        'trend_below_normal',
        'notes',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}
