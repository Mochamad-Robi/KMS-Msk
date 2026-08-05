<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BirthdayGift extends Model
{
    protected $fillable = [
        'user_id', 'gift_code', 'message',
        'year', 'is_claimed', 'claimed_at',
    ];

    protected $casts = [
        'is_claimed'  => 'boolean',
        'claimed_at'  => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Generate kode unik MSK-2026-XXXX
    public static function generateCode(): string
    {
        do {
            $code = 'MSK-' . now()->year . '-' . strtoupper(substr(md5(uniqid()), 0, 4));
        } while (self::where('gift_code', $code)->exists());

        return $code;
    }
}