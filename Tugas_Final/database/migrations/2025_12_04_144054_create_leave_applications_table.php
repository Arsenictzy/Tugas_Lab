<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leave_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->enum('type', ['annual', 'sick']);
            $table->date('application_date');
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('total_days');
            $table->text('reason');
            $table->string('doctor_note')->nullable(); // only for sick leave
            $table->string('address_during_leave');
            $table->string('emergency_contact');
            $table->enum('status', ['pending', 'approved_by_leader', 'approved', 'rejected', 'cancelled'])->default('pending');
            $table->foreignId('leader_id')->nullable()->constrained('users')->onDelete('set null');
            $table->text('leader_note')->nullable();
            $table->timestamp('leader_action_at')->nullable();
            $table->foreignId('hrd_id')->nullable()->constrained('users')->onDelete('set null');
            $table->text('hrd_note')->nullable();
            $table->timestamp('hrd_action_at')->nullable();
            $table->text('cancellation_reason')->nullable();
            $table->timestamps();
            
            $table->index(['status', 'type']);
            $table->index(['start_date', 'end_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leave_applications');
    }
};