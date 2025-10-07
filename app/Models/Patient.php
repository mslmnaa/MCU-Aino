<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'share_id',
        'name',
        'jenis_kelamin',
        'tanggal_lahir',
        'umur',
        'departemen',
        'jabatan',
        'riwayat_kebiasaan_hidup',
        'merokok',
        'minum_alkohol',
        'olahraga',
        'profile_photo',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'merokok' => 'boolean',
        'minum_alkohol' => 'boolean',
        'olahraga' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    // Get all MCU orders sorted by date (latest first)
    public function getMcuOrdersAttribute()
    {
        return $this->orders()
            ->orderBy('tgl_order', 'desc')
            ->get();
    }

    // Get the latest/most recent MCU order
    public function getLatestMcuAttribute()
    {
        return $this->orders()
            ->orderBy('tgl_order', 'desc')
            ->first();
    }

    // Check if patient has multiple MCUs
    public function getHasMultipleMcusAttribute()
    {
        return $this->orders()->count() > 1;
    }

    // Get MCU orders grouped by year for easy navigation
    public function getMcuOrdersByYearAttribute()
    {
        return $this->mcu_orders->groupBy(function($order) {
            return $order->tgl_order->format('Y');
        });
    }

    // Relationship for trend configurations
    public function trendConfigs()
    {
        return $this->hasMany(PatientTrendConfig::class);
    }
}