<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LabPenandaTumor extends Model
{
    use HasFactory;

    protected $table = 'hasil_penanda_tumor';

    protected $fillable = [
        'order_id',
        'hbsag',
        'cea',
        'kesimpulan_penanda_tumor',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}