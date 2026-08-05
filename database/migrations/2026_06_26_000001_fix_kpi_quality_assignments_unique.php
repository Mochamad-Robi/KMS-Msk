<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kpi_quality_assignments', function (Blueprint $table) {
            // Drop FK dulu (index lama terikat ke FK ini, jadi tidak bisa langsung di-drop)
            $table->dropForeign(['kadept_id']);
            $table->dropUnique('kpi_quality_assignments_kadept_id_employee_id_unique');
        });

        Schema::table('kpi_quality_assignments', function (Blueprint $table) {
            // Pasang lagi FK kadept_id (tanpa unique constraint lama)
            $table->foreign('kadept_id')->references('id')->on('users')->onDelete('cascade');

            // Constraint baru yang benar: 1 karyawan hanya boleh punya
            // MAKSIMAL 1 assignment primary dan MAKSIMAL 1 assignment cross-dept
            $table->unique(['employee_id', 'is_primary'], 'kpi_quality_employee_primary_unique');
        });
    }

    public function down(): void
    {
        Schema::table('kpi_quality_assignments', function (Blueprint $table) {
            $table->dropUnique('kpi_quality_employee_primary_unique');
            $table->dropForeign(['kadept_id']);
        });

        Schema::table('kpi_quality_assignments', function (Blueprint $table) {
            $table->foreign('kadept_id')->references('id')->on('users')->onDelete('cascade');
            $table->unique(['kadept_id', 'employee_id'], 'kpi_quality_assignments_kadept_id_employee_id_unique');
        });
    }
};