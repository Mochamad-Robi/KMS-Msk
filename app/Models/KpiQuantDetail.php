<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KpiQuantDetail extends Model
{
    protected $fillable = [
        'kpi_evaluation_id', 'indicator_name', 'weight_percent', 'order',
        'target', 'actual', 'achievement_percent', 'score',
    ];

    protected $casts = [
        'weight_percent'      => 'decimal:2',
        'target'              => 'decimal:2',
        'actual'               => 'decimal:2',
        'achievement_percent' => 'decimal:2',
        'score'                => 'decimal:2',
    ];

    public function evaluation(): BelongsTo
    {
        return $this->belongsTo(KpiEvaluation::class, 'kpi_evaluation_id');
    }

    public function calculateAchievement(): float
    {
        if (!$this->target || $this->target == 0) {
            return 0;
        }

        return round(($this->actual / $this->target) * 100, 2);
    }

    public function calculateScore(): float
    {
        $achievement = $this->calculateAchievement();
        return round(($achievement / 100) * $this->weight_percent, 2);
    }
}