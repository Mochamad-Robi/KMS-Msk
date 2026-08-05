<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KpiSubmission extends Model
{
    protected $fillable = [
        'kpi_form_id', 'user_id', 'form_data',
        'period_month', 'period_year', 'status',
    ];

    protected $casts = [
        'form_data' => 'array',
    ];

    public function form()
    {
        return $this->belongsTo(KpiForm::class, 'kpi_form_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}