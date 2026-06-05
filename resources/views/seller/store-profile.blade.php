@extends('layouts.dashboard')
@section('title','Profil Toko')
@section('page-title','Profil & Branding Toko')
@section('page-subtitle','Atur tampilan toko kamu')

@section('sidebar-menu')
<div class="sidebar-section">Menu</div>
<a class="sidebar-link" href="{{ route('seller.dashboard') }}"><i class="fas fa-chart-bar"></i> Overview</a>
<a class="sidebar-link" href="{{ route('seller.products') }}"><i class="fas fa-box-open"></i> Produk Saya</a>
<a class="sidebar-link" href="{{ route('seller.orders') }}"><i class="fas fa-qrcode"></i> Pesanan Masuk</a>
<a class="sidebar-link active" href="{{ route('seller.store.profile') }}"><i class="fas fa-store-alt"></i> Profil Toko</a>
@endsection

@section('content')
<div style="display:grid;grid-template-columns:1fr 340px;gap:24px;align-items:start">
  <div class="form-card">
    <h3 style="margin-bottom:20px"><i class="fas fa-store-alt" style="color:var(--primary-light)"></i> Profil Toko</h3>
    <form method="POST" action="{{ route('seller.store.profile.update') }}" enctype="multipart/form-data">
      @csrf

      {{-- LOGO --}}
      <div style="display:flex;align-items:center;gap:16px;padding:16px;background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08);border-radius:14px;margin-bottom:20px">
        <div id="logoPreview" style="width:80px;height:80px;border-radius:16px;background:linear-gradient(135deg,#667eea,#764ba2);display:flex;align-items:center;justify-content:center;font-size:2.5rem;overflow:hidden;border:2px solid rgba(255,255,255,0.1);flex-shrink:0">
          @if($store?->logo_path)
            <img src="{{ $store->logoUrl() }}" style="width:100%;height:100%;object-fit:cover"/>
          @else
            {{ $store?->logo_emoji ?? '🏪' }}
          @endif
        </div>
        <div>
          <h4 style="color:white;font-weight:700;margin-bottom:4px">Logo Toko</h4>
          <p style="color:var(--text-muted);font-size:0.8rem;margin-bottom:10px">Upload foto logo atau gunakan emoji</p>
          <label class="btn btn-secondary btn-sm" style="cursor:pointer">
            <i class="fas fa-upload"></i> Upload Logo
            <input type="file" name="logo" accept="image/*" style="display:none" onchange="previewLogo(event)"/>
          </label>
        </div>
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
        <div class="form-group"><label><i class="fas fa-store"></i> Nama Toko *</label><input type="text" name="name" value="{{ old('name',$store?->name) }}" placeholder="Nama toko kamu" required/></div>
        <div class="form-group"><label><i class="fas fa-th-large"></i> Kategori *</label>
          <select name="category" required>
            @foreach(['Makanan & Minuman','Fashion','Jasa','Merchandise','Lainnya'] as $cat)
              <option value="{{ $cat }}" {{ old('category',$store?->category)===$cat?'selected':'' }}>{{ $cat }}</option>
            @endforeach
          </select>
        </div>
      </div>

      <div class="form-group"><label><i class="fas fa-align-left"></i> Deskripsi Toko</label><textarea name="description" placeholder="Ceritakan tentang toko kamu...">{{ old('description',$store?->description) }}</textarea></div>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
        <div class="form-group"><label><i class="fas fa-smile"></i> Emoji Logo</label><input type="text" name="logo_emoji" value="{{ old('logo_emoji',$store?->logo_emoji) }}" placeholder="🍱" maxlength="2"/></div>
        <div class="form-group"><label><i class="fas fa-map-marker-alt"></i> Lokasi</label><input type="text" name="location" value="{{ old('location',$store?->location) }}" placeholder="Kantin Gedung A, Lantai 1"/></div>
      </div>

      <div class="form-group"><label><i class="fas fa-clock"></i> Jam Operasional</label><input type="text" name="operating_hours" value="{{ old('operating_hours',$store?->operating_hours) }}" placeholder="Senin–Jumat, 08.00–17.00"/></div>

      <button type="submit" class="btn btn-primary" style="width:100%"><i class="fas fa-save"></i> Simpan Profil Toko</button>
    </form>
  </div>

  {{-- PREVIEW --}}
  @if($store)
  <div class="form-card">
    <h3 style="margin-bottom:16px"><i class="fas fa-eye" style="color:var(--primary-light)"></i> Preview Toko</h3>
    <div style="display:flex;align-items:flex-start;gap:14px;padding:16px;background:rgba(255,255,255,0.04);border-radius:12px;margin-bottom:14px">
      <div style="width:64px;height:64px;border-radius:14px;background:linear-gradient(135deg,#667eea,#764ba2);display:flex;align-items:center;justify-content:center;font-size:2rem;overflow:hidden;flex-shrink:0">
        @if($store->logo_path)<img src="{{ $store->logoUrl() }}" style="width:100%;height:100%;object-fit:cover"/>
        @else {{ $store->logo_emoji }} @endif
      </div>
      <div>
        <h3 style="color:white;font-size:1.1rem;font-weight:800">{{ $store->name }}</h3>
        <p style="color:var(--text-muted);font-size:0.8rem">{{ $store->category }}</p>
        @if($store->location)<p style="color:var(--text-muted);font-size:0.78rem;margin-top:2px"><i class="fas fa-map-marker-alt" style="color:var(--primary-light)"></i> {{ $store->location }}</p>@endif
        @if($store->operating_hours)<p style="color:var(--text-muted);font-size:0.78rem;margin-top:2px"><i class="fas fa-clock" style="color:var(--accent)"></i> {{ $store->operating_hours }}</p>@endif
      </div>
    </div>
    @if($store->description)<p style="font-size:0.84rem;color:var(--text-secondary);line-height:1.7">{{ $store->description }}</p>@endif
    <div style="margin-top:14px;padding:10px 14px;background:rgba(16,185,129,0.08);border:1px solid rgba(16,185,129,0.2);border-radius:8px;font-size:0.78rem;color:#34d399;font-weight:700">
      <i class="fas fa-check-circle"></i> Toko aktif & siap menerima pesanan
    </div>
  </div>
  @endif
</div>

@push('scripts')
<script>
function previewLogo(e) {
  const file = e.target.files[0];
  if (!file) return;
  const reader = new FileReader();
  reader.onload = ev => {
    document.getElementById('logoPreview').innerHTML = `<img src="${ev.target.result}" style="width:100%;height:100%;object-fit:cover"/>`;
  };
  reader.readAsDataURL(file);
}
</script>
@endpush
@endsection
