@extends('layouts.dashboard')
@section('title','Keranjang')
@section('page-title','Keranjang Belanja')
@section('page-subtitle','Review pesananmu sebelum checkout')

@section('sidebar-menu')
<div class="sidebar-section">Menu</div>
<a class="sidebar-link" href="{{ route('buyer.dashboard') }}"><i class="fas fa-home"></i> Dashboard</a>
<a class="sidebar-link" href="{{ route('buyer.shop') }}"><i class="fas fa-store"></i> Belanja</a>
<a class="sidebar-link active" href="{{ route('buyer.cart') }}"><i class="fas fa-shopping-cart"></i> Keranjang</a>
<a class="sidebar-link" href="{{ route('buyer.orders') }}"><i class="fas fa-history"></i> Riwayat</a>
@endsection

@section('content')
@if(empty($storeGroups))
  <div style="text-align:center;padding:80px 20px">
    <div style="font-size:4rem;margin-bottom:16px;opacity:0.2">🛒</div>
    <h3 style="color:white;font-size:1.2rem;font-weight:800;margin-bottom:8px">Keranjang Kosong</h3>
    <p style="color:var(--text-muted);margin-bottom:20px">Yuk belanja dulu!</p>
    <a href="{{ route('buyer.shop') }}" class="btn btn-primary"><i class="fas fa-store"></i> Mulai Belanja</a>
  </div>
