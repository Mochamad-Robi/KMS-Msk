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
    Schema::create('documents', function (Blueprint $table) {
        $table->id();
        $table->string('title');
        $table->string('category'); // policy, roles, employee-info, knowledge-base
        $table->string('file_path');
        $table->string('file_hash')->nullable(); // untuk deteksi perubahan file
        $table->foreignId('uploaded_by')->constrained('users')->cascadeOnDelete();
        $table->foreignId('department_id')->nullable()->constrained()->nullOnDelete();
        $table->tinyInteger('min_grade')->default(2); // grade minimum yang bisa akses
        $table->text('description')->nullable();
        $table->boolean('is_active')->default(true);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
