<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <meta name="csrf-token" content="{{ csrf_token() }}"/>
  <title>@yield('title', 'CampusMarket') — Platform Jual Beli Mahasiswa</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
  <link rel="preconnect" href="https://fonts.googleapis.com"/>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet"/>
  <link rel="stylesheet" href="{{ asset('css/app.css') }}"/>
  @stack('styles')
</head>
<body>

{{-- NAVBAR --}}
<nav class="navbar" id="navbar">
  <div class="nav-container">
    <a href="{{ route('home') }}" class="nav-logo">
      <div class="logo-icon"><i class="fas fa-store"></i></div>
      Campus<span>Market</span>
    </a>
    <ul class="nav-links" id="navLinks">
      <li><a href="{{ route('home') }}#home">Beranda</a></li>
      <li><a href="{{ route('home') }}#katalog">Katalog</a></li>
      <li><a href="{{ route('home') }}#trending">Hot Trending</a></li>
      <li><a href="{{ route('home') }}#tentang">Tentang</a></li>
    </ul>
    <div class="nav-actions">
      @guest
        <a href="{{ route('login') }}" class="btn btn-secondary btn-sm">Masuk</a>
        <a href="{{ route('register') }}" class="btn btn-primary btn-sm">Daftar Gratis</a>
      @else
        <a href="{{ route(auth()->user()->dashboardRoute()) }}" class="btn btn-primary btn-sm">
          <i class="fas fa-tachometer-alt"></i> Dashboard
        </a>
        <form method="POST" action="{{ route('logout') }}" style="display:inline">
          @csrf
          <button type="submit" class="btn btn-secondary btn-sm">
            <i class="fas fa-sign-out-alt"></i> Keluar
          </button>
        </form>
      @endguest
    </div>
    <button class="hamburger" id="hamburger" onclick="toggleMenu()">
      <i class="fas fa-bars"></i>
    </button>
  </div>
</nav>

{{-- FLASH MESSAGES --}}
@if(session('success'))
  <div class="flash-toast flash-success" id="flashToast">
    <i class="fas fa-check-circle"></i> {{ session('success') }}
  </div>
@endif
@if(session('error'))
  <div class="flash-toast flash-error" id="flashToast">
    <i class="fas fa-times-circle"></i> {{ session('error') }}
  </div>
@endif

@yield('content')

<footer class="footer">
  <div class="container footer-grid">
    <div class="footer-brand">
      <div class="nav-logo" style="margin-bottom:14px">
        <div class="logo-icon"><i class="fas fa-store"></i></div>
        Campus<span>Market</span>
      </div>
      <p>Platform marketplace O2O untuk ekosistem kampus yang lebih modern dan terpercaya.</p>
    </div>
    <div class="footer-links">
      <h4>Navigasi</h4>
      <ul>
        <li><a href="{{ route('home') }}">Beranda</a></li>
        <li><a href="{{ route('home') }}#katalog">Katalog</a></li>
        <li><a href="{{ route('home') }}#trending">Hot Trending</a></li>
      </ul>
    </div>
    <div class="footer-links">
      <h4>Akun</h4>
      <ul>
        <li><a href="{{ route('login') }}">Masuk</a></li>
        <li><a href="{{ route('register') }}">Daftar Pembeli</a></li>
        <li><a href="{{ route('register') }}?role=seller">Daftar Penjual</a></li>
      </ul>
    </div>
    <div class="footer-links">
      <h4>Kontak</h4>
      <ul>
        <li><i class="fas fa-envelope"></i> campusmarket@kampus.ac.id</li>
        <li><i class="fas fa-map-marker-alt"></i> Kampus Universitas</li>
      </ul>
    </div>
  </div>
  <div class="footer-bottom">
    <p>© {{ date('Y') }} CampusMarket — Dibuat dengan ❤️ oleh Tim Developer Kelompok</p>
  </div>
</footer>

<script src="{{ asset('js/app.js') }}"></script>
@stack('scripts')
</body>
</html>
