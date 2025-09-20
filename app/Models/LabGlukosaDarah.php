<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LabGlukosaDarah extends Model
{
    use HasFactory;

    protected $table = 'lab_glukosa_darah';

    protected $fillable = [
        'order_id',
        'glukosa_puasa',
        'glukosa_2jam_pp',
        'hba1c',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}