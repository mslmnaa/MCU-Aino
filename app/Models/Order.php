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