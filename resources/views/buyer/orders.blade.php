@extends('layouts.dashboard')
@section('title','Riwayat Transaksi')
@section('page-title','Riwayat Transaksi')
@section('page-subtitle','Semua transaksi kamu')

@section('sidebar-menu')
<div class="sidebar-section">Menu</div>
<a class="sidebar-link" href="{{ route('buyer.dashboard') }}"><i class="fas fa-home"></i> Dashboard</a>
<a class="sidebar-link" href="{{ route('buyer.shop') }}"><i class="fas fa-store"></i> Belanja</a>
<a class="sidebar-link" href="{{ route('buyer.cart') }}"><i class="fas fa-shopping-cart"></i> Keranjang</a>
<a class="sidebar-link active" href="{{ route('buyer.orders') }}"><i class="fas fa-history"></i> Riwayat</a>
@endsection

@section('content')
<div class="table-card">
  <div class="table-header"><h3>Semua Pesanan</h3></div>
  @if($orders->isEmpty())
    <div style="text-align:center;padding:60px;color:var(--text-muted)">
      <i class="fas fa-history" style="font-size:3rem;display:block;margin-bottom:12px;opacity:0.2"></i>
      <p>Belum ada transaksi. <a href="{{ route('buyer.shop') }}" style="color:var(--primary-light)">Mulai belanja!</a></p>
    </div>
  @else
    <table class="data-table">
      <thead><tr><th>Order ID</th><th>Toko</th><th>Item</th><th>Total</th><th>Bayar</th><th>Status</th><th>Tanggal</th><th>Aksi</th></tr></thead>
      <tbody>
        @foreach($orders as $order)
        <tr>
          <td><code style="color:var(--primary-light);font-size:0.72rem">{{ $order->order_code }}</code></td>
          <td>{{ $order->store->name }}</td>
          <td>{{ $order->items->sum('quantity') }} item</td>
          <td style="font-weight:700;color:white">{{ $order->formattedTotal() }}</td>
          <td><span class="badge badge-info">{{ strtoupper($order->payment_method) }}</span></td>
          <td><span class="badge {{ $order->statusBadgeClass() }}">{{ $order->statusLabel() }}</span></td>
          <td style="color:var(--text-muted)">{{ $order->created_at->format('d/m/Y') }}</td>
          <td>
            @if($order->status === 'pending')
              <a href="{{ route('buyer.orders.qr',$order) }}" class="btn btn-primary btn-sm"><i class="fas fa-qrcode"></i> QR</a>
            @else — @endif
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
    <div style="margin-top:16px">{{ $orders->links() }}</div>
  @endif
</div>
@endsection
