<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('grade');
        });

        Schema::table('documents', function (Blueprint $table) {
            $table->dropColumn('min_grade');
        });

        Schema::table('positions', function (Blueprint $table) {
            $table->dropColumn('grade_level');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->tinyInteger('grade')->nullable();
        });

        Schema::table('documents', function (Blueprint $table) {
            $table->tinyInteger('min_grade')->nullable();
        });

        Schema::table('positions', function (Blueprint $table) {
            $table->tinyInteger('grade_level')->nullable();
        });
    }
};