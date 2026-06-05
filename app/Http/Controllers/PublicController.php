<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Store;

class PublicController extends Controller
{
    // ===== LANDING PAGE =====
    public function home()
    {
        // Produk aktif untuk katalog
        $products = Product::with('store')
            ->where('is_active', true)
            ->where('is_banned', false)
            ->whereHas('store', fn($q) => $q->where('is_active', true)->where('is_banned', false))
            ->latest()
            ->take(12)
            ->get();

        // Hot Trending: toko dengan transaksi selesai terbanyak 7 hari terakhir
        $trendingStores = Store::with('owner')
            ->where('is_active', true)
            ->where('is_banned', false)
            ->withCount(['orders as weekly_txn' => function ($q) {
                $q->where('status', 'done')
                  ->where('completed_at', '>=', now()->subDays(7));
            }])
            ->orderByDesc('weekly_txn')
            ->take(6)
            ->get();

        // Stats
        $stats = [
            'users'    => \App\Models\User::count(),
            'stores'   => Store::where('is_active', true)->count(),
            'orders'   => \App\Models\Order::where('status', 'done')->count(),
        ];

        return view('public.home', compact('products', 'trendingStores', 'stats'));
    }

    // ===== KATALOG (AJAX / full page) =====
    public function catalog()
    {
        $products = Product::with('store')
            ->where('is_active', true)
            ->where('is_banned', false)
            ->whereHas('store', fn($q) => $q->where('is_active', true))
            ->when(request('category'), fn($q, $cat) => $q->where('category', $cat))
            ->when(request('search'), fn($q, $s) =>
                $q->where('name', 'like', "%{$s}%")
                  ->orWhereHas('store', fn($sq) => $sq->where('name', 'like', "%{$s}%"))
            )
            ->latest()
            ->paginate(12);

        return view('public.catalog', compact('products'));
    }
}
