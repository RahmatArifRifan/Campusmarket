@extends('layouts.app')
@section('title','Beranda')
@section('content')

{{-- HERO --}}
<section class="hero" id="home">
  <div class="hero-bg-orbs">
    <div class="orb orb-1"></div><div class="orb orb-2"></div><div class="orb orb-3"></div>
  </div>
  <div class="hero-inner">
    <div class="hero-content">
      <div class="hero-badge"><div class="badge-dot"></div> Platform O2O Kampus #1</div>
      <h1>Belanja Lebih Cerdas<br/><span class="gradient-text">Transaksi Lebih Aman</span><br/>di Kampusmu</h1>
      <p>CampusMarket menghubungkan mahasiswa pembeli dengan pengusaha kampus. Pesan online, ambil langsung — mudah, cepat, terpercaya.</p>
      <div class="hero-btns">
        <a href="{{ route('register') }}" class="btn btn-primary btn-lg"><i class="fas fa-rocket"></i> Mulai Belanja</a>
        <a href="{{ route('register') }}?role=seller" class="btn btn-secondary btn-lg"><i class="fas fa-store"></i> Buka Toko Gratis</a>
      </div>
      <div class="hero-stats">
        <div class="stat"><span class="stat-num">{{ $stats['users'] }}</span><span>Mahasiswa</span></div>
        <div class="stat"><span class="stat-num">{{ $stats['stores'] }}</span><span>Toko Aktif</span></div>
        <div class="stat"><span class="stat-num">{{ $stats['orders'] }}</span><span>Transaksi</span></div>
      </div>
    </div>
    <div class="hero-visual">
      <div class="phone-mockup">
        <div class="phone-screen">
          <div class="mock-header"><i class="fas fa-store"></i> CampusMarket</div>
          <div class="mock-search"><i class="fas fa-search"></i> Cari produk...</div>
          <div class="mock-cards">
            <div class="mock-card"><div class="mock-img food"></div><p>Makanan</p></div>
            <div class="mock-card"><div class="mock-img drink"></div><p>Minuman</p></div>
            <div class="mock-card"><div class="mock-img snack"></div><p>Jajanan</p></div>
            <div class="mock-card"><div class="mock-img merch"></div><p>Fashion</p></div>
          </div>
          <div class="mock-qr"><i class="fas fa-qrcode"></i> QR Pesanan Aktif</div>
        </div>
      </div>
      <div class="floating-badge badge-1"><i class="fas fa-check-circle"></i> Pesanan Dikonfirmasi!</div>
      <div class="floating-badge badge-2"><i class="fas fa-fire"></i> Toko Trending</div>
      <div class="floating-badge badge-3"><i class="fas fa-star"></i> Multi-Store Cart</div>
    </div>
  </div>
</section>

{{-- HOW IT WORKS --}}
<section class="how-it-works">
  <div class="container">
    <div class="section-header reveal">
      <div class="section-tag"><i class="fas fa-magic"></i> Cara Kerja</div>
      <h2>Semudah <span>4 Langkah</span></h2>
      <p>Proses belanja simpel dari online ke offline</p>
    </div>
    <div class="steps-grid">
      <div class="step-card reveal"><div class="step-num">01</div><div class="step-icon s1"><i class="fas fa-search"></i></div><h3>Telusuri & Pilih</h3><p>Jelajahi katalog produk dari berbagai toko kampus.</p></div>
      <div class="step-arrow reveal"><i class="fas fa-chevron-right"></i></div>
      <div class="step-card reveal"><div class="step-num">02</div><div class="step-icon s2"><i class="fas fa-shopping-cart"></i></div><h3>Multi-Store Cart</h3><p>Tambah produk dari berbagai toko sekaligus dalam satu keranjang.</p></div>
      <div class="step-arrow reveal"><i class="fas fa-chevron-right"></i></div>
      <div class="step-card reveal"><div class="step-num">03</div><div class="step-icon s3"><i class="fas fa-qrcode"></i></div><h3>Bulk Checkout</h3><p>1 klik checkout semua toko, QR Code masing-masing langsung muncul.</p></div>
      <div class="step-arrow reveal"><i class="fas fa-chevron-right"></i></div>
      <div class="step-card reveal"><div class="step-num">04</div><div class="step-icon s4"><i class="fas fa-handshake"></i></div><h3>Ambil di Lapak</h3><p>Tunjukkan QR ke penjual, ambil barang, selesai!</p></div>
    </div>
  </div>
</section>

