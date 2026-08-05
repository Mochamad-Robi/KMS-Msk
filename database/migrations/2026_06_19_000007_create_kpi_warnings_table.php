<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kpi_warnings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kpi_evaluation_id')->constrained('kpi_evaluations')->onDelete('cascade');
            $table->enum('type', ['sp1', 'sp2', 'sp3']);
            $table->boolean('is_present')->default(false);
            $table->decimal('weight_percent', 5, 2);
            $table->decimal('score', 6, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kpi_warnings');
    }
};