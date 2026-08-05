<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::create('kpi_submissions', function (Blueprint $table) {
        $table->id();
        $table->foreignId('kpi_form_id')->constrained()->cascadeOnDelete();
        $table->foreignId('user_id')->constrained()->cascadeOnDelete();
        $table->json('form_data');
        $table->tinyInteger('period_month');
        $table->smallInteger('period_year');
        $table->string('status')->default('draft'); // draft, submitted, approved
        $table->timestamps();
    });
}
   /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kpi_submissions');
    }
};
