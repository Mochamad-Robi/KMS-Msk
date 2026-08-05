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
    Schema::create('birthday_gifts', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->cascadeOnDelete();
        $table->string('gift_code')->unique(); // MSK-2026-XXXX
        $table->text('message')->nullable();   // pesan custom dari admin
        $table->integer('year');               // tahun pemberian
        $table->boolean('is_claimed')->default(false);
        $table->timestamp('claimed_at')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('birthday_gifts');
    }
};
