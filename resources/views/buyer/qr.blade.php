@extends('layouts.dashboard')
@section('title','QR Code Pesanan')
@section('page-title','QR Code Pesanan')
@section('page-subtitle','Tunjukkan ke penjual saat ambil barang')

@section('sidebar-menu')
<div class="sidebar-section">Menu</div>
<a class="sidebar-link" href="{{ route('buyer.dashboard') }}"><i class="fas fa-home"></i> Dashboard</a>
<a class="sidebar-link" href="{{ route('buyer.shop') }}"><i class="fas fa-store"></i> Belanja</a>
<a class="sidebar-link" href="{{ route('buyer.cart') }}"><i class="fas fa-shopping-cart"></i> Keranjang</a>
<a class="sidebar-link active" href="{{ route('buyer.orders') }}"><i class="fas fa-history"></i> Riwayat</a>
@endsection

@section('content')
<div style="max-width:500px;margin:0 auto">
  <div class="form-card" style="text-align:center">
    <div style="font-size:3rem;margin-bottom:12px">🎉</div>
    <h2 style="color:white;font-size:1.4rem;font-weight:900;margin-bottom:6px">Pesanan Berhasil Dibuat!</h2>
    <p style="color:var(--text-muted);margin-bottom:24px">Tunjukkan QR Code ini ke penjual saat mengambil barang</p>

    <div style="display:flex;align-items:center;gap:12px;padding:14px;background:rgba(99,102,241,0.08);border:1px solid rgba(99,102,241,0.2);border-radius:12px;margin-bottom:20px;text-align:left">
      <div style="width:40px;height:40px;border-radius:10px;background:linear-gradient(135deg,#667eea,#764ba2);display:flex;align-items:center;justify-content:center;flex-shrink:0">🏪</div>
      <div>
        <div style="font-weight:800;color:white">{{ $order->store->name }}</div>
        <div style="font-size:0.78rem;color:var(--text-muted)">{{ $order->items->sum('quantity') }} item · {{ $order->formattedTotal() }} · {{ strtoupper($order->payment_method) }}</div>
      </div>
      <span class="badge badge-warning" style="margin-left:auto">{{ $order->statusLabel() }}</span>
    </div>

    {{-- QR CODE --}}
    <div style="background:white;border-radius:16px;padding:20px;display:inline-block;margin-bottom:16px">
      {!! QrCode::size(220)->errorCorrection('H')->generate(json_encode(['order_code'=>$order->order_code,'store'=>$order->store->name,'total'=>$order->total_price])) !!}
    </div>
    <p style="font-size:0.78rem;color:var(--text-muted);font-family:monospace;margin-bottom:20px">{{ $order->order_code }}</p>

    {{-- ITEMS --}}
    <div style="text-align:left;margin-bottom:20px">
      <h4 style="color:white;margin-bottom:10px">Detail Pesanan</h4>
      @foreach($order->items as $item)
        <div style="display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px solid rgba(255,255,255,0.06);font-size:0.88rem">
          <span style="color:var(--text-secondary)">{{ $item->product->displayEmoji() }} {{ $item->product->name }} ×{{ $item->quantity }}</span>
          <span style="color:white;font-weight:700">Rp {{ number_format($item->price_at_order * $item->quantity,0,',','.') }}</span>
        </div>
      @endforeach
      <div style="display:flex;justify-content:space-between;padding:12px 0;font-weight:900">
        <span style="color:white">Total</span>
        <span style="color:var(--primary-light)">{{ $order->formattedTotal() }}</span>
      </div>
    </div>

    <a href="{{ route('buyer.orders') }}" class="btn btn-primary" style="width:100%">
      <i class="fas fa-history"></i> Lihat Semua Riwayat
    </a>
  </div>
</div>
@endsection
