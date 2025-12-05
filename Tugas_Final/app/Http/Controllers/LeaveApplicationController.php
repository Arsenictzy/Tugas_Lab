<?php

namespace App\Http\Controllers;

use App\Models\LeaveApplication;
use App\Models\LeaveQuota;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class LeaveApplicationController extends Controller
{
    public function index(Request $request)
    {
        $query = Auth::user()->leaveApplications();
        
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('start_date')) {
            $query->where('start_date', '>=', $request->start_date);
        }
        
        if ($request->filled('end_date')) {
            $query->where('end_date', '<=', $request->end_date);
        }
        
        $applications = $query->orderBy('created_at', 'desc')->paginate(15);
        
        return view('leave-applications.index', compact('applications'));
    }
    
    public function create()
    {
        $user = Auth::user();
        $currentYearQuota = $user->currentYearQuota();
        // Fallback ke initial_leave_quota jika kuota tahun ini belum ada
        $remainingQuota = $currentYearQuota ? $currentYearQuota->remaining_quota : $user->initial_leave_quota;
        
        return view('leave-applications.create', compact('remainingQuota'));
    }
    
    public function store(Request $request)
    {
        $user = Auth::user();
        $today = Carbon::today();
        
        $validated = $request->validate([
            'type' => 'required|in:annual,sick',
            'start_date' => 'required|date|after_or_equal:' . $today->format('Y-m-d'), 
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string|min:10',
            'doctor_note' => 'required_if:type,sick|nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'address_during_leave' => 'required|string',
            'emergency_contact' => 'required|string',
        ]);

        $totalDays = $this->calculateWorkingDays($validated['start_date'], $validated['end_date']);
        $validated['total_days'] = $totalDays;

        // Validasi Cuti Tahunan: Harus 3 hari di muka
        if ($validated['type'] === 'annual') {
            $minAnnualDate = $today->copy()->addDays(3);
            if (Carbon::parse($validated['start_date'])->lt($minAnnualDate)) {
                 return redirect()->back()
                    ->withInput()
                    ->with('error', 'Cuti Tahunan harus diajukan setidaknya 3 hari kerja sebelum tanggal mulai cuti.');
            }

            $currentYearQuota = $user->currentYearQuota();
            // PENTING: Mendefinisikan kuota tersisa dengan fallback
            $remainingQuota = $currentYearQuota ? $currentYearQuota->remaining_quota : $user->initial_leave_quota;
            
            // Periksa Kuota
            if ($totalDays > $remainingQuota) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Insufficient annual leave quota. Remaining: ' . $remainingQuota . ' days.');
            }
        }
        
        // Check for overlapping approved/pending leaves
        $overlap = LeaveApplication::where('user_id', $user->id)
            ->whereIn('status', ['pending', 'approved_by_leader', 'approved']) 
            ->where(function($query) use ($validated) {
                $query->where('start_date', '<=', $validated['end_date'])
                      ->where('end_date', '>=', $validated['start_date']);
            })
            ->exists();
            
        if ($overlap) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'You already have an existing leave application (pending or approved) during this period.');
        }

        // Upload Doctor's Note
        if ($request->hasFile('doctor_note')) {
            $path = $request->file('doctor_note')->store('doctor-notes', 'public');
            $validated['doctor_note'] = $path;
        }

        $validated['application_date'] = Carbon::today();
        $validated['user_id'] = $user->id;
        
        $status = 'pending';
        $leader_id = null;
        $leader_action_at = null;

        // Self-approval logic for Leaders
        if ($user->isLeader() && $user->division) {
            $status = 'approved_by_leader'; 
            $leader_id = $user->id;
            $leader_action_at = now();
        }

        $application = LeaveApplication::create(array_merge($validated, [
            'status' => $status,
            'leader_id' => $leader_id,
            'leader_action_at' => $leader_action_at,
        ]));
        
        // Pesan/Kurangi kuota cuti tahunan di muka (saat store)
        if ($validated['type'] === 'annual') {
             $currentYearQuota = $user->currentYearQuota();

             // Jika belum ada kuota, buat kuota baru dengan pengurangan
             if (!$currentYearQuota) {
                 LeaveQuota::create([
                    'user_id' => $user->id,
                    'year' => date('Y'),
                    'total_quota' => $user->initial_leave_quota,
                    'used_quota' => $totalDays,
                ]);
             } else {
                 // Jika sudah ada, tambahkan ke kuota terpakai
                 $currentYearQuota->increment('used_quota', $totalDays);
             }
        }
        
        return redirect()->route('leave-applications.index')
            ->with('success', 'Leave application submitted successfully. Current status: ' . $application->status_label . '.');
    }
    
    public function show(LeaveApplication $leaveApplication)
    {
        $this->authorize('view', $leaveApplication);
        
        return view('leave-applications.show', compact('leaveApplication'));
    }
    
    public function cancel(Request $request, LeaveApplication $leaveApplication)
    {
        $this->authorize('cancel', $leaveApplication);
        
        $validated = $request->validate([
            'cancellation_reason' => 'required|string|min:10',
        ]);
        
        $leaveApplication->update([
            'status' => 'cancelled',
            'cancellation_reason' => $validated['cancellation_reason'],
        ]);
        
        // Restore quota jika cuti tahunan
        if ($leaveApplication->is_annual && $leaveApplication->status !== 'rejected' && $leaveApplication->total_days > 0) {
            $quota = $leaveApplication->user->currentYearQuota();
            if ($quota) {
                $decrementAmount = min($leaveApplication->total_days, $quota->used_quota);
                if ($decrementAmount > 0) {
                    $quota->decrement('used_quota', $decrementAmount);
                }
            }
        }
        
        return redirect()->route('leave-applications.index')
            ->with('success', 'Leave application cancelled successfully. Quota restored.');
    }
    
    private function calculateWorkingDays($startDate, $endDate)
    {
        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);
        $totalDays = 0;
        
        for ($date = $start; $date->lte($end); $date->addDay()) {
            if ($date->isWeekday()) {
                $totalDays++;
            }
        }
        
        return $totalDays;
    }
}