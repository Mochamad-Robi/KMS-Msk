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
    Schema::create('audit_logs', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
        $table->string('action');         // login, logout, view_document, upload_document, dll
        $table->string('module');         // auth, document, news, kpi, user, dll
        $table->text('description')->nullable();
        $table->string('ip_address')->nullable();
        $table->string('user_agent')->nullable();
        $table->json('metadata')->nullable(); // data tambahan (id dokumen, judul, dll)
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
