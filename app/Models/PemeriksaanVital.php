<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PemeriksaanVital extends Model
{
    use HasFactory;

    protected $table = 'pemeriksaan_vital';

    protected $fillable = [
        'order_id',
        'berat_badan',
        'tinggi_badan',
        'lingkar_perut',
        'bmi',
        'klasifikasi_tekanan_darah',
        'klasifikasi_od',
        'klasifikasi_os',
        'persepsi_warna',
        'pemeriksaan_fisik_umum',
        'kesimpulan_fisik',
        'rekomendasi',
        'saran',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}