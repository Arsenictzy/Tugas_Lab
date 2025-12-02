<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('username')->unique();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->enum('role', ['admin', 'karyawan', 'ketua_divisi', 'hrd'])->default('karyawan');
            $table->string('full_name');
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->date('join_date');
            $table->integer('leave_quota')->default(12);
            $table->string('profile_photo')->nullable();
            $table->integer('division_id')->nullable();
            $table->string('emergency_contact')->nullable();
            $table->boolean('is_active')->default(true);
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
