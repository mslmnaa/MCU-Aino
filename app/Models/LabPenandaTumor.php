<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LabPenandaTumor extends Model
{
    use HasFactory;

    protected $table = 'lab_penanda_tumor';

    protected $fillable = [
        'order_id',
        'hbsag',
        'cea',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}