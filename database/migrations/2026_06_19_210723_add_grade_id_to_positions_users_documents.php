<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('positions', function (Blueprint $table) {
            $table->foreignId('grade_id')->nullable()->after('department_id')->constrained('grades')->nullOnDelete();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('grade_id')->nullable()->after('role_id')->constrained('grades')->nullOnDelete();
        });

        Schema::table('documents', function (Blueprint $table) {
            $table->foreignId('min_grade_id')->nullable()->after('min_grade')->constrained('grades')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('positions', function (Blueprint $table) {
            $table->dropForeign(['grade_id']);
            $table->dropColumn('grade_id');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['grade_id']);
            $table->dropColumn('grade_id');
        });

        Schema::table('documents', function (Blueprint $table) {
            $table->dropForeign(['min_grade_id']);
            $table->dropColumn('min_grade_id');
        });
    }
};