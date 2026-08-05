<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    protected $fillable = [
        'title', 'content', 'created_by',
        'is_active', 'publish_at', 'expire_at',
    ];

    protected $casts = [
        'is_active'  => 'boolean',
        'publish_at' => 'date',
        'expire_at'  => 'date',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Scope: hanya yang aktif dan belum expired
    public function scopeActive($query)
    {
        return $query->where('is_active', true)
                     ->where('publish_at', '<=', now())
                     ->where(function ($q) {
                         $q->whereNull('expire_at')
                           ->orWhere('expire_at', '>=', now());
                     });
    }
}