<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PemeriksaanMata extends Model
{
    use HasFactory;

    protected $table = 'pemeriksaan_mata';

    protected $fillable = [
        'order_id',
        'dengan_kacamata',
        'tanpa_kacamata',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}