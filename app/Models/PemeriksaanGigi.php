<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PemeriksaanGigi extends Model
{
    use HasFactory;

    protected $table = 'pemeriksaan_gigi';

    protected $fillable = [
        'order_id',
        'kondisi_gigi',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}