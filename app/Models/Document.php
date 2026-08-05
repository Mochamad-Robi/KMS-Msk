<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Models\Grade;

class Document extends Model
{
    protected $fillable = [
    'title', 'category', 'sub_category', 'type', 'file_path', 'thumbnail_path',
    'file_hash', 'uploaded_by', 'department_id', 'position_id',
    'min_grade_id', 'description', 'is_active', 'show_on_dashboard',
    'requires_acknowledgement',
];

    const SUB_CATEGORIES_ROLES = [
    'struktur-organisasi' => 'Struktur Organisasi',
    'jobdesk'             => 'Job desc',
];

    protected $casts = [
        'is_active'                  => 'boolean',
        'show_on_dashboard'          => 'boolean',
        'requires_acknowledgement'   => 'boolean',
    ];

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function position()
    {
        return $this->belongsTo(Position::class);
    }

    public function minGrade()
    {
        return $this->belongsTo(Grade::class, 'min_grade_id');
    }

    public function reads()
    {
        return $this->hasMany(DocumentRead::class);
    }

    public function accesses()
    {
        return $this->hasMany(DocumentAccess::class);
    }

    public function bookmarks()
    {
        return $this->hasMany(DocumentBookmark::class);
    }

    public function isReadBy(User $user): bool
    {
        return $this->reads()->where('user_id', $user->id)->exists();
    }

    public function isAcknowledgedBy(User $user): bool
    {
        return $this->reads()
            ->where('user_id', $user->id)
            ->whereNotNull('acknowledged_at')
            ->exists();
    }

    public function acknowledgedCount(): int
    {
        return $this->reads()->whereNotNull('acknowledged_at')->count();
    }

    public function isBookmarkedBy(User $user): bool
    {
        return $this->bookmarks()->where('user_id', $user->id)->exists();
    }
}