{{-- KATALOG --}}
<section class="katalog-section" id="katalog">
  <div class="container">
    <div class="section-header reveal">
      <div class="section-tag"><i class="fas fa-th-large"></i> Katalog</div>
      <h2>Produk <span>Pilihan</span></h2>
      <p>Temukan berbagai produk dari toko-toko kampus</p>
    </div>
    <div class="filter-bar reveal">
      <div class="search-wrapper">
        <i class="fas fa-search"></i>
        <input type="text" id="searchInput" placeholder="Cari produk atau toko..." class="search-input" oninput="filterProducts()"/>
      </div>
      <div class="filter-tags">
        <button class="tag active" onclick="filterCategory('semua',this)">✨ Semua</button>
        <button class="tag" onclick="filterCategory('makanan',this)">🍱 Makanan</button>
        <button class="tag" onclick="filterCategory('minuman',this)">🥤 Minuman</button>
        <button class="tag" onclick="filterCategory('fashion',this)">👕 Fashion</button>
        <button class="tag" onclick="filterCategory('jasa',this)">🛠️ Jasa</button>
        <button class="tag" onclick="filterCategory('lainnya',this)">📦 Lainnya</button>
      </div>
    </div>

    @if($products->isEmpty())
      <div style="text-align:center;padding:80px 20px">
        <div style="font-size:4rem;margin-bottom:16px;opacity:0.25">🛍️</div>
        <h3 style="color:white;font-size:1.2rem;font-weight:800;margin-bottom:10px">Belum Ada Produk</h3>
        <p style="color:var(--text-muted);font-size:0.88rem;max-width:340px;margin:0 auto;line-height:1.8">
          Produk akan tampil di sini setelah penjual mendaftar dan mengupload produk mereka.
        </p>
        <a href="{{ route('register') }}?role=seller" class="btn btn-primary" style="margin-top:24px">
          <i class="fas fa-store"></i> Daftar sebagai Penjual
        </a>
      </div>
    @else
      <div class="products-grid" id="productsGrid">
        @foreach($products as $product)
          @php $shopUrl = auth()->check() && auth()->user()->isBuyer() ? route('buyer.shop') : route('register'); @endphp
          <div class="product-card reveal" onclick="window.location='{{ $shopUrl }}'">
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
              <div class="product-price">{{ $product->formattedPrice() }}</div>
              <div class="product-footer">
                <span class="product-stock"><i class="fas fa-box"></i> Stok: {{ $product->stock }}</span>
                <a href="{{ $shopUrl }}" class="btn btn-primary btn-sm" onclick="event.stopPropagation()"><i class="fas fa-cart-plus"></i></a>
              </div>
            </div>
          </div>
        @endforeach
      </div>
    @endif
  </div>
</section>

{{-- HOT TRENDING --}}
<section class="trending-section" id="trending">
  <div class="container">
    <div class="section-header reveal">
      <div class="section-tag"><i class="fas fa-fire"></i> Hot Trending</div>
      <h2>Toko <span>Paling Ramai</span></h2>
      <p>Diperbarui otomatis berdasarkan transaksi terbanyak 7 hari terakhir</p>
    </div>
    @if($trendingStores->isEmpty())
      <div style="text-align:center;padding:80px 20px">
        <div style="font-size:4rem;margin-bottom:16px;opacity:0.25">🔥</div>
        <h3 style="color:white;font-size:1.2rem;font-weight:800;margin-bottom:10px">Belum Ada Toko Trending</h3>
        <p style="color:var(--text-muted);font-size:0.88rem;max-width:340px;margin:0 auto;line-height:1.8">
          Toko trending akan muncul otomatis setelah web aktif dan transaksi berjalan.
        </p>
      </div>
    @else
      <div class="trending-grid">
        @foreach($trendingStores as $i => $store)
          <div class="trending-card reveal">
            <div class="trending-rank {{ $i===0?'rank-1':($i===1?'rank-2':($i===2?'rank-3':'rank-other')) }}">{{ $i+1 }}</div>
            <div class="trending-logo" style="background:linear-gradient(135deg,#667eea,#764ba2)">
              @if($store->logo_path)
                <img src="{{ $store->logoUrl() }}" style="width:100%;height:100%;object-fit:cover;border-radius:14px"/>
              @else
                {{ $store->logo_emoji }}
              @endif
            </div>
            <div class="trending-info">
              <div class="trending-name">{{ $store->name }}</div>
              <div class="trending-meta">{{ $store->category }}</div>
              <div class="trending-txn"><i class="fas fa-fire"></i>
                {{ $store->weekly_txn > 0 ? $store->weekly_txn.' transaksi minggu ini' : 'Baru bergabung' }}
              </div>
            </div>
            @if($i < 3)<i class="fas fa-fire trending-fire"></i>@endif
          </div>
        @endforeach
      </div>
    @endif
  </div>
</section>

