<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'store_id', 'name', 'description', 'category',
        'price', 'stock', 'image_path', 'emoji', 'is_active', 'is_banned',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_banned' => 'boolean',
        'price'     => 'integer',
        'stock'     => 'integer',
    ];

    // ===== RELATIONSHIPS =====
    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    // ===== HELPERS =====
    public function imageUrl(): string
    {
        if ($this->image_path) {
            return asset('storage/' . $this->image_path);
        }
        return '';
    }

    public function displayEmoji(): string
    {
        if ($this->emoji) return $this->emoji;
        return match($this->category) {
            'makanan' => '🍱',
            'minuman' => '🥤',
            'fashion' => '👕',
            'jasa'    => '🛠️',
            default   => '📦',
        };
    }

    public function formattedPrice(): string
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }

    public function categoryGradient(): string
    {
        return match($this->category) {
            'makanan' => 'linear-gradient(135deg,#ff9a56,#ff6b35)',
            'minuman' => 'linear-gradient(135deg,#56ccf2,#2f80ed)',
            'fashion' => 'linear-gradient(135deg,#a18cd1,#fbc2eb)',
            'jasa'    => 'linear-gradient(135deg,#43e97b,#38f9d7)',
            default   => 'linear-gradient(135deg,#f7971e,#ffd200)',
        };
    }

    public function reduceStock(int $qty): void
    {
        if ($this->stock < $qty) {
            throw new \Exception("Stok tidak cukup untuk produk {$this->name}");
        }
        $this->decrement('stock', $qty);
    }
}
