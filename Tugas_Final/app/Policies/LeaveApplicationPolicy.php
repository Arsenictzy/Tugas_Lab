<?php

namespace App\Policies;

use App\Models\LeaveApplication;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class LeaveApplicationPolicy
{
    public function view(User $user, LeaveApplication $leaveApplication): bool
    {
        return $user->id === $leaveApplication->user_id 
            || $user->isAdmin()
            || $user->isHRD()
            || ($user->isLeader() && $user->division && $user->division->members->contains('id', $leaveApplication->user_id));
    }
    
    public function cancel(User $user, LeaveApplication $leaveApplication): bool
    {
        return $user->id === $leaveApplication->user_id 
            && $leaveApplication->status === 'pending';
    }
    
    public function verify(User $user, LeaveApplication $leaveApplication): bool
    {
        return $user->isLeader() 
            && $user->division 
            && $user->division->members->contains('id', $leaveApplication->user_id)
            && $leaveApplication->status === 'pending';
    }
}