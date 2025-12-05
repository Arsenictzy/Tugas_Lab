<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder; // Import Builder

class LeaveApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'application_date',
        'start_date',
        'end_date',
        'total_days',
        'reason',
        'doctor_note',
        'address_during_leave',
        'emergency_contact',
        'status',
        'leader_id',
        'leader_note',
        'leader_action_at',
        'hrd_id',
        'hrd_note',
        'hrd_action_at',
        'cancellation_reason',
    ];

    protected $casts = [
        'application_date' => 'date',
        'start_date' => 'date',
        'end_date' => 'date',
        'leader_action_at' => 'datetime',
        'hrd_action_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function leader()
    {
        return $this->belongsTo(User::class, 'leader_id');
    }

    public function hrd()
    {
        return $this->belongsTo(User::class, 'hrd_id');
    }

    public function getIsAnnualAttribute()
    {
        return $this->type === 'annual';
    }

    public function getIsSickAttribute()
    {
        return $this->type === 'sick';
    }

    // --- SCOPES UNTUK NAVIGATION BAR ---

    public function scopePendingForLeader(Builder $query, User $leader): Builder
    {
        // Mendapatkan ID anggota divisi yang dipimpin oleh $leader (termasuk dirinya sendiri jika dia anggota)
        $memberIds = $leader->division ? $leader->division->members->pluck('id')->toArray() : [];
        
        // Memastikan leader tidak memverifikasi cutinya sendiri, kecuali dia satu-satunya anggota divisi (skenario yang tidak biasa)
        $memberIds = array_diff($memberIds, [$leader->id]);

        return $query->whereIn('user_id', $memberIds)
                     ->where('status', 'pending');
    }

    public function scopePendingForHRD(Builder $query): Builder
    {
        // Cuti yang perlu ditinjau HRD adalah cuti yang:
        // 1. Statusnya 'pending' (jika diajukan oleh leader yang otomatis bypass leader approval)
        // 2. Statusnya 'approved_by_leader' (disetujui leader, menunggu final approval HRD)
        return $query->whereIn('status', ['pending', 'approved_by_leader']);
    }

    // --- ACCESSORS LAINNYA ---
    
    public function getStatusBadgeClassAttribute()
    {
        return match($this->status) {
            'pending' => 'bg-yellow-100 text-yellow-800',
            'approved_by_leader' => 'bg-blue-100 text-blue-800',
            'approved' => 'bg-green-100 text-green-800',
            'rejected' => 'bg-red-100 text-red-800',
            'cancelled' => 'bg-gray-100 text-gray-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function getTypeLabelAttribute()
    {
        return $this->is_annual ? 'Cuti Tahunan' : 'Cuti Sakit';
    }

    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            'pending' => 'Menunggu',
            'approved_by_leader' => 'Disetujui Ketua Divisi',
            'approved' => 'Disetujui',
            'rejected' => 'Ditolak',
            'cancelled' => 'Dibatalkan',
            default => 'Unknown',
        };
    }
    
    // Fungsi calculateWorkingDays tidak perlu di sini, sudah di Controller
    
    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function canBeCancelled()
    {
        // Cuti dapat dibatalkan jika statusnya masih menunggu (pending)
        return $this->isPending();
    }
}