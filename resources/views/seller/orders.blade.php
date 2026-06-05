@extends('layouts.dashboard')
@section('title','Pesanan Masuk')
@section('page-title','Pesanan Masuk')
@section('page-subtitle','Validasi QR Code pembeli')

@section('sidebar-menu')
<div class="sidebar-section">Menu</div>
<a class="sidebar-link" href="{{ route('seller.dashboard') }}"><i class="fas fa-chart-bar"></i> Overview</a>
<a class="sidebar-link" href="{{ route('seller.products') }}"><i class="fas fa-box-open"></i> Produk Saya</a>
<a class="sidebar-link active" href="{{ route('seller.orders') }}"><i class="fas fa-qrcode"></i> Pesanan Masuk</a>
<a class="sidebar-link" href="{{ route('seller.store.profile') }}"><i class="fas fa-store-alt"></i> Profil Toko</a>
@endsection

@section('content')
<div style="display:grid;grid-template-columns:1fr 360px;gap:24px;align-items:start">

  {{-- DAFTAR PESANAN --}}
  <div class="table-card">
    <div class="table-header"><h3>Semua Pesanan</h3></div>
    @if($orders->isEmpty())
      <div style="text-align:center;padding:50px;color:var(--text-muted)">
        <i class="fas fa-inbox" style="font-size:3rem;display:block;margin-bottom:12px;opacity:0.2"></i>
        <p>Belum ada pesanan masuk</p>
      </div>
    @else
      <table class="data-table">
        <thead><tr><th>Order ID</th><th>Pembeli</th><th>Item</th><th>Total</th><th>Bayar</th><th>Status</th><th>Aksi</th></tr></thead>
        <tbody>
          @foreach($orders as $order)
          <tr>
            <td><code style="color:var(--primary-light);font-size:0.72rem">{{ $order->order_code }}</code></td>
            <td>{{ $order->buyer->name }}</td>
            <td>{{ $order->items->sum('quantity') }} item</td>
            <td style="font-weight:700;color:white">{{ $order->formattedTotal() }}</td>
            <td><span class="badge badge-info">{{ strtoupper($order->payment_method) }}</span></td>
            <td><span class="badge {{ $order->statusBadgeClass() }}">{{ $order->statusLabel() }}</span></td>
            <td>
              @if($order->status === 'pending')
                <form method="POST" action="{{ route('seller.orders.complete',$order) }}">
                  @csrf
                  <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('Selesaikan pesanan ini?')"><i class="fas fa-check"></i> Selesai</button>
                </form>
              @else — @endif
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    @endif
  </div>

  {{-- SCANNER QR --}}
  <div class="form-card">
    <h3 style="margin-bottom:8px"><i class="fas fa-qrcode" style="color:var(--primary-light)"></i> Validasi QR Code</h3>
    <p style="color:var(--text-muted);font-size:0.85rem;margin-bottom:16px">Masukkan Order ID dari QR Code pembeli</p>

    <div style="border:2px dashed rgba(99,102,241,0.3);border-radius:14px;padding:24px;text-align:center;margin-bottom:16px;background:rgba(99,102,241,0.05)">
      <i class="fas fa-qrcode" style="font-size:3rem;color:var(--primary-light);margin-bottom:10px;display:block"></i>
      <p style="color:var(--text-muted);font-size:0.82rem">Minta pembeli tunjukkan QR Code, lalu masukkan Order ID di bawah</p>
    </div>

    <form method="POST" action="{{ route('seller.orders.validate') }}">
      @csrf
      <div class="form-group">
        <label>Order ID</label>
        <input type="text" name="order_code" placeholder="CM-XXXXXXXX" style="font-family:monospace;letter-spacing:1px;text-transform:uppercase"/>
      </div>
      <button type="submit" class="btn btn-primary" style="width:100%">
        <i class="fas fa-check-circle"></i> Validasi & Selesaikan
      </button>
    </form>

    @if(session('scan_error'))
      <div style="background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.2);color:#f87171;padding:14px;border-radius:10px;font-weight:700;margin-top:14px">
        <i class="fas fa-times-circle"></i> {{ session('scan_error') }}
      </div>
    @endif

    @if(session('scan_order'))
      @php $scanOrder = session('scan_order'); @endphp
      <div style="background:rgba(99,102,241,0.08);border:1px solid rgba(99,102,241,0.3);border-radius:12px;padding:18px;margin-top:14px">
        <p style="font-weight:800;color:white;margin-bottom:12px"><i class="fas fa-check-circle" style="color:#34d399"></i> Pesanan Ditemukan!</p>
        <div style="font-size:0.85rem;display:flex;flex-direction:column;gap:6px;margin-bottom:14px">
          <div style="display:flex;justify-content:space-between"><span style="color:var(--text-muted)">Pembeli</span><b style="color:white">{{ $scanOrder->buyer->name }}</b></div>
          <div style="display:flex;justify-content:space-between"><span style="color:var(--text-muted)">Total</span><b style="color:var(--primary-light)">{{ $scanOrder->formattedTotal() }}</b></div>
          <div style="display:flex;justify-content:space-between"><span style="color:var(--text-muted)">Pembayaran</span><b style="color:white">{{ strtoupper($scanOrder->payment_method) }}</b></div>
        </div>
        <form method="POST" action="{{ route('seller.orders.complete',$scanOrder) }}">
          @csrf
          <button type="submit" class="btn btn-success" style="width:100%"><i class="fas fa-check"></i> Konfirmasi & Selesaikan</button>
        </form>
      </div>
    @endif
  </div>
</div>
@endsection
