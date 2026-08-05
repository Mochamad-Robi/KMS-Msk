<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KpiWarning extends Model
{
    protected $fillable = [
        'kpi_evaluation_id', 'type', 'is_present', 'weight_percent', 'score',
    ];

    protected $casts = [
        'is_present'     => 'boolean',
        'weight_percent' => 'decimal:2',
        'score'          => 'decimal:2',
    ];

    const TYPE_LABELS = [
        'sp1' => 'SP 1',
        'sp2' => 'SP 2',
        'sp3' => 'SP 3',
    ];

    public function evaluation(): BelongsTo
    {
        return $this->belongsTo(KpiEvaluation::class, 'kpi_evaluation_id');
    }

    public function getTypeLabelAttribute(): string
    {
        return self::TYPE_LABELS[$this->type] ?? $this->type;
    }
}