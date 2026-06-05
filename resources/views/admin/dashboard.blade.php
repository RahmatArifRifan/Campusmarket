@extends('layouts.dashboard')
@section('title','Admin Dashboard')
@section('page-title','Admin Dashboard')
@section('page-subtitle','Pantau ekosistem CampusMarket')

@section('sidebar-menu')
<div class="sidebar-section">Admin Panel</div>
<a class="sidebar-link {{ request()->routeIs('admin.dashboard')?'active':'' }}" href="{{ route('admin.dashboard') }}"><i class="fas fa-tachometer-alt"></i> Overview</a>
<a class="sidebar-link {{ request()->routeIs('admin.users')?'active':'' }}" href="{{ route('admin.users') }}"><i class="fas fa-users"></i> Manajemen User</a>
<a class="sidebar-link {{ request()->routeIs('admin.stores')?'active':'' }}" href="{{ route('admin.stores') }}"><i class="fas fa-store"></i> Manajemen Toko</a>
<a class="sidebar-link {{ request()->routeIs('admin.transactions')?'active':'' }}" href="{{ route('admin.transactions') }}"><i class="fas fa-exchange-alt"></i> Transaksi</a>
@endsection

@section('content')
<div class="stats-grid">
  <div class="stat-card"><div class="stat-icon blue"><i class="fas fa-users"></i></div><div class="stat-info"><p>Total User</p><h3>{{ $stats['users'] }}</h3><small>Pembeli: {{ $stats['buyers'] }} | Penjual: {{ $stats['sellers'] }}</small></div></div>
  <div class="stat-card"><div class="stat-icon green"><i class="fas fa-store"></i></div><div class="stat-info"><p>Toko Aktif</p><h3>{{ $stats['stores'] }}</h3></div></div>
  <div class="stat-card"><div class="stat-icon orange"><i class="fas fa-exchange-alt"></i></div><div class="stat-info"><p>Total Transaksi</p><h3>{{ $stats['orders'] }}</h3><small>Selesai: {{ $stats['done'] }} | Pending: {{ $stats['pending'] }}</small></div></div>
  <div class="stat-card"><div class="stat-icon cyan"><i class="fas fa-coins"></i></div><div class="stat-info"><p>Volume Transaksi</p><h3>Rp {{ number_format($stats['volume'],0,',','.') }}</h3></div></div>
</div>

{{-- TRENDING --}}
<div class="table-card" style="margin-bottom:24px">
  <div class="table-header"><h3><i class="fas fa-fire" style="color:var(--orange);margin-right:8px"></i>Hot Trending Toko</h3></div>
  @if($trendingStores->isEmpty())
    <p style="text-align:center;padding:30px;color:var(--text-muted)">Belum ada data trending</p>
  @else
    <table class="data-table">
      <thead><tr><th>Rank</th><th>Toko</th><th>Transaksi Minggu Ini</th></tr></thead>
      <tbody>
        @foreach($trendingStores as $i => $store)
        <tr>
          <td><span style="font-weight:900;color:{{ $i===0?'#ffd700':($i===1?'#c0c0c0':($i===2?'#cd7f32':'var(--text-muted)')) }}">#{{ $i+1 }}</span></td>
          <td style="font-weight:700;color:white">{{ $store->name }}</td>
          <td><span class="badge badge-warning">{{ $store->weekly_txn }} transaksi</span></td>
        </tr>
        @endforeach
      </tbody>
    </table>
  @endif
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:24px">
  <div class="table-card">
    <div class="table-header"><h3>User Terbaru</h3><a href="{{ route('admin.users') }}" class="btn btn-secondary btn-sm">Lihat Semua</a></div>
    <table class="data-table">
      <thead><tr><th>Nama</th><th>Role</th><th>Status</th></tr></thead>
      <tbody>
        @foreach($recentUsers as $user)
        <tr>
          <td style="color:white">{{ $user->name }}<br/><small style="color:var(--text-muted)">{{ $user->email }}</small></td>
          <td><span class="badge {{ $user->role==='admin'?'badge-danger':($user->role==='seller'?'badge-primary':'badge-success') }}">{{ $user->role }}</span></td>
          <td><span class="badge {{ $user->is_banned?'badge-danger':'badge-success' }}">{{ $user->is_banned?'Banned':'Aktif' }}</span></td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
  <div class="table-card">
    <div class="table-header"><h3>Transaksi Terbaru</h3><a href="{{ route('admin.transactions') }}" class="btn btn-secondary btn-sm">Lihat Semua</a></div>
    <table class="data-table">
      <thead><tr><th>Order ID</th><th>Toko</th><th>Total</th><th>Status</th></tr></thead>
      <tbody>
        @foreach($recentOrders as $order)
        <tr>
          <td><code style="color:var(--primary-light);font-size:0.72rem">{{ $order->order_code }}</code></td>
          <td>{{ $order->store->name }}</td>
          <td style="font-weight:700;color:white">{{ $order->formattedTotal() }}</td>
          <td><span class="badge {{ $order->statusBadgeClass() }}">{{ $order->statusLabel() }}</span></td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>
@endsection
