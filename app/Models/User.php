<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'role', 'phone', 'avatar', 'is_banned',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'is_banned'         => 'boolean',
        ];
    }

    // ===== RELATIONSHIPS =====
    public function store()
    {
        return $this->hasOne(Store::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'buyer_id');
    }

    // ===== HELPERS =====
    public function isAdmin(): bool   { return $this->role === 'admin'; }
    public function isSeller(): bool  { return $this->role === 'seller'; }
    public function isBuyer(): bool   { return $this->role === 'buyer'; }
    public function isBanned(): bool  { return $this->is_banned; }

    public function dashboardRoute(): string
    {
        return match($this->role) {
            'admin'  => 'admin.dashboard',
            'seller' => 'seller.dashboard',
            default  => 'buyer.dashboard',
        };
    }

    public function avatarUrl(): string
    {
        if ($this->avatar) {
            return asset('storage/' . $this->avatar);
        }
        $initials = strtoupper(substr($this->name, 0, 2));
        return "https://ui-avatars.com/api/?name={$initials}&background=6366f1&color=fff&size=80";
    }
}
