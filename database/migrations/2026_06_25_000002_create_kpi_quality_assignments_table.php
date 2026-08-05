<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Assignment khusus untuk penilaian KUALITATIF — boleh lebih dari 1 kadept per karyawan
        // (beda dengan kpi_assignments yang dipakai untuk kuantitatif, 1 karyawan = 1 kadept dept sendiri)
        Schema::create('kpi_quality_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kadept_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('employee_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('assigned_by')->nullable()->constrained('users')->onDelete('set null');
            $table->boolean('is_primary')->default(true); // true = kadept dept sendiri, false = cross-dept
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['kadept_id', 'employee_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kpi_quality_assignments');
    }
};