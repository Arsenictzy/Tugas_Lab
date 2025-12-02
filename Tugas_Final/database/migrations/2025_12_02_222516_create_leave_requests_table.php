<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leave_requests', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->enum('leave_type', ['tahunan', 'sakit']);
            $table->date('request_date');
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('total_days');
            $table->text('reason');
            $table->string('address_during_leave')->nullable();
            $table->string('emergency_contact');
            $table->string('medical_certificate')->nullable();
            $table->enum('status', ['pending', 'approved_by_leader', 'approved', 'rejected', 'cancelled'])->default('pending');
            $table->text('leader_note')->nullable();
            $table->text('hr_note')->nullable();
            $table->integer('approved_by_leader')->nullable();
            $table->dateTime('leader_approved_at')->nullable();
            $table->integer('approved_by_hr')->nullable();
            $table->dateTime('hr_approved_at')->nullable();
            $table->text('cancellation_reason')->nullable();
            $table->dateTime('cancelled_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leave_requests');
    }
};