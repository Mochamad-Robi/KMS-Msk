<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewsComment extends Model
{
    protected $fillable = ['news_id', 'user_id', 'body', 'likes'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function news()
    {
        return $this->belongsTo(News::class);
    }
}