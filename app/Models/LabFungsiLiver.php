<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LabFungsiLiver extends Model
{
    use HasFactory;

    protected $table = 'hasil_fungsi_hati';

    protected $fillable = [
        'order_id',
        'sgot',
        'sgpt',
        'kesimpulan_fungsi_hati',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}