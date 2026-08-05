<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KpiForm extends Model
{
    protected $fillable = [
        'title', 'position_id', 'department_id',
        'form_schema', 'is_active',
    ];

    protected $casts = [
        'form_schema' => 'array',
        'is_active'   => 'boolean',
    ];

    public function position()
    {
        return $this->belongsTo(Position::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function submissions()
    {
        return $this->hasMany(KpiSubmission::class);
    }
}