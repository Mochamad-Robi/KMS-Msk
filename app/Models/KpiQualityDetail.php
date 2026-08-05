<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KpiQualityDetail extends Model
{
    protected $fillable = [
        'kpi_evaluation_id', 'submitted_by',
        'kehadiran_target', 'kehadiran_actual', 'kehadiran_achievement',
        'integritas', 'kekeluargaan', 'handal', 'loyalitas', 'amanah', 'saling_menghargai',
        'core_value_average', 'total_quality_score', 'is_final_average',
    ];

    protected $casts = [
        'kehadiran_achievement' => 'decimal:2',
        'integritas'             => 'decimal:2',
        'kekeluargaan'           => 'decimal:2',
        'handal'                 => 'decimal:2',
        'loyalitas'               => 'decimal:2',
        'amanah'                  => 'decimal:2',
        'saling_menghargai'      => 'decimal:2',
        'core_value_average'    => 'decimal:2',
        'total_quality_score'   => 'decimal:2',
        'is_final_average'      => 'boolean',
    ];

    const CORE_VALUE_FIELDS = [
        'integritas'        => 'Integritas',
        'kekeluargaan'      => 'Kekeluargaan',
        'handal'            => 'Handal',
        'loyalitas'         => 'Loyalitas',
        'amanah'            => 'Amanah',
        'saling_menghargai' => 'Saling Menghargai',
    ];

    public function evaluation(): BelongsTo
    {
        return $this->belongsTo(KpiEvaluation::class, 'kpi_evaluation_id');
    }

    public function submittedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'submitted_by');
    }

    public function calculateCoreValueAverage(): float
    {
        $values = array_filter([
            $this->integritas, $this->kekeluargaan, $this->handal,
            $this->loyalitas, $this->amanah, $this->saling_menghargai,
        ], fn($v) => $v !== null);

        if (empty($values)) {
            return 0;
        }

        return round(array_sum($values) / count($values), 2);
    }

    // Hitung rata-rata dari beberapa submission kadept (dept sendiri + cross-dept)
    // jadi 1 nilai final per kategori core value
    public static function calculateFinalAverage($submissions)
    {
        $fields = array_keys(self::CORE_VALUE_FIELDS);
        $averaged = [];

        foreach ($fields as $field) {
            $values = $submissions->pluck($field)->filter(fn($v) => $v !== null);
            $averaged[$field] = $values->count() > 0 ? round($values->avg(), 2) : 0;
        }

        $coreValueAverage = round(array_sum($averaged) / count($averaged), 2);

        // Kehadiran tetap dari submission kadept dept sendiri saja (primary),
        // karena kehadiran bukan dinilai kadept cross-dept
        $primarySubmission = $submissions->firstWhere('is_final_average', false);

        return [
            'fields'              => $averaged,
            'core_value_average'  => $coreValueAverage,
            'kehadiran_target'    => $primarySubmission?->kehadiran_target,
            'kehadiran_actual'     => $primarySubmission?->kehadiran_actual,
            'kehadiran_achievement' => $primarySubmission?->kehadiran_achievement,
        ];
    }
}