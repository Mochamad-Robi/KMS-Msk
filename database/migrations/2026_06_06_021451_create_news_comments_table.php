<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('news_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('news_id')->constrained('news')->cascadeOnDelete(); // ← ubah di sini
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->text('body');
            $table->integer('likes')->default(0);
            $table->timestamps();
        });

        Schema::table('news', function (Blueprint $table) { // ← ubah di sini
            $table->unsignedInteger('views_count')->default(0)->after('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('news_comments');
        Schema::table('news', function (Blueprint $table) { // ← ubah di sini
            $table->dropColumn('views_count');
        });
    }
};