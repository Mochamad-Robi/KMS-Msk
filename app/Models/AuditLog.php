<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    protected $fillable = [
        'user_id', 'action', 'module',
        'description', 'ip_address',
        'user_agent', 'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Helper static method untuk catat log
    public static function record(string $action, string $module, string $description, array $metadata = []): void
    {
        self::create([
            'user_id'     => auth()->id(),
            'action'      => $action,
            'module'      => $module,
            'description' => $description,
            'ip_address'  => request()->ip(),
            'user_agent'  => request()->userAgent(),
            'metadata'    => $metadata,
        ]);
    }
}