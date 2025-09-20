<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TesFisik extends Model
{
    use HasFactory;

    protected $table = 'tes_fisik';

    protected $fillable = [
        'order_id',
        'smell_test',
        'low_back_pain',
        'laseque_test',
        'bragard_test',
        'patrict_test',
        'kontra_patrict',
        'neer_sign',
        'range_of_motion',
        'speed_test',
        'straight_leg_raised_test',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}