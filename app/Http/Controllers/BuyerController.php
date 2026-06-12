<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;

class BuyerController extends Controller
{
    // ===== DASHBOARD =====
    public function dashboard()
    {
        $user   = Auth::user();
        $orders = $user->orders()->with('store', 'items')->latest()->take(5)->get();

        $stats = [
            'total'   => $user->orders()->count(),
            'pending' => $user->orders()->where('status', 'pending')->count(),
            'done'    => $user->orders()->where('status', 'done')->count(),
            'spend'   => $user->orders()->where('status', 'done')->sum('total_price'),
        ];

        return view('buyer.dashboard', compact('user', 'orders', 'stats'));
    }

    // ===== SHOP =====
    public function shop(Request $request)
    {
        $products = Product::with('store')
            ->where('is_active', true)
            ->where('is_banned', false)
            ->whereHas('store', fn($q) => $q->where('is_active', true)->where('is_banned', false))
            ->when($request->category, fn($q, $c) => $q->where('category', $c))
            ->when($request->search, fn($q, $s) =>
                $q->where('name', 'like', "%{$s}%")
                  ->orWhereHas('store', fn($sq) => $sq->where('name', 'like', "%{$s}%"))
            )
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('buyer.shop', compact('products'));
    }

    // ===== CART =====
    public function cart()
    {
        $cart       = session()->get('cart', []);
        $storeGroups = [];
        $grandTotal  = 0;

        // Format cart: ['store_1' => ['store_id'=>1, 'store_name'=>'...', 'items'=>[pid=>qty]], ...]
        foreach ($cart as $storeKey => $storeData) {
            $storeItems = [];
            $storeTotal = 0;

            foreach ($storeData['items'] as $productId => $qty) {
                $product = Product::with('store')->find($productId);
                if ($product) {
                    $subtotal     = $product->price * $qty;
                    $storeTotal  += $subtotal;
                    $grandTotal  += $subtotal;
                    $storeItems[] = [
                        'product'  => $product,
                        'qty'      => $qty,
                        'subtotal' => $subtotal,
                    ];
                }
            }

            if (!empty($storeItems)) {
                $storeGroups[] = [
                    'store_id'   => $storeData['store_id'],
                    'store_name' => $storeData['store_name'],
                    'store'      => $storeItems[0]['product']->store,
                    'items'      => $storeItems,
                    'total'      => $storeTotal,
                ];
            }
        }

        return view('buyer.cart', compact('storeGroups', 'grandTotal'));
    }

