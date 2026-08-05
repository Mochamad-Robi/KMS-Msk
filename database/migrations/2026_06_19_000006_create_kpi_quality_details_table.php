<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kpi_quality_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kpi_evaluation_id')->constrained('kpi_evaluations')->onDelete('cascade');

            $table->integer('kehadiran_target')->nullable();
            $table->integer('kehadiran_actual')->nullable();
            $table->decimal('kehadiran_achievement', 6, 2)->nullable();

            $table->decimal('integritas', 5, 2)->nullable();
            $table->decimal('kekeluargaan', 5, 2)->nullable();
            $table->decimal('handal', 5, 2)->nullable();
            $table->decimal('loyalitas', 5, 2)->nullable();
            $table->decimal('amanah', 5, 2)->nullable();
            $table->decimal('saling_menghargai', 5, 2)->nullable();

            $table->decimal('core_value_average', 5, 2)->nullable();
            $table->decimal('total_quality_score', 6, 2)->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kpi_quality_details');
    }
};