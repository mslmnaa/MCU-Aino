<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LabHematologi extends Model
{
    use HasFactory;

    protected $table = 'lab_hematologi';

    protected $fillable = [
        'order_id',
        'hematologi',
        'hemoglobin',
        'erytrosit',
        'hematokrit',
        'mcv',
        'mch',
        'mchc',
        'rdw',
        'leukosit',
        'eosinofil',
        'basofil',
        'neutrofil_batang',
        'neutrofil_segmen',
        'limfosit',
        'monosit',
        'trombosit',
        'laju_endap_darah',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}