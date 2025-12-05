<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\LeaveApplication;
use App\Models\User;
use App\Policies\LeaveApplicationPolicy;
use App\Policies\UserPolicy;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        LeaveApplication::class => LeaveApplicationPolicy::class,
        User::class => UserPolicy::class,
    ];
    
    public function boot(): void
    {
        $this->registerPolicies();
    }
}