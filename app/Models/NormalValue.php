<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NormalValue extends Model
{
    protected $fillable = [
        'category',
        'parameter',
        'value',
        'unit'
    ];

    public static function getValuesByCategory()
    {
        return self::all()->groupBy('category')->map(function ($items) {
            return $items->pluck('value', 'parameter');
        });
    }
}