{{-- TENTANG --}}
<section class="tentang-section" id="tentang">
  <div class="container tentang-container">
    <div class="tentang-text reveal">
      <div class="section-tag" style="margin-bottom:16px"><i class="fas fa-info-circle"></i> Tentang Kami</div>
      <h2>Platform Kampus<br/><span>Masa Depan</span></h2>
      <p>CampusMarket adalah platform marketplace O2O berbasis <b style="color:var(--primary-light)">Laravel 12</b> dan <b style="color:var(--primary-light)">MySQL</b> yang dirancang khusus untuk ekosistem kampus.</p>
      <ul class="tentang-list">
        <li><i class="fas fa-check-circle"></i> Transaksi aman dengan QR Code unik per toko</li>
        <li><i class="fas fa-check-circle"></i> Multi-Store Cart dengan Bulk Checkout</li>
        <li><i class="fas fa-check-circle"></i> Dashboard lengkap untuk semua peran</li>
        <li><i class="fas fa-check-circle"></i> Moderasi aktif oleh Admin kampus</li>
      </ul>
      <div class="dev-team">
        <h3><i class="fas fa-users" style="color:var(--primary-light);margin-right:8px"></i>Tim Developer</h3>
        <div class="team-grid">
          <div class="team-card"><div class="team-avatar">RA</div><p><b>Rahmat Arif Rifan</b><br/><small>Ketua — 2409010129</small></p></div>
          <div class="team-card"><div class="team-avatar">FL</div><p><b>Fathir Lazuardi O.</b><br/><small>2409010118</small></p></div>
          <div class="team-card"><div class="team-avatar">MR</div><p><b>M. Rifat Syahjahan</b><br/><small>2409010098</small></p></div>
          <div class="team-card"><div class="team-avatar">RF</div><p><b>Rifqy Fauzi P.</b><br/><small>2409010100</small></p></div>
          <div class="team-card"><div class="team-avatar">MP</div><p><b>M. Rio Pramadanu</b><br/><small>2409010115</small></p></div>
        </div>
      </div>
    </div>
    <div class="tentang-visual reveal">
      <div class="tech-stack-card">
        <h3><i class="fas fa-layer-group" style="color:var(--primary-light)"></i> Tech Stack</h3>
        <div class="tech-item"><i class="fab fa-html5" style="color:#e34f26"></i><span>HTML5 + CSS3 + Tailwind</span></div>
        <div class="tech-item"><i class="fab fa-js" style="color:#f7df1e"></i><span>Vanilla JavaScript</span></div>
        <div class="tech-item"><i class="fab fa-laravel" style="color:#fb503b"></i><span>Laravel 12</span></div>
        <div class="tech-item"><i class="fas fa-layer-group" style="color:#fb503b"></i><span>Laravel Blade</span></div>
        <div class="tech-item"><i class="fas fa-database" style="color:#4479a1"></i><span>MySQL 8.0+</span></div>
        <div class="tech-item"><i class="fas fa-qrcode" style="color:var(--primary-light)"></i><span>simple-qrcode</span></div>
        <div class="tech-item"><i class="fas fa-shield-alt" style="color:var(--green)"></i><span>Laravel Session Auth</span></div>
        <div class="tech-item"><i class="fas fa-robot" style="color:var(--pink)"></i><span>AI Tools (Gemini/GPT/Claude)</span></div>
      </div>
    </div>
  </div>
</section>

{{-- CTA --}}
<section class="cta-section">
  <div class="container cta-container reveal">
    <div class="section-tag" style="margin:0 auto 16px"><i class="fas fa-rocket"></i> Bergabung Sekarang</div>
    <h2>Siap Jadi Bagian dari CampusMarket?</h2>
    <p>Daftar gratis dan mulai pengalaman belanja kampus yang lebih modern</p>
    <div class="cta-btns">
      <a href="{{ route('register') }}" class="btn btn-white btn-lg"><i class="fas fa-user-plus"></i> Daftar sebagai Pembeli</a>
      <a href="{{ route('register') }}?role=seller" class="btn btn-outline-white btn-lg"><i class="fas fa-store"></i> Buka Toko Sekarang</a>
    </div>
  </div>
</section>

@endsection
@push('scripts')
<script>
function toggleMenu(){ document.getElementById('navLinks').classList.toggle('open'); }
function filterCategory(cat,el){
  document.querySelectorAll('.tag').forEach(t=>t.classList.remove('active'));
  el.classList.add('active');
}
function filterProducts(){}
// Scroll reveal
const observer = new IntersectionObserver(entries=>{
  entries.forEach((e,i)=>{ if(e.isIntersecting) setTimeout(()=>e.target.classList.add('visible'),i*80); });
},{threshold:0.1});
document.querySelectorAll('.reveal').forEach(el=>observer.observe(el));
// Navbar scroll
window.addEventListener('scroll',()=>document.getElementById('navbar').classList.toggle('scrolled',scrollY>30));
</script>
@endpush
