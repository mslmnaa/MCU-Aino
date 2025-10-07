<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrendTemplate extends Model
{
    protected $fillable = [
        'name',
        'description',
        'config',
        'is_default',
    ];

    protected $casts = [
        'config' => 'array',
        'is_default' => 'boolean',
    ];
}
