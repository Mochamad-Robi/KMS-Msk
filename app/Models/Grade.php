<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    protected $fillable = [
        'name',
        'code',
        'level',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'level'     => 'integer',
    ];

    public function positions()
    {
        return $this->hasMany(Position::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function documents()
    {
        return $this->hasMany(Document::class, 'min_grade_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('level');
    }

    // Label gabungan, misal "GM — General Manager"
    public function label(): string
    {
        return $this->code ? "{$this->code} — {$this->name}" : $this->name;
    }
}