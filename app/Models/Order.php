<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_code', 'buyer_id', 'store_id',
        'payment_method', 'status', 'total_price', 'qr_path', 'completed_at',
    ];

    protected $casts = [
        'completed_at' => 'datetime',
        'total_price'  => 'integer',
    ];

    // ===== RELATIONSHIPS =====
    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    // ===== HELPERS =====
    public static function generateCode(): string
    {
        do {
            $code = 'CM-' . strtoupper(Str::random(8));
        } while (self::where('order_code', $code)->exists());

        return $code;
    }

    public function qrUrl(): string
    {
        return $this->qr_path ? asset('storage/' . $this->qr_path) : '';
    }

    public function formattedTotal(): string
    {
        return 'Rp ' . number_format($this->total_price, 0, ',', '.');
    }

    public function statusLabel(): string
    {
        return match($this->status) {
            'done'      => 'Selesai',
            'cancelled' => 'Dibatalkan',
            default     => 'Menunggu',
        };
    }

    public function statusBadgeClass(): string
    {
        return match($this->status) {
            'done'      => 'badge-success',
            'cancelled' => 'badge-danger',
            default     => 'badge-warning',
        };
    }

    public function complete(): void
    {
        $this->update([
            'status'       => 'done',
            'completed_at' => now(),
        ]);
    }
}
