<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('grades', function (Blueprint $table) {
            $table->id();
            $table->string('name');           // contoh: "General Manager", "Kepala Departemen", "Supervisor"
            $table->string('code')->nullable(); // contoh singkatan: "GM", "Kadept", "SPV" (opsional, buat tampilan ringkas)
            $table->unsignedTinyInteger('level'); // urutan tingkatan: 1 = paling tinggi, makin besar makin rendah
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique('level');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('grades');
    }
};