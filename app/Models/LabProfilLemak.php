<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LabProfilLemak extends Model
{
    use HasFactory;

    protected $table = 'lab_profil_lemak';

    protected $fillable = [
        'order_id',
        'cholesterol',
        'trigliserida',
        'hdl_cholesterol',
        'ldl_cholesterol',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}