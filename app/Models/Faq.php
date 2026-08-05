<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    protected $fillable = [
        'question', 'answer', 'category',
        'order', 'is_active', 'created_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Scope hanya yang aktif
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}