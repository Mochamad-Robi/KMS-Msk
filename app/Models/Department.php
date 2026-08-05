<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $fillable = ['name', 'code', 'parent_id'];

    public function parent()
    {
        return $this->belongsTo(Department::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Department::class, 'parent_id');
    }

        public function hasChildren()
    {
        return $this->children()->exists();
    }

    public function positions()
    {
        return $this->hasMany(Position::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }
}