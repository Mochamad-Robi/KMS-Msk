<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KpiEvaluation extends Model
{
    protected $fillable = [
        'kpi_period_id', 'employee_id', 'evaluated_by',
        'total_quant_score', 'total_quality_score', 'penilaian_1',
        'total_warning_score', 'penilaian_2', 'total_special_score',
        'grand_total', 'grade', 'status', 'submitted_at',
    ];

    protected $casts = [
        'total_quant_score'    => 'decimal:2',
        'total_quality_score'  => 'decimal:2',
        'penilaian_1'          => 'decimal:2',
        'total_warning_score'  => 'decimal:2',
        'penilaian_2'          => 'decimal:2',
        'total_special_score'  => 'decimal:2',
        'grand_total'          => 'decimal:2',
        'submitted_at'         => 'datetime',
    ];

    const GRADES = [
        'istimewa'    => ['label' => 'Istimewa',    'min' => 100.01],
        'baik_sekali' => ['label' => 'Baik Sekali', 'min' => 90],
        'baik'        => ['label' => 'Baik',        'min' => 80],
        'cukup'       => ['label' => 'Cukup',       'min' => 65],
        'kurang'      => ['label' => 'Kurang',      'min' => 0],
    ];

    public function period(): BelongsTo
    {
        return $this->belongsTo(KpiPeriod::class, 'kpi_period_id');
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'employee_id');
    }

    public function evaluator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'evaluated_by');
    }

    public function quantDetails(): HasMany
    {
        return $this->hasMany(KpiQuantDetail::class);
    }

    // Sekarang bisa lebih dari 1 row (submission per kadept + 1 row final average)
    public function qualityDetails(): HasMany
    {
        return $this->hasMany(KpiQualityDetail::class);
    }

    // Helper: ambil hanya row hasil average final
    public function qualityFinal()
    {
        return $this->qualityDetails()->where('is_final_average', true)->first();
    }

    // Helper: ambil submission individual tiap kadept (bukan yang final average)
    public function qualitySubmissions()
    {
        return $this->qualityDetails()->where('is_final_average', false)->get();
    }

    public function warnings(): HasMany
    {
        return $this->hasMany(KpiWarning::class);
    }

    public function specialAssignments(): HasMany
    {
        return $this->hasMany(KpiSpecialAssignment::class);
    }

    public static function calculateGrade(float $grandTotal): string
    {
        if ($grandTotal > 100) return 'istimewa';
        if ($grandTotal >= 90) return 'baik_sekali';
        if ($grandTotal >= 80) return 'baik';
        if ($grandTotal >= 65) return 'cukup';
        return 'kurang';
    }

    public function getGradeLabelAttribute(): string
    {
        return self::GRADES[$this->grade]['label'] ?? '-';
    }

    public function isLocked(): bool
    {
        return $this->status === 'locked';
    }

    // Cek apakah masih menunggu kadept cross-dept submit kualitatifnya
    public function isWaitingCrossDept(): bool
    {
        $hasCrossAssignment = KpiQualityAssignment::where('employee_id', $this->employee_id)
                                ->where('is_primary', false)
                                ->where('is_active', true)
                                ->exists();

        if (!$hasCrossAssignment) {
            return false; // tidak ada assignment cross-dept, tidak perlu menunggu siapa-siapa
        }

        $crossSubmissionCount = $this->qualityDetails()
                                    ->where('is_final_average', false)
                                    ->count();

        // Kalau assignment cross-dept ada tapi submission baru 1 (cuma dari primary), masih menunggu
        return $crossSubmissionCount < 2;
    }
}