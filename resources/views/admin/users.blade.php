@extends('layouts.dashboard')
@section('title','Manajemen User')
@section('page-title','Manajemen User')
@section('page-subtitle','Kelola semua pengguna')

@section('sidebar-menu')
<div class="sidebar-section">Admin Panel</div>
<a class="sidebar-link" href="{{ route('admin.dashboard') }}"><i class="fas fa-tachometer-alt"></i> Overview</a>
<a class="sidebar-link active" href="{{ route('admin.users') }}"><i class="fas fa-users"></i> Manajemen User</a>
<a class="sidebar-link" href="{{ route('admin.stores') }}"><i class="fas fa-store"></i> Manajemen Toko</a>
<a class="sidebar-link" href="{{ route('admin.transactions') }}"><i class="fas fa-exchange-alt"></i> Transaksi</a>
@endsection

@section('content')
<div class="table-card">
  <div class="table-header">
    <h3>Semua Pengguna ({{ $users->total() }})</h3>
    <form method="GET" action="{{ route('admin.users') }}" style="display:flex;gap:8px">
      <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama/email..." class="search-input" style="width:220px"/>
      <select name="role" style="padding:8px 12px;background:var(--glass);border:1px solid var(--glass-border);border-radius:8px;color:white;font-size:0.85rem" onchange="this.form.submit()">
        <option value="">Semua Role</option>
        <option value="admin" {{ request('role')==='admin'?'selected':'' }}>Admin</option>
        <option value="seller" {{ request('role')==='seller'?'selected':'' }}>Seller</option>
        <option value="buyer" {{ request('role')==='buyer'?'selected':'' }}>Buyer</option>
      </select>
      <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-search"></i></button>
    </form>
  </div>
  <table class="data-table">
    <thead><tr><th>Nama</th><th>Email</th><th>Role</th><th>Status</th><th>Daftar</th><th>Aksi</th></tr></thead>
    <tbody>
      @foreach($users as $u)
      <tr>
        <td style="color:white;font-weight:600">{{ $u->name }}</td>
        <td>{{ $u->email }}</td>
        <td><span class="badge {{ $u->role==='admin'?'badge-danger':($u->role==='seller'?'badge-primary':'badge-success') }}">{{ $u->role }}</span></td>
        <td><span class="badge {{ $u->is_banned?'badge-danger':'badge-success' }}">{{ $u->is_banned?'Banned':'Aktif' }}</span></td>
        <td style="color:var(--text-muted)">{{ $u->created_at->format('d/m/Y') }}</td>
        <td>
          @if($u->role !== 'admin')
            <form method="POST" action="{{ route('admin.users.toggle-ban',$u) }}">
              @csrf
              <button type="submit" class="btn {{ $u->is_banned?'btn-success':'btn-danger' }} btn-sm">
                <i class="fas fa-{{ $u->is_banned?'unlock':'ban' }}"></i>
                {{ $u->is_banned?'Aktifkan':'Ban' }}
              </button>
            </form>
          @else — @endif
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>
  <div style="margin-top:16px">{{ $users->links() }}</div>
</div>
@endsection
