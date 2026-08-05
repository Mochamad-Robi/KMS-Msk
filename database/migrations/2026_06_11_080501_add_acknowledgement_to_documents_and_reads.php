<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tambah kolom di documents
        Schema::table('documents', function (Blueprint $table) {
            $table->boolean('requires_acknowledgement')->default(false)->after('is_active');
        });

        // Tambah kolom di document_reads
        Schema::table('document_reads', function (Blueprint $table) {
            $table->timestamp('acknowledged_at')->nullable()->after('read_at');
        });
    }

    public function down(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->dropColumn('requires_acknowledgement');
        });

        Schema::table('document_reads', function (Blueprint $table) {
            $table->dropColumn('acknowledged_at');
        });
    }
};