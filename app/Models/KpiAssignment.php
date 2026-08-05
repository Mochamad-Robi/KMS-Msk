<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KpiAssignment extends Model
{
    protected $fillable = [
        'kadept_id', 'employee_id', 'assigned_by', 'is_cross_department', 'is_active',
    ];

    protected $casts = [
        'is_cross_department' => 'boolean',
        'is_active'            => 'boolean',
    ];

    public function kadept(): BelongsTo
    {
        return $this->belongsTo(User::class, 'kadept_id');
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'employee_id');
    }

    public function assignedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }
}