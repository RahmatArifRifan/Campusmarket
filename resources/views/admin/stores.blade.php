@extends('layouts.dashboard')
@section('title','Manajemen Toko')
@section('page-title','Manajemen Toko')
@section('page-subtitle','Pantau semua toko aktif')

@section('sidebar-menu')
<div class="sidebar-section">Admin Panel</div>
<a class="sidebar-link" href="{{ route('admin.dashboard') }}"><i class="fas fa-tachometer-alt"></i> Overview</a>
<a class="sidebar-link" href="{{ route('admin.users') }}"><i class="fas fa-users"></i> Manajemen User</a>
<a class="sidebar-link active" href="{{ route('admin.stores') }}"><i class="fas fa-store"></i> Manajemen Toko</a>
<a class="sidebar-link" href="{{ route('admin.transactions') }}"><i class="fas fa-exchange-alt"></i> Transaksi</a>
@endsection

@section('content')
<div class="table-card">
  <div class="table-header">
    <h3>Semua Toko ({{ $stores->total() }})</h3>
    <form method="GET" action="{{ route('admin.stores') }}">
      <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari toko..." class="search-input" style="width:220px"/>
    </form>
  </div>
  @if($stores->isEmpty())
    <p style="text-align:center;padding:40px;color:var(--text-muted)">Belum ada toko terdaftar</p>
  @else
    <table class="data-table">
      <thead><tr><th>Toko</th><th>Pemilik</th><th>Kategori</th><th>Produk</th><th>Pesanan</th><th>Status</th><th>Aksi</th></tr></thead>
      <tbody>
        @foreach($stores as $store)
        <tr>
          <td>
            <div style="display:flex;align-items:center;gap:10px">
              <div style="width:36px;height:36px;border-radius:8px;background:linear-gradient(135deg,#667eea,#764ba2);display:flex;align-items:center;justify-content:center;overflow:hidden;flex-shrink:0">
                @if($store->logo_path)<img src="{{ $store->logoUrl() }}" style="width:100%;height:100%;object-fit:cover"/>
                @else <span style="font-size:1.2rem">{{ $store->logo_emoji }}</span> @endif
              </div>
              <span style="color:white;font-weight:700">{{ $store->name }}</span>
            </div>
          </td>
          <td>{{ $store->owner->name }}</td>
          <td><span class="badge badge-info">{{ $store->category }}</span></td>
          <td>{{ $store->products_count }}</td>
          <td>{{ $store->orders_count }}</td>
          <td><span class="badge {{ $store->is_banned?'badge-danger':'badge-success' }}">{{ $store->is_banned?'Nonaktif':'Aktif' }}</span></td>
          <td>
            <form method="POST" action="{{ route('admin.stores.toggle-ban',$store) }}">
              @csrf
              <button type="submit" class="btn {{ $store->is_banned?'btn-success':'btn-danger' }} btn-sm">
                <i class="fas fa-{{ $store->is_banned?'check':'ban' }}"></i>
                {{ $store->is_banned?'Aktifkan':'Nonaktifkan' }}
              </button>
            </form>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
    <div style="margin-top:16px">{{ $stores->links() }}</div>
  @endif
</div>
@endsection
