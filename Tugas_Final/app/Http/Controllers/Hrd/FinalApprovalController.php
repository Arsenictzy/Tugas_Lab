<?php

namespace App\Http\Controllers\HRD;

use App\Http\Controllers\Controller;
use App\Models\LeaveApplication;
use App\Models\LeaveQuota; // PENTING: Pastikan ini diimpor
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; // PENTING: Import DB Facade

class FinalApprovalController extends Controller
{
    public function index(Request $request)
    {
        $query = LeaveApplication::whereIn('status', ['approved_by_leader', 'pending']);
        
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        
        $applications = $query->with(['user', 'leader'])->orderBy('created_at', 'desc')->paginate(15);
        
        return view('hrd.final-approval.index', compact('applications'));
    }
    
    public function show(LeaveApplication $leaveApplication)
    {
        return view('hrd.final-approval.show', compact('leaveApplication'));
    }
    
    public function approve(Request $request, LeaveApplication $leaveApplication)
    {
        // 1. Cek status cuti
        if (!in_array($leaveApplication->status, ['approved_by_leader', 'pending'])) {
            return redirect()->back()->with('error', 'Cuti tidak dapat disetujui karena statusnya sudah final.');
        }

        $validated = $request->validate([
            'note' => 'nullable|string|max:500',
        ]);
        
        // 2. LOGIKA UTAMA PENGURANGAN KUOTA
        if ($leaveApplication->is_annual) {
            // Memuat ulang relasi user (safety check)
            $user = $leaveApplication->user()->first(); 
            
            if (!$user) {
                 return redirect()->back()->with('error', 'Gagal memproses. Data pengguna tidak ditemukan.');
            }

            // Dapatkan atau buat entri kuota tahun ini
            $quota = LeaveQuota::firstOrCreate(
                ['user_id' => $user->id, 'year' => date('Y')],
                ['total_quota' => $user->initial_leave_quota, 'used_quota' => 0]
            );

            // Periksa apakah kuota yang akan dipotong valid
            if ($quota->used_quota + $leaveApplication->total_days > $quota->total_quota) {
                 return redirect()->back()->with('error', 'Kuota cuti tidak valid. Pengurangan melebihi kuota total.');
            }
            
            // PENTING: Potong kuota di sini dan pastikan berhasil
            // Menggunakan DB::transaction untuk memastikan atomicity
            DB::transaction(function () use ($quota, $leaveApplication) {
                $quota->increment('used_quota', $leaveApplication->total_days);
                // Memastikan objek kuota memuat ulang data terbaru
                $quota->refresh(); 
            });
            
            $remaining = $quota->remaining_quota;
        }
        
        // 3. Update status aplikasi
        $leaveApplication->update([
            'status' => 'approved',
            'hrd_id' => Auth::id(),
            'hrd_note' => $validated['note'] ?? null,
            'hrd_action_at' => now(),
        ]);
        
        return redirect()->route('hrd.approval.index')
            ->with('success', 'Leave application fully approved. Quota has been deducted. Remaining: ' . ($remaining ?? 'N/A') . ' days.');
    }
    
    public function reject(Request $request, LeaveApplication $leaveApplication)
    {
        // 1. Cek status cuti
        if (!in_array($leaveApplication->status, ['approved_by_leader', 'pending'])) {
            return redirect()->back()->with('error', 'Cuti tidak dapat ditolak karena statusnya sudah final.');
        }

        $validated = $request->validate([
            'note' => 'required|string|min:10|max:500',
        ]);
        
        // 2. Logika restore kuota: TIDAK PERLU karena kuota baru dipotong di approve()
        
        // 3. Update status aplikasi
        $leaveApplication->update([
            'status' => 'rejected',
            'hrd_id' => Auth::id(),
            'hrd_note' => $validated['note'],
            'hrd_action_at' => now(),
        ]);
        
        return redirect()->route('hrd.approval.index')
            ->with('success', 'Leave application rejected. Quota status remains unchanged.');
    }
    
    public function bulkAction(Request $request)
    {
        $validated = $request->validate([
            'action' => 'required|in:approve,reject',
            'applications' => 'required|array',
            'applications.*' => 'exists:leave_applications,id',
            'note' => 'nullable|string|max:500',
        ]);
        
        $applications = LeaveApplication::whereIn('id', $validated['applications'])
            ->whereIn('status', ['approved_by_leader', 'pending'])
            ->get();
        
        $hrdId = Auth::id();
        $hrdNote = $validated['note'] ?? null;
        $now = now();
        $count = 0;

        foreach ($applications as $application) {
            
            $user = $application->user()->first();

            // Safety check
            if (!$user) {
                continue; 
            }
            
            // Pastikan aplikasi adalah cuti tahunan
            $isAnnual = $application->is_annual;
            
            if ($validated['action'] === 'approve') {
                
                // LOGIKA PENGURANGAN KUOTA UNTUK BULK ACTION
                if ($isAnnual) {
                    $quota = LeaveQuota::firstOrCreate(
                        ['user_id' => $user->id, 'year' => date('Y')],
                        ['total_quota' => $user->initial_leave_quota, 'used_quota' => 0]
                    );
                    
                    // Potong kuota
                    DB::transaction(function () use ($quota, $application) {
                        $quota->increment('used_quota', $application->total_days);
                        $quota->refresh();
                    });
                }
                
                $application->update([
                    'status' => 'approved',
                    'hrd_id' => $hrdId,
                    'hrd_note' => $hrdNote,
                    'hrd_action_at' => $now,
                ]);
                $count++;
            } else { // reject
                
                // Jika reject, hanya update status.
                
                $application->update([
                    'status' => 'rejected',
                    'hrd_id' => $hrdId,
                    'hrd_note' => $hrdNote,
                    'hrd_action_at' => $now,
                ]);
                $count++;
            }
        }
        
        return redirect()->route('hrd.approval.index')
            ->with('success', $count . ' applications processed. Quotas updated where applicable.');
    }
}