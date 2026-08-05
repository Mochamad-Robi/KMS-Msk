<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    protected $fillable = [
        'title', 'category', 'sub_category', 'content',
        'image_path', 'created_by', 'birthday_user_id',
        'views_count', 'is_active', 'publish_at', 'expire_at',
    ];

    protected $casts = [
        'is_active'  => 'boolean',
        'publish_at' => 'date',
        'expire_at'  => 'date',
    ];

    const CATEGORIES = [
        'pemberitahuan' => 'Pemberitahuan',
        'himbauan'      => 'Himbauan',
        'promosi-umkm'  => 'Promosi UMKM',
        'birthday'      => 'Ulang Tahun',
    ];

    const SUB_CATEGORIES = [
        'food-beverage' => 'Food & Beverage',
        'otomotif'      => 'Otomotif',
        'properti'      => 'Properti',
        'gadget'        => 'Gadget',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function birthdayUser()
    {
        return $this->belongsTo(User::class, 'birthday_user_id');
    }

    public function comments()
    {
        return $this->hasMany(NewsComment::class, 'news_id')->latest();
    }

    public function incrementViews()
    {
        $this->increment('views_count');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)
                     ->where(function ($q) {
                         $q->whereNull('publish_at')
                           ->orWhere('publish_at', '<=', now());
                     })
                     ->where(function ($q) {
                         $q->whereNull('expire_at')
                           ->orWhere('expire_at', '>=', now());
                     });
    }

    public function scopeCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeSubCategory($query, $subCategory)
    {
        return $query->where('sub_category', $subCategory);
    }

    public function subCategoryLabel(): ?string
    {
        return self::SUB_CATEGORIES[$this->sub_category] ?? null;
    }

    public function isBirthday(): bool
    {
        return $this->category === 'birthday';
    }
}