<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class DocumentRead extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'document_id', 'user_id', 'read_at',
        'watermark_text', 'ip_address', 'acknowledged_at',
    ];

    protected $casts = [
        'read_at'         => 'datetime',
        'acknowledged_at' => 'datetime',
    ];

    public function document()
    {
        return $this->belongsTo(Document::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}