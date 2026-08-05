<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KpiSpecialAssignment extends Model
{
    protected $fillable = [
        'kpi_evaluation_id', 'name', 'is_present', 'score',
    ];

    protected $casts = [
        'is_present' => 'boolean',
        'score'      => 'decimal:2',
    ];

    public function evaluation(): BelongsTo
    {
        return $this->belongsTo(KpiEvaluation::class, 'kpi_evaluation_id');
    }
}