<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KpiQuantTemplate extends Model
{
    protected $fillable = [
        'name', 'weight_percent', 'order', 'is_active',
    ];

    protected $casts = [
        'weight_percent' => 'decimal:2',
        'is_active'      => 'boolean',
    ];

    public function details(): HasMany
    {
        return $this->hasMany(KpiQuantDetail::class);
    }

    public static function activeOrdered()
    {
        return self::where('is_active', true)->orderBy('order')->get();
    }
}