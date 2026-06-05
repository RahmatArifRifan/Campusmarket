@extends('layouts.dashboard')
@section('title','Dashboard Pengusaha')
@section('page-title','Dashboard')
@section('page-subtitle','Pantau performa toko kamu')

@section('sidebar-menu')
<div class="sidebar-section">Menu</div>
<a class="sidebar-link {{ request()->routeIs('seller.dashboard')?'active':'' }}" href="{{ route('seller.dashboard') }}"><i class="fas fa-chart-bar"></i> Overview</a>
<a class="sidebar-link {{ request()->routeIs('seller.products')?'active':'' }}" href="{{ route('seller.products') }}"><i class="fas fa-box-open"></i> Produk Saya</a>
<a class="sidebar-link {{ request()->routeIs('seller.orders')?'active':'' }}" href="{{ route('seller.orders') }}">
  <i class="fas fa-qrcode"></i> Pesanan Masuk
  @if($stats['pending'] > 0)<span class="sidebar-badge">{{ $stats['pending'] }}</span>@endif
</a>
<a class="sidebar-link {{ request()->routeIs('seller.store.profile')?'active':'' }}" href="{{ route('seller.store.profile') }}"><i class="fas fa-store-alt"></i> Profil Toko</a>
@endsection

@section('content')
<div class="stats-grid">
  <div class="stat-card"><div class="stat-icon blue"><i class="fas fa-box-open"></i></div><div class="stat-info"><p>Total Produk</p><h3>{{ $stats['products'] }}</h3></div></div>
  <div class="stat-card"><div class="stat-icon orange"><i class="fas fa-clock"></i></div><div class="stat-info"><p>Pesanan Pending</p><h3>{{ $stats['pending'] }}</h3></div></div>
  <div class="stat-card"><div class="stat-icon green"><i class="fas fa-check-circle"></i></div><div class="stat-info"><p>Transaksi Selesai</p><h3>{{ $stats['done'] }}</h3></div></div>
  <div class="stat-card"><div class="stat-icon cyan"><i class="fas fa-coins"></i></div><div class="stat-info"><p>Total Pendapatan</p><h3>Rp {{ number_format($stats['revenue'],0,',','.') }}</h3></div></div>
</div>

@if(!$store)
  <div style="background:rgba(245,158,11,0.1);border:1px solid rgba(245,158,11,0.3);border-radius:14px;padding:20px;margin-bottom:24px">
    <p style="font-weight:700;color:#fbbf24;margin-bottom:8px"><i class="fas fa-exclamation-triangle"></i> Toko belum dibuat!</p>
    <p style="color:var(--text-muted);font-size:0.88rem;margin-bottom:14px">Buat profil toko terlebih dahulu agar produkmu bisa tampil di katalog.</p>
    <a href="{{ route('seller.store.profile') }}" class="btn btn-warning btn-sm"><i class="fas fa-store"></i> Buat Profil Toko</a>
  </div>
@endif

<div class="table-card">
  <div class="table-header">
    <h3>Pesanan Terbaru</h3>
    <a href="{{ route('seller.orders') }}" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-right"></i> Lihat Semua</a>
  </div>
  @if($recentOrders->isEmpty())
    <div style="text-align:center;padding:40px;color:var(--text-muted)">
      <i class="fas fa-inbox" style="font-size:2.5rem;display:block;margin-bottom:12px;opacity:0.2"></i>
      <p>Belum ada pesanan masuk</p>
    </div>
  @else
    <table class="data-table">
      <thead><tr><th>Order ID</th><th>Pembeli</th><th>Total</th><th>Status</th></tr></thead>
      <tbody>
        @foreach($recentOrders as $order)
        <tr>
          <td><code style="color:var(--primary-light);font-size:0.75rem">{{ $order->order_code }}</code></td>
          <td>{{ $order->buyer->name }}</td>
          <td style="font-weight:700;color:white">{{ $order->formattedTotal() }}</td>
          <td><span class="badge {{ $order->statusBadgeClass() }}">{{ $order->statusLabel() }}</span></td>
        </tr>
        @endforeach
      </tbody>
    </table>
  @endif
</div>
@endsection
