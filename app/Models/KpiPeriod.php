<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KpiPeriod extends Model
{
    protected $fillable = [
        'year', 'quartal', 'start_date', 'end_date', 'is_open', 'notified',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date'   => 'date',
        'is_open'    => 'boolean',
        'notified'   => 'boolean',
    ];

    public function evaluations(): HasMany
    {
        return $this->hasMany(KpiEvaluation::class);
    }

    public function getLabelAttribute(): string
    {
        return "{$this->quartal} {$this->year}";
    }

    public function isCurrentlyActive(): bool
    {
        return $this->is_open
            && now()->toDateString() >= $this->start_date->toDateString()
            && now()->toDateString() <= $this->end_date->toDateString();
    }
}