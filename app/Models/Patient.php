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
        'tanggal_lahir',
        'umur',
        'departemen',
        'jabatan',
        'riwayat_kebiasaan_hidup',
        'merokok',
        'minum_alkohol',
        'olahraga',
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
}