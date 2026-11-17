<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = ['name', 'username', 'email', 'password', 'role', 'nim', 'is_active'];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    public function isAdmin(): bool
    {
        return in_array($this->role, ['admin', 'superadmin']);
    }

    public function homeRoute(): string
    {
        return match ($this->role) {
            'mahasiswa' => 'mahasiswa.dashboard',
            'ob' => 'ob.dashboard',
            default => 'admin.dashboard',
        };
    }

    public function roleLabel(): string
    {
        return ['mahasiswa' => 'Mahasiswa', 'ob' => 'Petugas OB', 'admin' => 'Admin', 'superadmin' => 'Superadmin'][$this->role] ?? $this->role;
    }

    public function initials(): string
    {
        return mb_strtoupper(mb_substr($this->name, 0, 1));
    }
}
