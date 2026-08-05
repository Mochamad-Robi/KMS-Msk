<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Grade;

class Position extends Model
{
    protected $fillable = ['name', 'department_id', 'grade_id'];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function kpiForms()
    {
        return $this->hasMany(KpiForm::class);
    }

    public function grade()
    {
        return $this->belongsTo(Grade::class);
    }
}