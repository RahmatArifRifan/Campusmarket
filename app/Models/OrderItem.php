<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = ['order_id', 'product_id', 'quantity', 'price_at_order'];

    protected $casts = [
        'quantity'       => 'integer',
        'price_at_order' => 'integer',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function subtotal(): int
    {
        return $this->quantity * $this->price_at_order;
    }

    public function formattedSubtotal(): string
    {
        return 'Rp ' . number_format($this->subtotal(), 0, ',', '.');
    }
}
