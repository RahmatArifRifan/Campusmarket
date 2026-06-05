<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Masuk — CampusMarket</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800;900&family=Space+Grotesk:wght@600;700&display=swap" rel="stylesheet"/>
  <link rel="stylesheet" href="{{ asset('css/app.css') }}"/>
</head>
<body>
<canvas id="particles" style="position:fixed;inset:0;z-index:0;pointer-events:none;opacity:0.4"></canvas>

<div class="auth-page" style="position:relative;z-index:1">
  <div class="auth-card">
    <div class="auth-logo">
      <a href="{{ route('home') }}" class="nav-logo" style="justify-content:center">
        <div class="logo-icon"><i class="fas fa-store"></i></div>
        Campus<span>Market</span>
      </a>
      <p>Platform Jual Beli Mahasiswa O2O</p>
    </div>

    <div class="auth-tabs">
      <a href="{{ route('login') }}" class="auth-tab active">
        <i class="fas fa-sign-in-alt"></i> Masuk
      </a>
      <a href="{{ route('register') }}" class="auth-tab">
        <i class="fas fa-user-plus"></i> Daftar
      </a>
    </div>

    @if($errors->any())
      <div class="auth-error">
        <i class="fas fa-exclamation-circle"></i>
        {{ $errors->first() }}
      </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
      @csrf
      <div class="form-group">
        <label><i class="fas fa-envelope"></i> Email</label>
        <input type="email" name="email" value="{{ old('email') }}"
               placeholder="email@kampus.ac.id" required autofocus/>
      </div>
      <div class="form-group">
        <label><i class="fas fa-lock"></i> Password</label>
        <input type="password" name="password" placeholder="Masukkan password" required/>
      </div>
      <div style="display:flex;align-items:center;gap:8px;margin-bottom:16px">
        <input type="checkbox" name="remember" id="remember" style="width:auto"/>
        <label for="remember" style="font-size:0.85rem;color:var(--text-muted);margin:0">Ingat saya</label>
      </div>
      <button type="submit" class="btn btn-primary auth-submit btn-lg">
        <i class="fas fa-sign-in-alt"></i> Masuk ke CampusMarket
      </button>
    </form>

    <div class="auth-footer">
      Belum punya akun? <a href="{{ route('register') }}">Daftar sekarang</a>
    </div>

  </div>
</div>

<script src="{{ asset('js/app.js') }}"></script>
<script>initParticles();</script>
</body>
</html>
