<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function updateProfile(User $user, User $model): bool
    {
        return $user->id === $model->id || $user->isAdmin();
    }
}