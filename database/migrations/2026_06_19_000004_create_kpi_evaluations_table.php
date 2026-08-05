<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kpi_evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kpi_period_id')->constrained('kpi_periods')->onDelete('cascade');
            $table->foreignId('employee_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('evaluated_by')->constrained('users')->onDelete('cascade');

            $table->decimal('total_quant_score', 6, 2)->nullable();
            $table->decimal('total_quality_score', 6, 2)->nullable();
            $table->decimal('penilaian_1', 6, 2)->nullable();
            $table->decimal('total_warning_score', 6, 2)->default(0);
            $table->decimal('penilaian_2', 6, 2)->nullable();
            $table->decimal('total_special_score', 6, 2)->default(0);
            $table->decimal('grand_total', 6, 2)->nullable();
            $table->enum('grade', ['istimewa', 'baik_sekali', 'baik', 'cukup', 'kurang'])->nullable();

            $table->enum('status', ['draft', 'submitted', 'locked'])->default('draft');
            $table->timestamp('submitted_at')->nullable();
            $table->timestamps();

            $table->unique(['kpi_period_id', 'employee_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kpi_evaluations');
    }
};