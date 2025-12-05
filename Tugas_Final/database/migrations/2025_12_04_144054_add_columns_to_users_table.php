<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // PERBAIKAN: Tambahkan kolom username
            // Pastikan kolom ini ditambahkan setelah kolom 'id' atau 'email'
            // Posisi ini penting, namun karena ini Schema::table, Laravel akan menambahkannya di akhir.
            $table->string('username')->unique()->nullable()->after('email'); 
            
            $table->string('role')->default('user'); // admin, user, leader, hrd
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->string('profile_photo')->nullable();
            $table->date('join_date')->default(now());
            $table->foreignId('division_id')->nullable()->constrained('divisions')->onDelete('set null');
            $table->integer('initial_leave_quota')->default(12);
            $table->boolean('is_active')->default(true);
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['username', 'role', 'phone', 'address', 'profile_photo', 'join_date', 'division_id', 'initial_leave_quota', 'is_active']);
        });
    }
};