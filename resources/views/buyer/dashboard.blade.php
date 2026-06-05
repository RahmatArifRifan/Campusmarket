@extends('layouts.dashboard')
@section('title','Dashboard Pembeli')
@section('page-title','Dashboard')
@section('page-subtitle','Selamat datang, '.$user->name.'!')

@section('sidebar-menu')
<div class="sidebar-section">Menu</div>
<a class="sidebar-link {{ request()->routeIs('buyer.dashboard')?'active':'' }}" href="{{ route('buyer.dashboard') }}"><i class="fas fa-home"></i> Dashboard</a>
<a class="sidebar-link {{ request()->routeIs('buyer.shop')?'active':'' }}" href="{{ route('buyer.shop') }}"><i class="fas fa-store"></i> Belanja</a>
<a class="sidebar-link {{ request()->routeIs('buyer.cart')?'active':'' }}" href="{{ route('buyer.cart') }}">
  <i class="fas fa-shopping-cart"></i> Keranjang
  @php $cartCount = collect(session('cart',[]))->sum(fn($s)=>collect($s['items']??[])->sum()) @endphp
  @if($cartCount > 0)<span class="sidebar-badge">{{ $cartCount }}</span>@endif
</a>
<a class="sidebar-link {{ request()->routeIs('buyer.orders')?'active':'' }}" href="{{ route('buyer.orders') }}"><i class="fas fa-history"></i> Riwayat</a>
@endsection

@section('content')
<div class="stats-grid">
  <div class="stat-card"><div class="stat-icon blue"><i class="fas fa-shopping-bag"></i></div><div class="stat-info"><p>Total Pesanan</p><h3>{{ $stats['total'] }}</h3></div></div>
  <div class="stat-card"><div class="stat-icon green"><i class="fas fa-check-circle"></i></div><div class="stat-info"><p>Selesai</p><h3>{{ $stats['done'] }}</h3></div></div>
  <div class="stat-card"><div class="stat-icon orange"><i class="fas fa-clock"></i></div><div class="stat-info"><p>Menunggu</p><h3>{{ $stats['pending'] }}</h3></div></div>
  <div class="stat-card"><div class="stat-icon cyan"><i class="fas fa-wallet"></i></div><div class="stat-info"><p>Total Belanja</p><h3>Rp {{ number_format($stats['spend'],0,',','.') }}</h3></div></div>
</div>

<div class="table-card">
  <div class="table-header">
    <h3><i class="fas fa-receipt" style="color:var(--primary-light);margin-right:8px"></i>Pesanan Terbaru</h3>
    <a href="{{ route('buyer.orders') }}" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-right"></i> Lihat Semua</a>
  </div>
  @if($orders->isEmpty())
    <div style="text-align:center;padding:40px;color:var(--text-muted)">
      <i class="fas fa-shopping-bag" style="font-size:2.5rem;display:block;margin-bottom:12px;opacity:0.2"></i>
      <p>Belum ada pesanan. <a href="{{ route('buyer.shop') }}" style="color:var(--primary-light)">Mulai belanja!</a></p>
    </div>
  @else
    <table class="data-table">
      <thead><tr><th>Order ID</th><th>Toko</th><th>Total</th><th>Status</th><th>QR</th></tr></thead>
      <tbody>
        @foreach($orders as $order)
        <tr>
          <td><code style="color:var(--primary-light);font-size:0.75rem">{{ $order->order_code }}</code></td>
          <td>{{ $order->store->name }}</td>
          <td style="font-weight:700;color:white">{{ $order->formattedTotal() }}</td>
          <td><span class="badge {{ $order->statusBadgeClass() }}">{{ $order->statusLabel() }}</span></td>
          <td>
            @if($order->status === 'pending')
              <a href="{{ route('buyer.orders.qr',$order) }}" class="btn btn-primary btn-sm"><i class="fas fa-qrcode"></i> QR</a>
            @else — @endif
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  @endif
</div>
@endsection
