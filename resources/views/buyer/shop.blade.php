@extends('layouts.dashboard')
@section('title','Belanja')
@section('page-title','Belanja')
@section('page-subtitle','Temukan produk favoritmu')

@section('sidebar-menu')
<div class="sidebar-section">Menu</div>
<a class="sidebar-link" href="{{ route('buyer.dashboard') }}"><i class="fas fa-home"></i> Dashboard</a>
<a class="sidebar-link active" href="{{ route('buyer.shop') }}"><i class="fas fa-store"></i> Belanja</a>
<a class="sidebar-link" href="{{ route('buyer.cart') }}"><i class="fas fa-shopping-cart"></i> Keranjang</a>
<a class="sidebar-link" href="{{ route('buyer.orders') }}"><i class="fas fa-history"></i> Riwayat</a>
@endsection

@section('content')
{{-- SEARCH & FILTER --}}
<form method="GET" action="{{ route('buyer.shop') }}" style="margin-bottom:28px">
  <div class="filter-bar">
    <div class="search-wrapper">
      <i class="fas fa-search"></i>
      <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari produk atau toko..." class="search-input"/>
    </div>
    <div class="filter-tags">
      <a href="{{ route('buyer.shop') }}" class="tag {{ !request('category')?'active':'' }}">✨ Semua</a>
      <a href="{{ route('buyer.shop','?category=makanan') }}" class="tag {{ request('category')==='makanan'?'active':'' }}">🍱 Makanan</a>
      <a href="{{ route('buyer.shop','?category=minuman') }}" class="tag {{ request('category')==='minuman'?'active':'' }}">🥤 Minuman</a>
      <a href="{{ route('buyer.shop','?category=fashion') }}" class="tag {{ request('category')==='fashion'?'active':'' }}">👕 Fashion</a>
      <a href="{{ route('buyer.shop','?category=jasa') }}" class="tag {{ request('category')==='jasa'?'active':'' }}">🛠️ Jasa</a>
      <a href="{{ route('buyer.shop','?category=lainnya') }}" class="tag {{ request('category')==='lainnya'?'active':'' }}">📦 Lainnya</a>
    </div>
  </div>
</form>

@if(session('success'))
  <div style="background:rgba(16,185,129,0.1);border:1px solid rgba(16,185,129,0.2);color:#34d399;padding:12px 16px;border-radius:10px;margin-bottom:16px;font-weight:700">
    <i class="fas fa-check-circle"></i> {{ session('success') }}
  </div>
@endif
@if(session('cart_error'))
  <div style="background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.2);color:#f87171;padding:12px 16px;border-radius:10px;margin-bottom:16px;font-weight:700">
    <i class="fas fa-exclamation-circle"></i> {{ session('cart_error') }}
  </div>
@endif

{{-- PRODUCTS GRID --}}
@if($products->isEmpty())
  <div style="text-align:center;padding:80px 20px">
    <div style="font-size:4rem;margin-bottom:16px;opacity:0.25">🛍️</div>
    <h3 style="color:white;font-size:1.2rem;font-weight:800;margin-bottom:10px">Belum Ada Produk</h3>
    <p style="color:var(--text-muted);font-size:0.88rem">Produk akan tampil setelah penjual mengupload produk mereka.</p>
  </div>
@else
  <div class="products-grid">
    @foreach($products as $product)
      <div class="product-card">
        <div class="product-img" style="background:{{ $product->categoryGradient() }};position:relative">
          @if($product->image_path)
            <img src="{{ $product->imageUrl() }}" alt="{{ $product->name }}" style="width:100%;height:100%;object-fit:cover;position:absolute;inset:0"/>
          @else
            <span>{{ $product->displayEmoji() }}</span>
          @endif
        </div>
        <div class="product-info">
          <div class="product-store"><i class="fas fa-store"></i> {{ $product->store->name }}</div>
          <div class="product-name">{{ $product->name }}</div>
          @if($product->description)
            <div style="font-size:0.75rem;color:var(--text-muted);margin-bottom:6px;overflow:hidden;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical">{{ $product->description }}</div>
          @endif
          <div class="product-price">{{ $product->formattedPrice() }}</div>
          <div class="product-footer">
            <span class="product-stock"><i class="fas fa-box"></i> Stok: {{ $product->stock }}</span>
            <form method="POST" action="{{ route('buyer.cart.add') }}" style="display:inline">
              @csrf
              <input type="hidden" name="product_id" value="{{ $product->id }}"/>
              <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-cart-plus"></i></button>
            </form>
          </div>
        </div>
      </div>
    @endforeach
  </div>
  <div style="margin-top:24px">{{ $products->links() }}</div>
@endif
@endsection
