<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kpi_quality_details', function (Blueprint $table) {
            // Tiap submission kualitatif sekarang terikat ke kadept yang mengisi
            // (1 evaluation bisa punya lebih dari 1 row: 1 dari kadept dept sendiri, 1 dari cross-dept)
            $table->foreignId('submitted_by')->nullable()->after('kpi_evaluation_id')->constrained('users')->onDelete('cascade');

            // Tandai apakah ini hasil akhir (average) atau submission individual kadept
            $table->boolean('is_final_average')->default(false)->after('total_quality_score');
        });
    }

    public function down(): void
    {
        Schema::table('kpi_quality_details', function (Blueprint $table) {
            $table->dropForeign(['submitted_by']);
            $table->dropColumn(['submitted_by', 'is_final_average']);
        });
    }
};