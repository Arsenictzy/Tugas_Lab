<?php

namespace App\Http\Controllers\Leader;

use App\Http\Controllers\Controller;
use App\Models\LeaveApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeaveVerificationController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        
        if (!$user->division) {
            return view('leader.leave-verification.index', ['applications' => collect()]);
        }
        
        $query = LeaveApplication::whereIn('user_id', $user->division->members->pluck('id'))
            ->where('status', 'pending');
        
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        
        $applications = $query->with('user')->orderBy('created_at', 'desc')->paginate(15);
        
        return view('leader.leave-verification.index', compact('applications'));
    }
    
    public function show(LeaveApplication $leaveApplication)
    {
        $this->authorize('verify', $leaveApplication);
        
        return view('leader.leave-verification.show', compact('leaveApplication'));
    }
    
    public function approve(Request $request, LeaveApplication $leaveApplication)
    {
        $this->authorize('verify', $leaveApplication);
        
        $validated = $request->validate([
            'note' => 'nullable|string|max:500',
        ]);
        
        $leaveApplication->update([
            'status' => 'approved_by_leader',
            'leader_id' => Auth::id(),
            'leader_note' => $validated['note'] ?? null,
            'leader_action_at' => now(),
        ]);
        
        return redirect()->route('leader.verification.index')
            ->with('success', 'Leave application approved.');
    }
    
    public function reject(Request $request, LeaveApplication $leaveApplication)
    {
        $this->authorize('verify', $leaveApplication);
        
        $validated = $request->validate([
            'note' => 'required|string|min:10|max:500',
        ]);
        
        $leaveApplication->update([
            'status' => 'rejected',
            'leader_id' => Auth::id(),
            'leader_note' => $validated['note'],
            'leader_action_at' => now(),
        ]);
        
        // Restore quota if annual leave
        if ($leaveApplication->is_annual) {
            $quota = $leaveApplication->user->currentYearQuota();
            if ($quota) {
                $quota->decrement('used_quota', $leaveApplication->total_days);
            }
        }
        
        return redirect()->route('leader.verification.index')
            ->with('success', 'Leave application rejected.');
    }
}