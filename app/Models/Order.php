<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'cabang',
        'no_lab',
        'tgl_order',
        'mou',
    ];

    protected $casts = [
        'tgl_order' => 'date',
    ];

    // Auto-detect MCU type based on existing data patterns
    public function getMcuTypeAttribute()
    {
        // Get previous MCU for this patient
        $previousMcu = Order::where('patient_id', $this->patient_id)
            ->where('tgl_order', '<', $this->tgl_order)
            ->orderBy('tgl_order', 'desc')
            ->first();

        // If no previous MCU, this is the first one
        if (!$previousMcu) {
            return 'pre-employment'; // First MCU is usually pre-employment
        }

        // Calculate days difference from previous MCU
        $daysDiff = $this->tgl_order->diffInDays($previousMcu->tgl_order);

        // Detection logic based on interval
        if ($daysDiff <= 180) {
            return 'recheck'; // Less than 6 months = follow-up/recheck
        } elseif ($daysDiff <= 400) {
            return 'annual'; // 6-13 months = annual routine
        } elseif ($daysDiff > 730) {
            return 'return-to-work'; // More than 2 years = return to work
        } else {
            return 'annual'; // 13-24 months = annual
        }
    }

    // Get MCU type with color coding for badges
    public function getMcuTypeBadgeAttribute()
    {
        $type = $this->mcu_type;

        $badges = [
            'annual' => [
                'text' => 'Annual',
                'color' => 'bg-green-100 text-green-800',
                'icon' => '📅'
            ],
            'recheck' => [
                'text' => 'Recheck',
                'color' => 'bg-yellow-100 text-yellow-800',
                'icon' => '🔄'
            ],
            'pre-employment' => [
                'text' => 'Pre-Employment',
                'color' => 'bg-blue-100 text-blue-800',
                'icon' => '🏢'
            ],
            'return-to-work' => [
                'text' => 'Return to Work',
                'color' => 'bg-orange-100 text-orange-800',
                'icon' => '↩️'
            ]
        ];

        return $badges[$type] ?? $badges['annual'];
    }

    // Get formatted MCU display text for dropdowns
    public function getMcuDisplayTextAttribute()
    {
        $badge = $this->mcu_type_badge;
        return $this->tgl_order->format('M j, Y') . ' - ' . $badge['text'] . ' (' . $this->no_lab . ')';
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function labHematologi()
    {
        return $this->hasOne(LabHematologi::class);
    }

    public function labUrine()
    {
        return $this->hasOne(LabUrine::class);
    }

    public function labFungsiLiver()
    {
        return $this->hasOne(LabFungsiLiver::class);
    }

    public function labProfilLemak()
    {
        return $this->hasOne(LabProfilLemak::class);
    }

    public function labFungsiGinjal()
    {
        return $this->hasOne(LabFungsiGinjal::class);
    }

    public function labGlukosaDarah()
    {
        return $this->hasOne(LabGlukosaDarah::class);
    }

    public function labPenandaTumor()
    {
        return $this->hasOne(LabPenandaTumor::class);
    }

    public function radiologi()
    {
        return $this->hasOne(Radiologi::class);
    }

    public function pemeriksaanVital()
    {
        return $this->hasOne(PemeriksaanVital::class);
    }

    public function statusGizi()
    {
        return $this->hasOne(StatusGizi::class);
    }

    public function tandaVital()
    {
        return $this->hasOne(TandaVital::class);
    }

    public function pemeriksaanMata()
    {
        return $this->hasOne(PemeriksaanMata::class);
    }

    public function pemeriksaanGigi()
    {
        return $this->hasOne(PemeriksaanGigi::class);
    }

    public function tesFisik()
    {
        return $this->hasOne(TesFisik::class);
    }

    public function riwayatKebiasaanHidup()
    {
        return $this->hasOne(RiwayatKebiasaanHidup::class);
    }
}