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
    Schema::table('documents', function (Blueprint $table) {
        $table->string('type')->default('pdf')->after('category'); // pdf, poster, info, banner
        $table->string('thumbnail_path')->nullable()->after('file_path');
        $table->boolean('show_on_dashboard')->default(false)->after('is_active');
    });
}

public function down(): void
{
    Schema::table('documents', function (Blueprint $table) {
        $table->dropColumn(['type', 'thumbnail_path', 'show_on_dashboard']);
    });
}
};
