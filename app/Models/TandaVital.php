<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TandaVital extends Model
{
    use HasFactory;

    protected $table = 'tanda_vital';

    protected $fillable = [
        'order_id',
        'tekanan_darah',
        'nadi',
        'pernapasan',
        'suhu_tubuh',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}