    public function addToCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'qty'        => 'integer|min:1|max:99',
        ]);

        $product = Product::with('store')->findOrFail($request->product_id);
        $qty     = $request->qty ?? 1;

        // Cart sekarang: array of stores → masing-masing punya items
        // Format: ['store_1' => ['store_name'=>'...', 'items'=>[pid=>qty]], ...]
        $cart    = session()->get('cart', []);
        $storeKey = 'store_' . $product->store_id;

        if (!isset($cart[$storeKey])) {
            $cart[$storeKey] = [
                'store_id'   => $product->store_id,
                'store_name' => $product->store->name,
                'items'      => [],
            ];
        }

        if (isset($cart[$storeKey]['items'][$product->id])) {
            $cart[$storeKey]['items'][$product->id] += $qty;
        } else {
            $cart[$storeKey]['items'][$product->id] = $qty;
        }

        session()->put('cart', $cart);
        return back()->with('success', "'{$product->name}' ditambahkan ke keranjang!");
    }

    public function updateCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'store_id'   => 'required|integer',
            'qty'        => 'required|integer|min:1|max:99',
        ]);

        $cart     = session()->get('cart', []);
        $storeKey = 'store_' . $request->store_id;

        if (isset($cart[$storeKey]['items'][$request->product_id])) {
            $cart[$storeKey]['items'][$request->product_id] = $request->qty;
            session()->put('cart', $cart);
        }

        return back();
    }

    public function removeFromCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required',
            'store_id'   => 'required|integer',
        ]);

        $cart     = session()->get('cart', []);
        $storeKey = 'store_' . $request->store_id;

        unset($cart[$storeKey]['items'][$request->product_id]);

        // Hapus toko jika tidak ada item lagi
        if (empty($cart[$storeKey]['items'])) {
            unset($cart[$storeKey]);
        }

        session()->put('cart', $cart);
        return back()->with('success', 'Produk dihapus dari keranjang.');
    }

    public function clearCart()
    {
        session()->forget('cart');
        return back()->with('success', 'Keranjang dikosongkan.');
    }

    public function clearStoreCart(Request $request)
    {
        $request->validate(['store_id' => 'required|integer']);
        $cart = session()->get('cart', []);
        unset($cart['store_' . $request->store_id]);
        session()->put('cart', $cart);
        return back()->with('success', 'Keranjang toko dihapus.');
    }

    // ===== CHECKOUT =====
    public function checkout()
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) return redirect()->route('buyer.cart');

        $storeGroups = [];
        $grandTotal  = 0;

        foreach ($cart as $storeKey => $storeData) {
            $storeItems = [];
            $storeTotal = 0;

            foreach ($storeData['items'] as $productId => $qty) {
                $product = Product::with('store')->find($productId);
                if ($product) {
                    $subtotal     = $product->price * $qty;
                    $storeTotal  += $subtotal;
                    $grandTotal  += $subtotal;
                    $storeItems[] = ['product' => $product, 'qty' => $qty, 'subtotal' => $subtotal];
                }
            }

            if (!empty($storeItems)) {
                $storeGroups[] = [
                    'store_id'   => $storeData['store_id'],
                    'store_name' => $storeData['store_name'],
                    'store'      => $storeItems[0]['product']->store,
                    'items'      => $storeItems,
                    'total'      => $storeTotal,
                ];
            }
        }

        if (empty($storeGroups)) return redirect()->route('buyer.cart');

        return view('buyer.checkout', compact('storeGroups', 'grandTotal'));
    }

    public function processCheckout(Request $request)
    {
        $request->validate([
            'payment_method' => 'required|in:tunai,qris,transfer',
            'store_id'       => 'required|integer|exists:stores,id',
        ]);

        $cart = session()->get('cart', []);
        if (empty($cart)) return redirect()->route('buyer.cart');

        $storeKey = 'store_' . $request->store_id;
        if (!isset($cart[$storeKey])) {
            return redirect()->route('buyer.cart')->with('error', 'Toko tidak ditemukan di keranjang.');
        }

        $storeCart = $cart[$storeKey];

        DB::transaction(function () use ($storeCart, $storeKey, $request) {
            $user  = Auth::user();
            $total = 0;
            $items = [];

            foreach ($storeCart['items'] as $productId => $qty) {
                $product = Product::lockForUpdate()->findOrFail($productId);
                $product->reduceStock($qty);
                $subtotal = $product->price * $qty;
                $total   += $subtotal;
                $items[]  = [
                    'product'        => $product,
                    'quantity'       => $qty,
                    'price_at_order' => $product->price,
                ];
            }

            // Buat order per toko
            $order = Order::create([
                'order_code'     => Order::generateCode(),
                'buyer_id'       => $user->id,
                'store_id'       => $storeCart['store_id'],
                'payment_method' => $request->payment_method,
                'total_price'    => $total,
                'status'         => 'pending',
            ]);

            // Buat order items
            foreach ($items as $item) {
                OrderItem::create([
                    'order_id'       => $order->id,
                    'product_id'     => $item['product']->id,
                    'quantity'       => $item['quantity'],
                    'price_at_order' => $item['price_at_order'],
                ]);
            }

            // Generate QR Code (SVG — tidak butuh imagick)
            $qrContent = json_encode([
                'order_code' => $order->order_code,
                'store_id'   => $order->store_id,
                'total'      => $order->total_price,
                'buyer'      => $user->name,
            ]);

            $qrPath = 'qrcodes/' . $order->order_code . '.svg';
            Storage::disk('public')->put($qrPath, QrCode::format('svg')->size(300)->errorCorrection('H')->generate($qrContent));
            $order->update(['qr_path' => $qrPath]);
            $order->update(['qr_path' => $qrPath]);

            // Hapus hanya toko yang sudah di-checkout dari keranjang
            $cart = session()->get('cart', []);
            unset($cart[$storeKey]);
            session()->put('cart', $cart);
            session()->put('last_order_id', $order->id);
        });

        $orderId = session()->get('last_order_id');
        return redirect()->route('buyer.orders.qr', $orderId);
    }

    // ===== ORDERS =====
    public function orders()
    {
        $user   = Auth::user();
        $orders = $user->orders()->with('store', 'items.product')->latest()->paginate(10);
        return view('buyer.orders', compact('orders'));
    }

    public function showQr(Order $order)
    {
        abort_if($order->buyer_id !== Auth::id(), 403);
        $order->load('store', 'items.product');
        return view('buyer.qr', compact('order'));
    }

    // ===== BULK CHECKOUT =====
    public function processBulkCheckout(Request $request)
    {
        $request->validate([
            'payment_method' => 'required|in:tunai,qris,transfer',
        ]);

        $cart = session()->get('cart', []);
        if (empty($cart)) return redirect()->route('buyer.cart');

        $orderIds = [];

        DB::transaction(function () use ($cart, $request, &$orderIds) {
            $user = Auth::user();

            foreach ($cart as $storeKey => $storeData) {
                $total = 0;
                $items = [];

                foreach ($storeData['items'] as $productId => $qty) {
                    $product = Product::lockForUpdate()->findOrFail($productId);
                    $product->reduceStock($qty);
                    $subtotal = $product->price * $qty;
                    $total   += $subtotal;
                    $items[]  = [
                        'product'        => $product,
                        'quantity'       => $qty,
                        'price_at_order' => $product->price,
                    ];
                }

                $order = Order::create([
                    'order_code'     => Order::generateCode(),
                    'buyer_id'       => $user->id,
                    'store_id'       => $storeData['store_id'],
                    'payment_method' => $request->payment_method,
                    'total_price'    => $total,
                    'status'         => 'pending',
                ]);

                foreach ($items as $item) {
                    OrderItem::create([
                        'order_id'       => $order->id,
                        'product_id'     => $item['product']->id,
                        'quantity'       => $item['quantity'],
                        'price_at_order' => $item['price_at_order'],
                    ]);
                }

                $qrContent = json_encode([
                    'order_code' => $order->order_code,
                    'store_id'   => $order->store_id,
                    'total'      => $order->total_price,
                    'buyer'      => $user->name,
                ]);

                $qrPath = 'qrcodes/' . $order->order_code . '.svg';
                Storage::disk('public')->put($qrPath, QrCode::format('svg')->size(300)->errorCorrection('H')->generate($qrContent));
                $order->update(['qr_path' => $qrPath]);

                $orderIds[] = $order->id;
            }

            session()->forget('cart');
            session()->put('bulk_order_ids', $orderIds);
        });

        $orderIds = session()->get('bulk_order_ids', []);

        // Kalau hanya 1 toko, langsung ke QR page
        if (count($orderIds) === 1) {
            return redirect()->route('buyer.orders.qr', $orderIds[0]);
        }

        // Kalau lebih dari 1, ke halaman orders dengan pesan sukses
        return redirect()->route('buyer.orders')
            ->with('success', count($orderIds) . ' pesanan dari ' . count($orderIds) . ' toko berhasil dibuat! Lihat QR Code di bawah.');
    }
}
