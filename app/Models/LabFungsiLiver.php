<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LabFungsiLiver extends Model
{
    use HasFactory;

    protected $table = 'lab_fungsi_liver';

    protected $fillable = [
        'order_id',
        'sgot',
        'sgpt',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}