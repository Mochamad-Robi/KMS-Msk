<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BirthdayWish extends Model
{
    protected $fillable = [
        'from_user_id',
        'to_user_id',
        'message',
        'year',
    ];

    public function sender()
    {
        return $this->belongsTo(User::class, 'from_user_id');
    }

    public function recipient()
    {
        return $this->belongsTo(User::class, 'to_user_id');
    }

    public function scopeForYear($query, $year)
    {
        return $query->where('year', $year);
    }

    public static function hasUserSentWish(int $fromUserId, int $toUserId, int $year): bool
    {
        return self::where('from_user_id', $fromUserId)
                    ->where('to_user_id', $toUserId)
                    ->where('year', $year)
                    ->exists();
    }
}