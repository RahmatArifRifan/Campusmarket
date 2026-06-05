@extends('layouts.dashboard')
@section('title','Semua Transaksi')
@section('page-title','Semua Transaksi')
@section('page-subtitle','Pantau semua transaksi platform')

@section('sidebar-menu')
<div class="sidebar-section">Admin Panel</div>
<a class="sidebar-link" href="{{ route('admin.dashboard') }}"><i class="fas fa-tachometer-alt"></i> Overview</a>
<a class="sidebar-link" href="{{ route('admin.users') }}"><i class="fas fa-users"></i> Manajemen User</a>
<a class="sidebar-link" href="{{ route('admin.stores') }}"><i class="fas fa-store"></i> Manajemen Toko</a>
<a class="sidebar-link active" href="{{ route('admin.transactions') }}"><i class="fas fa-exchange-alt"></i> Transaksi</a>
@endsection

@section('content')
<div class="table-card">
  <div class="table-header">
    <h3>Semua Transaksi ({{ $orders->total() }})</h3>
    <form method="GET" action="{{ route('admin.transactions') }}" style="display:flex;gap:8px">
      <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari order ID / pembeli..." class="search-input" style="width:220px"/>
      <select name="status" style="padding:8px 12px;background:var(--glass);border:1px solid var(--glass-border);border-radius:8px;color:white;font-size:0.85rem" onchange="this.form.submit()">
        <option value="">Semua Status</option>
        <option value="pending" {{ request('status')==='pending'?'selected':'' }}>Pending</option>
        <option value="done" {{ request('status')==='done'?'selected':'' }}>Selesai</option>
        <option value="cancelled" {{ request('status')==='cancelled'?'selected':'' }}>Dibatalkan</option>
      </select>
      <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-search"></i></button>
    </form>
  </div>
  <table class="data-table">
    <thead><tr><th>Order ID</th><th>Pembeli</th><th>Toko</th><th>Item</th><th>Total</th><th>Bayar</th><th>Status</th><th>Tanggal</th><th>Aksi</th></tr></thead>
    <tbody>
      @foreach($orders as $order)
      <tr>
        <td><code style="color:var(--primary-light);font-size:0.72rem">{{ $order->order_code }}</code></td>
        <td>{{ $order->buyer->name }}</td>
        <td>{{ $order->store->name }}</td>
        <td>{{ $order->items->sum('quantity') }}</td>
        <td style="font-weight:700;color:white">{{ $order->formattedTotal() }}</td>
        <td><span class="badge badge-info">{{ strtoupper($order->payment_method) }}</span></td>
        <td><span class="badge {{ $order->statusBadgeClass() }}">{{ $order->statusLabel() }}</span></td>
        <td style="color:var(--text-muted)">{{ $order->created_at->format('d/m/Y') }}</td>
        <td>
          @if($order->status === 'pending')
            <form method="POST" action="{{ route('admin.transactions.cancel',$order) }}" onsubmit="return confirm('Batalkan pesanan ini?')">
              @csrf
              <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-times"></i> Batalkan</button>
            </form>
          @else — @endif
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>
  <div style="margin-top:16px">{{ $orders->links() }}</div>
</div>
@endsection
