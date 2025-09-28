<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Traits\LogsActivity;

class User extends Authenticatable
{
    use LogsActivity;
    use HasApiTokens, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function pieceworkEntries()
    {
        return $this->hasMany(PieceworkEntry::class);
    }

    public function hasPermission($permission)
    {
        return $this->role?->hasPermission($permission) ?? false;
    }
}