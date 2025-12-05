<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Division extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'leader_id',
        'formed_date',
    ];

    protected $casts = [
        'formed_date' => 'date',
    ];

    public function leader()
    {
        return $this->belongsTo(User::class, 'leader_id');
    }

    public function members()
    {
        return $this->hasMany(User::class);
    }

    public function getMemberCountAttribute()
    {
        return $this->members()->count();
    }

    public function getActiveMembersAttribute()
    {
        return $this->members()->where('is_active', true)->get();
    }
}