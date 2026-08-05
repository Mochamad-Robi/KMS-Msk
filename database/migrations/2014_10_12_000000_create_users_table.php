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
    Schema::create('users', function (Blueprint $table) {
        $table->id();
        $table->string('employee_id')->unique();   // ID Card karyawan
        $table->string('name');
        $table->string('email')->unique();
        $table->string('password');
        $table->string('phone')->nullable();
        $table->date('birth_date')->nullable();
        $table->foreignId('department_id')->nullable()->constrained()->nullOnDelete();
        $table->foreignId('position_id')->nullable()->constrained()->nullOnDelete();
        $table->foreignId('role_id')->nullable()->constrained()->nullOnDelete();
        $table->tinyInteger('grade')->default(2);  // 1=SPV/GM, 2=Staff, 3=per dept
        $table->string('two_fa_secret')->nullable();
        $table->boolean('two_fa_enabled')->default(false);
        $table->string('avatar')->nullable();
        $table->boolean('is_active')->default(true);
        $table->timestamp('last_login_at')->nullable();
        $table->rememberToken();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
