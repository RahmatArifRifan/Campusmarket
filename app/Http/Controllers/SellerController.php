<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SellerController extends Controller
{
    private function store(): Store
    {
        $store = Auth::user()->store;
        if (!$store) {
            return redirect()->route('seller.store.profile')
                ->with('error', 'Buat profil toko terlebih dahulu sebelum mengelola produk atau pesanan.')
                ->throwResponse();
        }
        return $store;
    }

    // ===== DASHBOARD =====
    public function dashboard()
    {
        $user  = Auth::user();
        $store = $user->store;

        $stats = [
            'products' => $store ? $store->products()->where('is_active', true)->count() : 0,
            'pending'  => $store ? $store->orders()->where('status', 'pending')->count() : 0,
            'done'     => $store ? $store->orders()->where('status', 'done')->count() : 0,
            'revenue'  => $store ? $store->orders()->where('status', 'done')->sum('total_price') : 0,
        ];

        $recentOrders = $store
            ? $store->orders()->with('buyer', 'items.product')->latest()->take(5)->get()
            : collect();

        return view('seller.dashboard', compact('user', 'store', 'stats', 'recentOrders'));
    }

    // ===== PRODUCTS =====
    public function products()
    {
        $store    = $this->store();
        $products = $store->products()->latest()->get();
        return view('seller.products', compact('store', 'products'));
    }

    public function storeProduct(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:200',
            'description' => 'nullable|string',
            'category'    => 'required|in:makanan,minuman,fashion,jasa,lainnya',
            'price'       => 'required|integer|min:0',
            'stock'       => 'required|integer|min:0',
            'emoji'       => 'nullable|string|max:5',
            'image'       => 'nullable|image|mimes:jpeg,png,webp,gif|max:5120',
        ]);

        $store     = $this->store();
        $imagePath = null;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
        }

        $store->products()->create([
            'name'        => $request->name,
            'description' => $request->description,
            'category'    => $request->category,
            'price'       => $request->price,
            'stock'       => $request->stock,
            'emoji'       => $request->emoji,
            'image_path'  => $imagePath,
        ]);

        return back()->with('success', 'Produk berhasil ditambahkan!');
    }

    public function updateProduct(Request $request, Product $product)
    {
        abort_if($product->store_id !== $this->store()->id, 403);

        $request->validate([
            'name'        => 'required|string|max:200',
            'description' => 'nullable|string',
            'category'    => 'required|in:makanan,minuman,fashion,jasa,lainnya',
            'price'       => 'required|integer|min:0',
            'stock'       => 'required|integer|min:0',
            'emoji'       => 'nullable|string|max:5',
            'image'       => 'nullable|image|mimes:jpeg,png,webp,gif|max:5120',
        ]);

        $data = $request->only('name', 'description', 'category', 'price', 'stock', 'emoji');

        if ($request->hasFile('image')) {
            // Hapus foto lama
            if ($product->image_path) {
                Storage::disk('public')->delete($product->image_path);
            }
            $data['image_path'] = $request->file('image')->store('products', 'public');
        }

        $product->update($data);
        return back()->with('success', 'Produk berhasil diperbarui!');
    }

    public function destroyProduct(Product $product)
    {
        abort_if($product->store_id !== $this->store()->id, 403);

        if ($product->image_path) {
            Storage::disk('public')->delete($product->image_path);
        }
        $product->delete();

        return back()->with('success', 'Produk berhasil dihapus.');
    }

    // ===== ORDERS =====
    public function orders()
    {
        $store  = $this->store();
        $orders = $store->orders()->with('buyer', 'items.product')->latest()->get();
        return view('seller.orders', compact('store', 'orders'));
    }

    public function validateOrder(Request $request)
    {
        $request->validate(['order_code' => 'required|string']);

        $store = $this->store();
        $order = Order::where('order_code', strtoupper($request->order_code))
                      ->where('store_id', $store->id)
                      ->with('buyer', 'items.product')
                      ->first();

        if (!$order) {
            return back()->with('scan_error', 'Order ID tidak ditemukan atau bukan milik toko ini.');
        }
        if ($order->status === 'done') {
            return back()->with('scan_error', 'Pesanan ini sudah selesai sebelumnya.');
        }

        return back()->with('scan_order', $order);
    }

    public function completeOrder(Order $order)
    {
        abort_if($order->store_id !== $this->store()->id, 403);
        abort_if($order->status !== 'pending', 400, 'Pesanan tidak dalam status pending.');

        $order->complete();
        return back()->with('success', "Pesanan {$order->order_code} berhasil diselesaikan!");
    }

    // ===== STORE PROFILE =====
    public function storeProfile()
    {
        $user  = Auth::user();
        $store = $user->store;
        return view('seller.store-profile', compact('user', 'store'));
    }

    public function updateStoreProfile(Request $request)
    {
        $request->validate([
            'name'             => 'required|string|max:100',
            'category'         => 'required|string|max:50',
            'description'      => 'nullable|string',
            'logo_emoji'       => 'nullable|string|max:5',
            'location'         => 'nullable|string|max:200',
            'operating_hours'  => 'nullable|string|max:100',
            'logo'             => 'nullable|image|mimes:jpeg,png,webp|max:2048',
        ]);

        $user  = Auth::user();
        $store = $user->store;
        $data  = $request->only('name', 'category', 'description', 'location', 'operating_hours');
        $data['logo_emoji'] = $request->input('logo_emoji') ?: '🏪';

        if ($request->hasFile('logo')) {
            if ($store && $store->logo_path) {
                Storage::disk('public')->delete($store->logo_path);
            }
            $data['logo_path'] = $request->file('logo')->store('store-logos', 'public');
        }

        if ($store) {
            $store->update($data);
        } else {
            $user->store()->create(array_merge($data, ['user_id' => $user->id]));
        }

        return back()->with('success', 'Profil toko berhasil disimpan!');
    }
}
