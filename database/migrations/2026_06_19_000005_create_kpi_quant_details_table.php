<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kpi_quant_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kpi_evaluation_id')->constrained('kpi_evaluations')->onDelete('cascade');
            $table->foreignId('kpi_quant_template_id')->constrained('kpi_quant_templates')->onDelete('cascade');
            $table->decimal('target', 12, 2)->nullable();
            $table->decimal('actual', 12, 2)->nullable();
            $table->decimal('achievement_percent', 6, 2)->nullable();
            $table->decimal('score', 6, 2)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kpi_quant_details');
    }
};