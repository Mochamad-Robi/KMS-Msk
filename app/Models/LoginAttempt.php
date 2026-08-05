<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoginAttempt extends Model
{
    protected $fillable = [
        'user_id', 'employee_id', 'ip_address',
        'is_success', 'user_agent',
    ];

    protected $casts = [
        'is_success' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}