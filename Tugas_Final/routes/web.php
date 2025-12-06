<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LeaveApplicationController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\DivisionController as AdminDivisionController;
use App\Http\Controllers\Admin\ReportController as AdminReportController; // Tambahkan import ReportController
use App\Http\Controllers\Leader\LeaveVerificationController;
use App\Http\Controllers\HRD\FinalApprovalController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Grup rute yang memerlukan autentikasi dan middleware 'user' (role apapun)
Route::middleware(['auth', 'user'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Leave Applications (Standard User Access)
    Route::prefix('leave-applications')->name('leave-applications.')->group(function () {
        Route::get('/', [LeaveApplicationController::class, 'index'])->name('index');
        Route::get('/create', [LeaveApplicationController::class, 'create'])->name('create');
        Route::post('/', [LeaveApplicationController::class, 'store'])->name('store');
        Route::get('/{leaveApplication}', [LeaveApplicationController::class, 'show'])->name('show');
        Route::post('/{leaveApplication}/cancel', [LeaveApplicationController::class, 'cancel'])->name('cancel');
    });
    
    // Leader Routes
    Route::middleware('leader')->prefix('leader')->name('leader.')->group(function () {
        Route::prefix('verification')->name('verification.')->group(function () {
            Route::get('/', [LeaveVerificationController::class, 'index'])->name('index');
            Route::get('/{leaveApplication}', [LeaveVerificationController::class, 'show'])->name('show');
            Route::post('/{leaveApplication}/approve', [LeaveVerificationController::class, 'approve'])->name('approve');
            Route::post('/{leaveApplication}/reject', [LeaveVerificationController::class, 'reject'])->name('reject');
        });
    });
    
    // HRD Routes
    Route::middleware('hrd')->prefix('hrd')->name('hrd.')->group(function () {
        Route::prefix('approval')->name('approval.')->group(function () {
            Route::get('/', [FinalApprovalController::class, 'index'])->name('index');
            Route::get('/{leaveApplication}', [FinalApprovalController::class, 'show'])->name('show');
            Route::post('/{leaveApplication}/approve', [FinalApprovalController::class, 'approve'])->name('approve');
            Route::post('/{leaveApplication}/reject', [FinalApprovalController::class, 'reject'])->name('reject');
            Route::post('/bulk-action', [FinalApprovalController::class, 'bulkAction'])->name('bulk-action');
        });
    });
    
    // Admin Routes
    Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
        
        // 1. User Management (Routes now have name prefix 'admin.users.')
        Route::prefix('users')->name('users.')->group(function () {
            Route::get('/', [AdminUserController::class, 'index'])->name('index');
            Route::get('/create', [AdminUserController::class, 'create'])->name('create');
            Route::post('/', [AdminUserController::class, 'store'])->name('store');
            Route::get('/{user}/edit', [AdminUserController::class, 'edit'])->name('edit');
            Route::put('/{user}', [AdminUserController::class, 'update'])->name('update');
            Route::delete('/{user}', [AdminUserController::class, 'destroy'])->name('destroy');
        });
        
        // 2. Division Management (Routes now have name prefix 'admin.divisions.')
        Route::prefix('divisions')->name('divisions.')->group(function () {
            Route::get('/', [AdminDivisionController::class, 'index'])->name('index');
            Route::get('/create', [AdminDivisionController::class, 'create'])->name('create');
            Route::post('/', [AdminDivisionController::class, 'store'])->name('store');
            Route::get('/{division}/edit', [AdminDivisionController::class, 'edit'])->name('edit');
            Route::put('/{division}', [AdminDivisionController::class, 'update'])->name('update');
            Route::delete('/{division}', [AdminDivisionController::class, 'destroy'])->name('destroy');
            Route::get('/{division}/members', [AdminDivisionController::class, 'members'])->name('members');
            Route::post('/{division}/members', [AdminDivisionController::class, 'addMember'])->name('members.add');
            Route::delete('/{division}/members/{user}', [AdminDivisionController::class, 'removeMember'])->name('members.remove');
        });
        
        // 3. Reports (Laporan Global Cuti)
        Route::prefix('reports')->name('reports.')->group(function () {
            Route::get('/leave-applications', [AdminReportController::class, 'leaveReport'])->name('leave-report');
        });
    });
});

require __DIR__.'/auth.php';