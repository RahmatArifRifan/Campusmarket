<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Daftar — CampusMarket</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800;900&family=Space+Grotesk:wght@600;700&display=swap" rel="stylesheet"/>
  <link rel="stylesheet" href="{{ asset('css/app.css') }}"/>
</head>
<body>
<div class="auth-page">
  <div class="auth-card">
    <div class="auth-logo">
      <a href="{{ route('home') }}" class="nav-logo" style="justify-content:center">
        <div class="logo-icon"><i class="fas fa-store"></i></div>
        Campus<span>Market</span>
      </a>
      <p>Platform Jual Beli Mahasiswa O2O</p>
    </div>

    <div class="auth-tabs">
      <a href="{{ route('login') }}" class="auth-tab"><i class="fas fa-sign-in-alt"></i> Masuk</a>
      <a href="{{ route('register') }}" class="auth-tab active"><i class="fas fa-user-plus"></i> Daftar</a>
    </div>

    @if($errors->any())
      <div class="auth-error" style="background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.2);color:#f87171;padding:12px 16px;border-radius:10px;margin-bottom:16px;font-size:0.86rem;font-weight:700">
        <i class="fas fa-exclamation-circle"></i> {{ $errors->first() }}
      </div>
    @endif

    <form method="POST" action="{{ route('register') }}">
      @csrf
      <div class="form-group">
        <label><i class="fas fa-user"></i> Nama Lengkap</label>
        <input type="text" name="name" value="{{ old('name') }}" placeholder="Nama lengkap kamu" required autofocus/>
      </div>
      <div class="form-group">
        <label><i class="fas fa-envelope"></i> Email</label>
        <input type="email" name="email" value="{{ old('email') }}" placeholder="email@kampus.ac.id" required/>
      </div>
      <div class="form-group">
        <label><i class="fas fa-lock"></i> Password</label>
        <input type="password" name="password" placeholder="Minimal 6 karakter" required/>
      </div>
      <div class="form-group">
        <label><i class="fas fa-lock"></i> Konfirmasi Password</label>
        <input type="password" name="password_confirmation" placeholder="Ulangi password" required/>
      </div>
      <div class="form-group">
        <label><i class="fas fa-users"></i> Daftar Sebagai</label>
        <div style="display:flex;gap:12px">
          <div class="role-option {{ old('role','buyer')==='buyer'?'selected':'' }}" id="roleBuyer"
            onclick="selectRole('buyer')" style="flex:1;padding:16px;border:1px solid rgba(255,255,255,0.1);border-radius:12px;text-align:center;cursor:pointer;transition:all 0.3s;background:rgba(255,255,255,0.04)">
            <i class="fas fa-shopping-bag" style="font-size:1.8rem;margin-bottom:8px;display:block;color:var(--primary-light)"></i>
            <span style="font-size:0.85rem;font-weight:700;color:white">Pembeli</span>
          </div>
          <div class="role-option {{ old('role')==='seller'?'selected':'' }}" id="roleSeller"
            onclick="selectRole('seller')" style="flex:1;padding:16px;border:1px solid rgba(255,255,255,0.1);border-radius:12px;text-align:center;cursor:pointer;transition:all 0.3s;background:rgba(255,255,255,0.04)">
            <i class="fas fa-store" style="font-size:1.8rem;margin-bottom:8px;display:block;color:var(--primary-light)"></i>
            <span style="font-size:0.85rem;font-weight:700;color:white">Pengusaha</span>
          </div>
        </div>
        <input type="hidden" name="role" id="roleInput" value="{{ old('role','buyer') }}"/>
      </div>
      <button type="submit" class="btn btn-primary" style="width:100%;padding:14px;font-size:1rem;margin-top:8px">
        <i class="fas fa-rocket"></i> Daftar Sekarang — Gratis!
      </button>
    </form>

    <div style="text-align:center;margin-top:20px;font-size:0.88rem;color:var(--text-muted)">
      Sudah punya akun? <a href="{{ route('login') }}" style="color:var(--primary-light);font-weight:700">Masuk di sini</a>
    </div>
  </div>
</div>

<script>
function selectRole(role) {
  document.getElementById('roleInput').value = role;
  document.getElementById('roleBuyer').style.borderColor = role==='buyer' ? 'var(--primary)' : 'rgba(255,255,255,0.1)';
  document.getElementById('roleBuyer').style.background = role==='buyer' ? 'rgba(99,102,241,0.12)' : 'rgba(255,255,255,0.04)';
  document.getElementById('roleSeller').style.borderColor = role==='seller' ? 'var(--primary)' : 'rgba(255,255,255,0.1)';
  document.getElementById('roleSeller').style.background = role==='seller' ? 'rgba(99,102,241,0.12)' : 'rgba(255,255,255,0.04)';
}
selectRole('{{ old('role','buyer') }}');
</script>
</body>
</html>
