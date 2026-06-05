@extends('layouts.dashboard')
@section('title','Checkout')
@section('page-title','Checkout')
@section('page-subtitle','Review & konfirmasi pesananmu')

@section('sidebar-menu')
<div class="sidebar-section">Menu</div>
<a class="sidebar-link" href="{{ route('buyer.dashboard') }}"><i class="fas fa-home"></i> Dashboard</a>
<a class="sidebar-link" href="{{ route('buyer.shop') }}"><i class="fas fa-store"></i> Belanja</a>
<a class="sidebar-link active" href="{{ route('buyer.cart') }}"><i class="fas fa-shopping-cart"></i> Keranjang</a>
<a class="sidebar-link" href="{{ route('buyer.orders') }}"><i class="fas fa-history"></i> Riwayat</a>
@endsection

@section('content')

{{-- Jika ada lebih dari 1 toko, tampilkan pilihan --}}
@if(count($storeGroups) > 1)
  <div class="dash-alert dash-alert-error" style="margin-bottom:20px">
    <i class="fas fa-info-circle"></i>
    Kamu punya item dari <strong>{{ count($storeGroups) }} toko</strong>. Checkout dilakukan per toko.
    Silakan pilih toko yang ingin kamu checkout sekarang.
  </div>
@endif

@foreach($storeGroups as $storeGroup)
  {{-- Sorot toko yang dipilih via ?store= query param --}}
  @php $isSelected = request('store') == $storeGroup['store_id']; @endphp
  <div style="display:grid;grid-template-columns:1fr 360px;gap:24px;align-items:start;margin-bottom:40px;
    {{ $isSelected ? 'border:2px solid var(--primary-light);border-radius:16px;padding:16px;' : (count($storeGroups) > 1 ? 'opacity:0.5;' : '') }}">

    {{-- Detail Pesanan --}}
    <div class="table-card">
      <h3 style="margin-bottom:20px">
        <i class="fas fa-receipt" style="color:var(--primary-light);margin-right:8px"></i>
        {{ $storeGroup['store_name'] }}
      </h3>

      @foreach($storeGroup['items'] as $item)
        <div style="display:flex;align-items:center;gap:14px;padding:14px 0;border-bottom:1px solid rgba(255,255,255,0.06)">
          <div style="width:52px;height:52px;border-radius:12px;background:{{ $item['product']->categoryGradient() }};display:flex;align-items:center;justify-content:center;font-size:1.6rem;overflow:hidden;flex-shrink:0">
            @if($item['product']->image_path)
              <img src="{{ $item['product']->imageUrl() }}" style="width:100%;height:100%;object-fit:cover"/>
            @else
              {{ $item['product']->displayEmoji() }}
            @endif
          </div>
          <div style="flex:1">
            <div style="font-weight:700;color:white">{{ $item['product']->name }}</div>
            <div style="font-size:0.8rem;color:var(--text-muted)">{{ $item['product']->formattedPrice() }} × {{ $item['qty'] }}</div>
          </div>
          <div style="font-weight:800;color:var(--primary-light)">Rp {{ number_format($item['subtotal'],0,',','.') }}</div>
        </div>
      @endforeach

      <div style="display:flex;justify-content:space-between;padding:16px 0 0;font-weight:900;font-size:1.05rem">
        <span style="color:white">Total</span>
        <span style="color:var(--primary-light)">Rp {{ number_format($storeGroup['total'],0,',','.') }}</span>
      </div>
    </div>

    {{-- Form Pembayaran --}}
    <div class="form-card">
      <h3 style="margin-bottom:16px">Metode Pembayaran</h3>
      <form method="POST" action="{{ route('buyer.checkout.process') }}" id="checkoutForm-{{ $storeGroup['store_id'] }}">
        @csrf
        {{-- store_id wajib dikirim untuk processCheckout --}}
        <input type="hidden" name="store_id" value="{{ $storeGroup['store_id'] }}"/>

        <div class="payment-options" style="margin-bottom:20px">
          <label class="payment-option selected" style="cursor:pointer"
            onclick="setPayment(this, {{ $storeGroup['store_id'] }})">
            <input type="radio" name="payment_method" value="tunai" checked style="display:none"/>
            <i class="fas fa-money-bill-wave"></i>
            <div><span>Tunai</span><small>Bayar langsung ke penjual</small></div>
          </label>
          <label class="payment-option" style="cursor:pointer"
            onclick="setPayment(this, {{ $storeGroup['store_id'] }})">
            <input type="radio" name="payment_method" value="qris" style="display:none"/>
            <i class="fas fa-qrcode"></i>
            <div><span>QRIS</span><small>Scan QR penjual</small></div>
          </label>
          <label class="payment-option" style="cursor:pointer"
            onclick="setPayment(this, {{ $storeGroup['store_id'] }})">
            <input type="radio" name="payment_method" value="transfer" style="display:none"/>
            <i class="fas fa-university"></i>
            <div><span>Transfer Bank</span><small>Transfer ke rekening penjual</small></div>
          </label>
        </div>

        <div style="padding:14px;background:rgba(255,255,255,0.04);border-radius:10px;margin-bottom:16px">
          <div style="display:flex;justify-content:space-between;margin-bottom:6px;font-size:0.88rem">
            <span style="color:var(--text-muted)">Toko</span>
            <span style="color:white;font-weight:700">{{ $storeGroup['store_name'] }}</span>
          </div>
          <div style="display:flex;justify-content:space-between;font-size:0.88rem">
            <span style="color:var(--text-muted)">Total Bayar</span>
            <span style="color:var(--primary-light);font-weight:900;font-size:1.05rem">Rp {{ number_format($storeGroup['total'],0,',','.') }}</span>
          </div>
        </div>

        <button type="submit" class="btn btn-primary" style="width:100%;padding:14px">
          <i class="fas fa-bolt"></i> Konfirmasi & Dapatkan QR Code
        </button>
      </form>

      <a href="{{ route('buyer.cart') }}" class="btn btn-secondary" style="width:100%;margin-top:10px;justify-content:center">
        <i class="fas fa-arrow-left"></i> Kembali ke Keranjang
      </a>
    </div>

  </div>
@endforeach

@push('scripts')
<script>
function setPayment(label, storeId) {
  const form = document.getElementById('checkoutForm-' + storeId);
  form.querySelectorAll('.payment-option').forEach(o => o.classList.remove('selected'));
  label.classList.add('selected');
  label.querySelector('input[type="radio"]').checked = true;
}
</script>
@endpush
@endsection
