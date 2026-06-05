<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <meta name="csrf-token" content="{{ csrf_token() }}"/>
  <title>@yield('title', 'Dashboard') — CampusMarket</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet"/>
  <link rel="stylesheet" href="{{ asset('css/app.css') }}"/>
  @stack('styles')
</head>
<body>
<div class="dashboard-layout">

  {{-- SIDEBAR --}}
  <aside class="sidebar" id="sidebar">
    <div class="sidebar-logo">
      <div class="logo-icon"><i class="fas fa-store"></i></div>
      Campus<span>Market</span>
    </div>
    <nav class="sidebar-nav">
      @yield('sidebar-menu')
    </nav>
    <div class="sidebar-footer">
      <div class="sidebar-user">
        <div class="sidebar-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 2)) }}</div>
        <div class="sidebar-user-info">
          <p>{{ auth()->user()->name }}</p>
          <small>{{ ucfirst(auth()->user()->role) }}</small>
        </div>
        <form method="POST" action="{{ route('logout') }}">
          @csrf
          <button type="submit" class="sidebar-logout" title="Keluar">
            <i class="fas fa-sign-out-alt"></i>
          </button>
        </form>
      </div>
    </div>
  </aside>

  {{-- MAIN CONTENT --}}
  <div class="main-content">
    <div class="topbar">
      <div class="topbar-title">
        <h2>@yield('page-title', 'Dashboard')</h2>
        <p>@yield('page-subtitle', '')</p>
      </div>
      <div class="topbar-actions">
        <div class="topbar-notif"><i class="fas fa-bell"></i></div>
        <a href="{{ route('home') }}" class="btn btn-secondary btn-sm">
          <i class="fas fa-home"></i> Beranda
        </a>
      </div>
    </div>

    {{-- FLASH MESSAGES --}}
    @if(session('success'))
      <div class="dash-alert dash-alert-success">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
      </div>
    @endif
    @if(session('error'))
      <div class="dash-alert dash-alert-error">
        <i class="fas fa-times-circle"></i> {{ session('error') }}
      </div>
    @endif
    @if($errors->any())
      <div class="dash-alert dash-alert-error">
        <i class="fas fa-exclamation-circle"></i>
        <ul style="margin:0;padding-left:16px">
          @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <div class="page-content">
      @yield('content')
    </div>
  </div>

</div>
<script src="{{ asset('js/app.js') }}"></script>
@stack('scripts')
</body>
</html>
