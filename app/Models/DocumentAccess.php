<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentAccess extends Model
{
    protected $fillable = ['document_id', 'accessible_type', 'accessible_id'];

    public function document()
    {
        return $this->belongsTo(Document::class);
    }

    public function accessible()
    {
        return $this->morphTo();
    }
}