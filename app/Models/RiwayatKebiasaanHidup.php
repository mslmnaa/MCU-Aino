<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RiwayatKebiasaanHidup extends Model
{
    protected $table = 'riwayat_kebiasaan_hidup';

    protected $fillable = [
        'order_id',
        'merokok',
        'minum_alkohol',
        'olahraga',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
