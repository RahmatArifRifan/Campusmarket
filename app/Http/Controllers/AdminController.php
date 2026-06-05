<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Store;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    // ===== DASHBOARD =====
    public function dashboard()
    {
        $stats = [
            'users'    => User::count(),
            'sellers'  => User::where('role', 'seller')->count(),
            'buyers'   => User::where('role', 'buyer')->count(),
            'stores'   => Store::where('is_active', true)->count(),
            'orders'   => Order::count(),
            'done'     => Order::where('status', 'done')->count(),
            'pending'  => Order::where('status', 'pending')->count(),
            'volume'   => Order::where('status', 'done')->sum('total_price'),
        ];

        $recentUsers  = User::latest()->take(5)->get();
        $recentOrders = Order::with('buyer', 'store')->latest()->take(5)->get();

        // Hot Trending
        $trendingStores = Store::withCount(['orders as weekly_txn' => function ($q) {
            $q->where('status', 'done')->where('completed_at', '>=', now()->subDays(7));
        }])->orderByDesc('weekly_txn')->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recentUsers', 'recentOrders', 'trendingStores'));
    }

    // ===== USERS =====
    public function users(Request $request)
    {
        $users = User::when($request->search, fn($q, $s) =>
                $q->where('name', 'like', "%{$s}%")->orWhere('email', 'like', "%{$s}%")
            )
            ->when($request->role, fn($q, $r) => $q->where('role', $r))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.users', compact('users'));
    }

    public function toggleBanUser(User $user)
    {
        if ($user->role === 'admin') {
            return back()->with('error', 'Tidak bisa ban akun admin.');
        }
        $user->update(['is_banned' => !$user->is_banned]);
        $action = $user->is_banned ? 'dinonaktifkan' : 'diaktifkan kembali';
        return back()->with('success', "Akun {$user->name} berhasil {$action}.");
    }

    // ===== STORES =====
    public function stores(Request $request)
    {
        $stores = Store::with('owner')
            ->when($request->search, fn($q, $s) =>
                $q->where('name', 'like', "%{$s}%")
            )
            ->withCount('products', 'orders')
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.stores', compact('stores'));
    }

    public function toggleBanStore(Store $store)
    {
        $store->update(['is_banned' => !$store->is_banned, 'is_active' => $store->is_banned]);
        $action = $store->is_banned ? 'dinonaktifkan' : 'diaktifkan kembali';
        return back()->with('success', "Toko {$store->name} berhasil {$action}.");
    }

    // ===== TRANSACTIONS =====
    public function transactions(Request $request)
    {
        $orders = Order::with('buyer', 'store', 'items')
            ->when($request->status, fn($q, $s) => $q->where('status', $s))
            ->when($request->search, fn($q, $s) =>
                $q->where('order_code', 'like', "%{$s}%")
                  ->orWhereHas('buyer', fn($bq) => $bq->where('name', 'like', "%{$s}%"))
            )
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.transactions', compact('orders'));
    }

    public function cancelOrder(Order $order)
    {
        if ($order->status !== 'pending') {
            return back()->with('error', 'Hanya pesanan pending yang bisa dibatalkan.');
        }
        $order->update(['status' => 'cancelled']);
        return back()->with('success', "Pesanan {$order->order_code} berhasil dibatalkan.");
    }
}
