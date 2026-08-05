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
    Schema::create('news', function (Blueprint $table) {
        $table->id();
        $table->string('title');
        $table->string('category'); // pemberitahuan, poster, himbauan, promosi-umkm
        $table->text('content')->nullable();
        $table->string('image_path')->nullable();
        $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
        $table->boolean('is_active')->default(true);
        $table->date('publish_at')->nullable();
        $table->date('expire_at')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('news');
    }
};
