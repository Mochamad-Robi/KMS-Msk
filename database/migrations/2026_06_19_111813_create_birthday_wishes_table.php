<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('birthday_wishes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('from_user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('to_user_id')->constrained('users')->cascadeOnDelete();
            $table->string('message', 500);
            $table->year('year');
            $table->timestamps();

            $table->unique(['from_user_id', 'to_user_id', 'year']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('birthday_wishes');
    }
};