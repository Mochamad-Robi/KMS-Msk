<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\LoginAttempt;
use App\Models\Grade;

class User extends Authenticatable
{
    use Notifiable;

  protected $fillable = [
    'employee_id', 'name', 'email', 'password',
    'phone', 'birth_date', 'department_id', 'position_id',
    'role_id', 'grade_id', 'two_fa_secret', 'two_fa_enabled',
    'avatar', 'is_active', 'locale', 'last_login_at',
];


    protected $hidden = ['password', 'remember_token', 'two_fa_secret'];

    protected $casts = [
        'birth_date'      => 'date',
        'two_fa_enabled'  => 'boolean',
        'is_active'       => 'boolean',
        'last_login_at'   => 'datetime',
    ];

    // Relasi
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function position()
    {
        return $this->belongsTo(Position::class);
    }

    public function documentReads()
    {
        return $this->hasMany(DocumentRead::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function kpiSubmissions()
    {
        return $this->hasMany(KpiSubmission::class);
    }

    // ===== Relasi KPI (kuantitatif — assignment lama, 1 kadept per karyawan) =====

    // Sebagai Kadept: daftar assignment karyawan yang dia nilai (KUANTITATIF, dept sendiri saja)
    public function kpiAssignmentsAsKadept()
    {
        return $this->hasMany(KpiAssignment::class, 'kadept_id');
    }

    // Sebagai Karyawan: daftar assignment dirinya dinilai oleh siapa (KUANTITATIF)
    public function kpiAssignmentsAsEmployee()
    {
        return $this->hasMany(KpiAssignment::class, 'employee_id');
    }

    // Sebagai Karyawan: semua hasil evaluasi KPI dirinya
    public function kpiEvaluationsReceived()
    {
        return $this->hasMany(KpiEvaluation::class, 'employee_id');
    }

    // Sebagai Kadept: semua evaluasi KPI yang sudah/akan dia isi
    public function kpiEvaluationsGiven()
    {
        return $this->hasMany(KpiEvaluation::class, 'evaluated_by');
    }

    // ===== Relasi KPI (kualitatif — assignment baru, bisa multi-kadept per karyawan) =====

    // Sebagai Kadept: daftar assignment KUALITATIF (bisa dept sendiri / cross-dept)
    public function kpiQualityAssignmentsAsKadept()
    {
        return $this->hasMany(KpiQualityAssignment::class, 'kadept_id');
    }

    // Sebagai Karyawan: daftar assignment KUALITATIF dirinya dinilai oleh siapa saja
    public function kpiQualityAssignmentsAsEmployee()
    {
        return $this->hasMany(KpiQualityAssignment::class, 'employee_id');
    }

    // Helper: daftar karyawan yang harus dinilai KUALITATIF oleh user ini
    public function assignedQualityEmployees()
    {
        if (!$this->isKadept()) {
            return collect();
        }

        $employeeIds = $this->kpiQualityAssignmentsAsKadept()
                            ->where('is_active', true)
                            ->pluck('employee_id');

        return User::whereIn('id', $employeeIds)->get();
    }

    // Helper: cek apakah user ini adalah kadept CROSS-DEPT untuk karyawan tertentu
    public function isCrossDeptKadeptFor($employeeId): bool
    {
        return $this->kpiQualityAssignmentsAsKadept()
                    ->where('employee_id', $employeeId)
                    ->where('is_primary', false)
                    ->where('is_active', true)
                    ->exists();
    }

    // Helper: cek apakah user ini adalah kadept UTAMA (dept sendiri) untuk karyawan tertentu
    public function isPrimaryKadeptFor($employeeId): bool
    {
        return $this->kpiQualityAssignmentsAsKadept()
                    ->where('employee_id', $employeeId)
                    ->where('is_primary', true)
                    ->where('is_active', true)
                    ->exists();
    }

    // Helper: cek role
    public function isAdmin(): bool
    {
        return $this->role?->slug === 'admin';
    }

    public function isSuperUser(): bool
    {
        return $this->role?->slug === 'super-user';
    }

    public function isUser(): bool
    {
        return $this->role?->slug === 'user';
    }

    public function isKadept(): bool
    {
        return $this->role?->slug === 'kadept';
    }

    // Helper: daftar karyawan aktif yang harus dinilai oleh user ini (jika dia Kadept, KUANTITATIF)
    public function assignedEmployees()
    {
        if (!$this->isKadept()) {
            return collect();
        }

        return User::whereIn('id', $this->kpiAssignmentsAsKadept()
                ->where('is_active', true)
                ->pluck('employee_id')
            )->get();
    }

    // Helper: cek apakah user ini punya assignment KPI aktif (untuk tampil/sembunyi menu KPI)
    public function hasKpiAssignments(): bool
    {
        return $this->kpiAssignmentsAsKadept()->where('is_active', true)->exists()
            || $this->kpiQualityAssignmentsAsKadept()->where('is_active', true)->exists();
    }

    // Helper: cek ulang tahun hari ini
    public function isBirthdayToday(): bool
    {
        if (!$this->birth_date) return false;

        return $this->birth_date->format('d-m') === now()->format('d-m');
    }

    // Helper: cek akses dokumen
   public function canAccessDocument(Document $document): bool
{
    if ($this->isAdmin() || $this->isSuperUser()) return true;

   // Layer 1: Grade (skip kalau min_grade_id null = semua grade boleh)
    if ($document->min_grade_id) {
        if ($this->grade_id) {
            if ($this->grade->level > $document->minGrade->level) {
                return false;
            }
        }
    }

    // Layer 2: Department (null = semua dept boleh)
    if ($document->department_id && $document->department_id !== $this->department_id) {
        return false;
    }

    // Layer 3: Jabatan (null = semua jabatan dalam dept itu boleh)
    if ($document->position_id && $document->position_id !== $this->position_id) {
        return false;
    }

    return true;
}

    // Unread notifications count
    public function unreadNotificationsCount(): int
    {
        return $this->notifications()->where('is_read', false)->count();
    }

    public function bookmarks()
{
    return $this->hasMany(DocumentBookmark::class);
}

public function loginAttempts()
{
    return $this->hasMany(LoginAttempt::class);
}

public function grade(): \Illuminate\Database\Eloquent\Relations\BelongsTo
{
    return $this->belongsTo(Grade::class);
}
}