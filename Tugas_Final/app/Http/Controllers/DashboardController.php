<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Division;
use App\Models\LeaveApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        if ($user->role === 'admin') {
            return $this->adminDashboard();
        } elseif ($user->role === 'hrd') {
            return $this->hrdDashboard();
        } elseif ($user->role === 'leader') {
            return $this->leaderDashboard();
        } else {
            return $this->userDashboard();
        }
    }

    private function userDashboard()
    {
        $user = Auth::user();
        
        $remainingQuota = $user->annual_leave_quota ?? 12;
        $sickLeaves = LeaveApplication::where('user_id', $user->id)
            ->where('type', 'sick')
            ->where('status', 'approved')
            ->count();
        $totalApplications = LeaveApplication::where('user_id', $user->id)->count();
        $recentLeaves = LeaveApplication::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
        $division = $user->division;

        return view('dashboard.user', compact(
            'remainingQuota',
            'sickLeaves',
            'totalApplications',
            'recentLeaves',
            'division'
        ));
    }

    private function leaderDashboard()
    {
        $user = Auth::user();
        $division = $user->division;

        if (!$division) {
            // Jika leader tidak punya divisi
            return view('dashboard.leader', compact('division'));
        }

        // Ambil semua anggota divisi
        $divisionMembers = User::where('division_id', $division->id)->get();
        
        // Hitung total pengajuan di divisi ini
        $memberIds = $divisionMembers->pluck('id');
        $totalApplications = LeaveApplication::whereIn('user_id', $memberIds)->count();
        
        // Hitung pending verifications (status pending)
        $pendingVerifications = LeaveApplication::whereIn('user_id', $memberIds)
            ->where('status', 'pending')
            ->count();
        
        // Hitung anggota dengan pending leaves
        $membersWithPending = User::where('division_id', $division->id)
            ->withCount(['leaveApplications as pending_leaves_count' => function ($query) {
                $query->where('status', 'pending');
            }])
            ->get()
            ->filter(function ($member) {
                return $member->pending_leaves_count > 0;
            });

        // Anggota yang sedang cuti minggu ini
        $today = Carbon::today();
        $weekStart = $today->startOfWeek()->format('Y-m-d');
        $weekEnd = $today->endOfWeek()->format('Y-m-d');
        
        $onLeaveThisWeek = LeaveApplication::whereIn('user_id', $memberIds)
            ->where('status', 'approved')
            ->whereDate('start_date', '<=', $weekEnd)
            ->whereDate('end_date', '>=', $weekStart)
            ->with('user')
            ->get();

        return view('dashboard.leader', compact(
            'division',
            'divisionMembers',
            'totalApplications',
            'pendingVerifications',
            'membersWithPending',
            'onLeaveThisWeek'
        ));
    }

    private function hrdDashboard()
    {
        $user = Auth::user();
        
        // Hitung pending approvals (status approved_by_leader)
        $pendingApprovals = LeaveApplication::where('status', 'approved_by_leader')->count();
        
        // Hitung pengajuan bulan ini
        $monthlyLeaves = LeaveApplication::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
        
        // Karyawan yang sedang cuti bulan ini
        $today = Carbon::today();
        $monthStart = $today->startOfMonth()->format('Y-m-d');
        $monthEnd = $today->endOfMonth()->format('Y-m-d');
        
        $onLeaveThisMonth = LeaveApplication::where('status', 'approved')
            ->whereDate('start_date', '<=', $monthEnd)
            ->whereDate('end_date', '>=', $monthStart)
            ->with('user')
            ->get();

        // Data divisi
        $divisions = Division::withCount('members')->get();

        return view('dashboard.hrd', compact(
            'pendingApprovals',
            'monthlyLeaves',
            'onLeaveThisMonth',
            'divisions'
        ));
    }

    private function adminDashboard()
    {
        $user = Auth::user();
        
        // Karyawan aktif
        $activeUsers = User::where('is_active', true)->count();
        
        // Pengajuan bulan ini
        $monthlyLeaves = LeaveApplication::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
        
        // Pending approvals (approved_by_leader)
        $pendingApprovals = LeaveApplication::where('status', 'approved_by_leader')->count();
        
        // Total divisi
        $totalDivisions = Division::count();
        
        // Karyawan baru (< 1 tahun)
        $oneYearAgo = Carbon::now()->subYear();
        $newEmployees = User::where('join_date', '>=', $oneYearAgo)
            ->orderBy('join_date', 'desc')
            ->take(10)
            ->get();
        
        // Pengajuan tertunda
        $pendingLeaves = LeaveApplication::whereIn('status', ['pending', 'approved_by_leader'])
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        return view('dashboard.admin', compact(
            'activeUsers',
            'monthlyLeaves',
            'pendingApprovals',
            'totalDivisions',
            'newEmployees',
            'pendingLeaves'
        ));
    }
}