@else
<div style="display:grid;grid-template-columns:1fr 360px;gap:24px;align-items:start">

  {{-- CART ITEMS PER TOKO --}}
  <div>
    @foreach($storeGroups as $storeGroup)
      <div style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.1);border-radius:16px;margin-bottom:20px;overflow:hidden">

        {{-- Header Toko --}}
        <div style="display:flex;align-items:center;justify-content:space-between;padding:14px 18px;background:rgba(99,102,241,0.1);border-bottom:1px solid rgba(255,255,255,0.08)">
          <div style="display:flex;align-items:center;gap:10px">
            <div style="width:36px;height:36px;border-radius:10px;background:linear-gradient(135deg,#667eea,#764ba2);display:flex;align-items:center;justify-content:center">🏪</div>
            <div>
              <div style="font-weight:800;color:white">{{ $storeGroup['store_name'] }}</div>
              <div style="font-size:0.75rem;color:var(--text-muted)">
                {{ collect($storeGroup['items'])->sum('qty') }} item · Rp {{ number_format($storeGroup['total'],0,',','.') }}
              </div>
            </div>
          </div>
          <form method="POST" action="{{ route('buyer.cart.clear-store') }}">
            @csrf
            <input type="hidden" name="store_id" value="{{ $storeGroup['store_id'] }}"/>
            <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i> Hapus Toko</button>
          </form>
        </div>

        {{-- Items --}}
        <div style="padding:0 18px">
          @foreach($storeGroup['items'] as $item)
            <div style="display:flex;align-items:center;gap:14px;padding:16px 0;border-bottom:1px solid rgba(255,255,255,0.06)">
              <div style="width:56px;height:56px;border-radius:12px;background:{{ $item['product']->categoryGradient() }};display:flex;align-items:center;justify-content:center;font-size:1.8rem;overflow:hidden;flex-shrink:0">
                @if($item['product']->image_path)
                  <img src="{{ $item['product']->imageUrl() }}" style="width:100%;height:100%;object-fit:cover"/>
                @else
                  {{ $item['product']->displayEmoji() }}
                @endif
              </div>
              <div style="flex:1">
                <div style="font-weight:700;color:white">{{ $item['product']->name }}</div>
                <div style="font-weight:800;color:var(--primary-light)">{{ $item['product']->formattedPrice() }}</div>
              </div>
              {{-- Update Qty --}}
              <form method="POST" action="{{ route('buyer.cart.update') }}" style="display:flex;align-items:center;gap:8px">
                @csrf
                <input type="hidden" name="product_id" value="{{ $item['product']->id }}"/>
                <input type="hidden" name="store_id" value="{{ $storeGroup['store_id'] }}"/>
                <button type="button" class="qty-btn" onclick="stepQty(this,-1)">−</button>
                <input type="number" name="qty" value="{{ $item['qty'] }}" min="1" max="99"
                  style="width:50px;text-align:center;background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.1);border-radius:8px;color:white;padding:6px;font-weight:700"
                  onchange="this.form.submit()"/>
                <button type="button" class="qty-btn" onclick="stepQty(this,1)">+</button>
              </form>
              {{-- Remove --}}
              <form method="POST" action="{{ route('buyer.cart.remove') }}">
                @csrf
                <input type="hidden" name="product_id" value="{{ $item['product']->id }}"/>
                <input type="hidden" name="store_id" value="{{ $storeGroup['store_id'] }}"/>
                <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
              </form>
            </div>
          @endforeach
        </div>

        {{-- Checkout per toko --}}
        <div style="padding:14px 18px;border-top:1px solid rgba(255,255,255,0.06);display:flex;justify-content:space-between;align-items:center">
          <div>
            <div style="font-size:0.8rem;color:var(--text-muted)">Total toko ini</div>
            <div style="font-weight:900;color:var(--primary-light);font-size:1.1rem">Rp {{ number_format($storeGroup['total'],0,',','.') }}</div>
          </div>
          <a href="{{ route('buyer.checkout') }}?store={{ $storeGroup['store_id'] }}" class="btn btn-primary">
            <i class="fas fa-bolt"></i> Checkout Toko Ini
          </a>
        </div>
      </div>
    @endforeach
  </div>

  {{-- RINGKASAN --}}
  <div class="form-card">
    <h3 style="margin-bottom:16px">Ringkasan Semua Toko</h3>
    <div class="cart-summary">
      @foreach($storeGroups as $sg)
        <div class="cart-summary-row">
          <span style="color:var(--text-muted);font-size:0.85rem">{{ $sg['store_name'] }}</span>
          <span style="color:white;font-weight:700">Rp {{ number_format($sg['total'],0,',','.') }}</span>
        </div>
      @endforeach
      <div class="cart-summary-row" style="border-top:1px solid rgba(255,255,255,0.1);padding-top:10px;margin-top:6px">
        <span style="font-weight:800;color:white">Grand Total</span>
        <span style="color:var(--primary-light);font-weight:900;font-size:1.05rem">Rp {{ number_format($grandTotal,0,',','.') }}</span>
      </div>
      <div class="cart-summary-row">
        <span style="color:var(--text-muted);font-size:0.78rem">Biaya layanan</span>
        <span style="color:var(--green);font-weight:700">Gratis</span>
      </div>
    </div>

    <p style="text-align:center;font-size:0.78rem;color:var(--text-muted);margin-top:14px">
      <i class="fas fa-info-circle"></i> Checkout dilakukan per toko — klik tombol di masing-masing toko
    </p>

    {{-- BULK CHECKOUT --}}
    <div style="margin-top:16px;padding:16px;background:rgba(99,102,241,0.08);border:1px solid rgba(99,102,241,0.25);border-radius:12px">
      <p style="font-size:0.82rem;color:var(--text-muted);margin-bottom:12px;text-align:center">
        <i class="fas fa-bolt" style="color:var(--primary-light)"></i> Atau checkout semua toko sekaligus
      </p>
      <div class="payment-options" style="margin-bottom:12px" id="bulkPaymentOptions">
        <label class="payment-option selected" style="cursor:pointer" onclick="setBulkPayment(this)">
          <input type="radio" name="bulk_payment" value="tunai" checked style="display:none"/>
          <i class="fas fa-money-bill-wave"></i>
          <div><span>Tunai</span></div>
        </label>
        <label class="payment-option" style="cursor:pointer" onclick="setBulkPayment(this)">
          <input type="radio" name="bulk_payment" value="qris" style="display:none"/>
          <i class="fas fa-qrcode"></i>
          <div><span>QRIS</span></div>
        </label>
        <label class="payment-option" style="cursor:pointer" onclick="setBulkPayment(this)">
          <input type="radio" name="bulk_payment" value="transfer" style="display:none"/>
          <i class="fas fa-university"></i>
          <div><span>Transfer</span></div>
        </label>
      </div>
      <form method="POST" action="{{ route('buyer.checkout.bulk') }}" id="bulkCheckoutForm">
        @csrf
        <input type="hidden" name="payment_method" id="bulkPaymentMethod" value="tunai"/>
        <button type="submit" class="btn btn-primary" style="width:100%;padding:14px"
          onclick="return confirm('Checkout semua {{ count($storeGroups) }} toko sekaligus?')">
          <i class="fas fa-bolt"></i> Checkout Semua ({{ count($storeGroups) }} Toko)
        </button>
      </form>
    </div>

    <form method="POST" action="{{ route('buyer.cart.clear') }}" style="margin-top:12px">
      @csrf
      <button type="submit" class="btn btn-danger" style="width:100%" onclick="return confirm('Kosongkan semua keranjang?')">
        <i class="fas fa-trash"></i> Kosongkan Semua
      </button>
    </form>
  </div>

</div>
@endif

@push('scripts')
<script>
function stepQty(btn, dir) {
  const form  = btn.closest('form');
  const input = form.querySelector('input[name="qty"]');
  const val   = parseInt(input.value) + dir;
  if (val >= 1 && val <= 99) {
    input.value = val;
    form.submit();
  }
}
function setBulkPayment(label) {
  document.querySelectorAll('#bulkPaymentOptions .payment-option').forEach(o => o.classList.remove('selected'));
  label.classList.add('selected');
  document.getElementById('bulkPaymentMethod').value = label.querySelector('input').value;
}
document.querySelectorAll('.payment-option').forEach(opt => {
  opt.addEventListener('click', function () {
    document.querySelectorAll('.payment-option').forEach(o => o.classList.remove('selected'));
    this.classList.add('selected');
  });
});
</script>
@endpush
@endsection
