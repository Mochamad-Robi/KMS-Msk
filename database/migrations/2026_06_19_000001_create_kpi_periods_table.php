<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kpi_periods', function (Blueprint $table) {
            $table->id();
            $table->integer('year');
            $table->enum('quartal', ['Q1', 'Q2', 'Q3', 'Q4']);
            $table->date('start_date');
            $table->date('end_date');
            $table->boolean('is_open')->default(false);
            $table->boolean('notified')->default(false);
            $table->timestamps();

            $table->unique(['year', 'quartal']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kpi_periods');
    }
};