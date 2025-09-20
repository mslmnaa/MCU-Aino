<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LabFungsiGinjal extends Model
{
    use HasFactory;

    protected $table = 'lab_fungsi_ginjal';

    protected $fillable = [
        'order_id',
        'ureum',
        'creatinin',
        'asam_urat',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}