<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Radiologi extends Model
{
    use HasFactory;

    protected $table = 'radiologi';

    protected $fillable = [
        'order_id',
        'ecg',
        'kesimpulan_ecg',
        'thorax_pa',
        'kesimpulan_thorax_pa',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}