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
    Schema::create('document_accesses', function (Blueprint $table) {
        $table->id();
        $table->foreignId('document_id')->constrained()->cascadeOnDelete();
        $table->string('accessible_type'); // App\Models\Role atau App\Models\Department
        $table->unsignedBigInteger('accessible_id');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_accesses');
    }
};
