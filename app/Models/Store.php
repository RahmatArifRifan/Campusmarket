<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'name', 'description', 'logo_path', 'logo_emoji',
        'category', 'location', 'operating_hours', 'is_active', 'is_banned',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_banned' => 'boolean',
    ];

    // ===== RELATIONSHIPS =====
    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    // ===== HELPERS =====
    public function logoUrl(): string
    {
        if ($this->logo_path) {
            return asset('storage/' . $this->logo_path);
        }
        return '';
    }

    public function logoDisplay(): string
    {
        return $this->logo_path ? '' : ($this->logo_emoji ?? '🏪');
    }

    /**
     * Jumlah transaksi selesai dalam 7 hari terakhir (untuk Hot Trending)
     */
    public function weeklyTransactionCount(): int
    {
        return $this->orders()
            ->where('status', 'done')
            ->where('completed_at', '>=', now()->subDays(7))
            ->count();
    }
}
