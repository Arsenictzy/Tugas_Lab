<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LeaveApplication;
use App\Models\User;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    /**
     * Menampilkan semua aplikasi cuti untuk tujuan pelaporan/audit.
     */
    public function leaveReport(Request $request)
    {
        $query = LeaveApplication::query()->with('user');
        
        // --- Filtering ---
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        
        // --- Sorting ---
        $applications = $query->orderBy('start_date', 'desc')->paginate(20);
        
        $allUsers = User::where('is_active', true)->orderBy('name')->get();

        return view('admin.reports.leave-report', compact('applications', 'allUsers'));
    }
}