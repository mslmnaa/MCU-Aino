<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LabUrine extends Model
{
    use HasFactory;

    protected $table = 'lab_urine';

    protected $fillable = [
        'order_id',
        'warna',
        'kejernihan',
        'bj',
        'ph',
        'protein',
        'glukosa',
        'keton',
        'bilirubin',
        'urobilinogen',
        'nitrit',
        'darah',
        'lekosit_esterase',
        'eritrosit_sedimen',
        'lekosit_sedimen',
        'epitel_sedimen',
        'kristal_sedimen',
        'silinder_sedimen',
        'lain_lain_sedimen',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}