@extends('layouts.dashboard')
@section('title','Produk Saya')
@section('page-title','Produk Saya')
@section('page-subtitle','Kelola produk toko kamu')

@section('sidebar-menu')
<div class="sidebar-section">Menu</div>
<a class="sidebar-link" href="{{ route('seller.dashboard') }}"><i class="fas fa-chart-bar"></i> Overview</a>
<a class="sidebar-link active" href="{{ route('seller.products') }}"><i class="fas fa-box-open"></i> Produk Saya</a>
<a class="sidebar-link" href="{{ route('seller.orders') }}"><i class="fas fa-qrcode"></i> Pesanan Masuk</a>
<a class="sidebar-link" href="{{ route('seller.store.profile') }}"><i class="fas fa-store-alt"></i> Profil Toko</a>
@endsection

@section('content')
{{-- FORM TAMBAH/EDIT PRODUK --}}
<div class="form-card" id="productForm">
  <h3 style="margin-bottom:20px"><i class="fas fa-plus-circle" style="color:var(--primary-light)"></i> Tambah Produk Baru</h3>
  <form method="POST" action="{{ route('seller.products.store') }}" enctype="multipart/form-data">
    @csrf
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:24px;align-items:start">
      {{-- UPLOAD FOTO --}}
      <div>
        <label style="display:block;font-weight:700;font-size:0.85rem;color:var(--text-secondary);margin-bottom:10px"><i class="fas fa-camera"></i> Foto Produk</label>
        <div id="uploadZone" style="border:2px dashed rgba(99,102,241,0.4);border-radius:16px;padding:32px 20px;text-align:center;cursor:pointer;background:rgba(99,102,241,0.04);transition:all 0.3s;position:relative" onclick="document.getElementById('photoInput').click()" ondragover="event.preventDefault();this.style.borderColor='var(--primary)'" ondrop="handleDrop(event)">
          <input type="file" id="photoInput" name="image" accept="image/jpeg,image/png,image/webp" style="display:none" onchange="previewPhoto(event)"/>
          <div id="uploadPlaceholder">
            <div style="width:60px;height:60px;border-radius:16px;background:var(--grad-main);display:flex;align-items:center;justify-content:center;margin:0 auto 14px;font-size:1.6rem">📷</div>
            <h4 style="color:white;margin-bottom:6px">Upload Foto Produk</h4>
            <p style="color:var(--text-muted);font-size:0.82rem">Drag & drop atau klik untuk pilih</p>
            <span style="display:inline-flex;align-items:center;gap:6px;background:rgba(99,102,241,0.15);border:1px solid rgba(99,102,241,0.3);color:var(--primary-light);padding:5px 12px;border-radius:50px;font-size:0.75rem;font-weight:700;margin-top:10px">JPG, PNG, WEBP — Maks. 5MB</span>
          </div>
          <div id="previewWrap" style="display:none">
            <img id="photoPreview" style="max-height:160px;border-radius:10px;object-fit:cover"/>
            <div style="margin-top:10px;display:flex;gap:8px;justify-content:center">
              <button type="button" class="btn btn-primary btn-sm" onclick="event.stopPropagation();document.getElementById('photoInput').click()"><i class="fas fa-sync"></i> Ganti</button>
              <button type="button" class="btn btn-danger btn-sm" onclick="event.stopPropagation();removePhoto()"><i class="fas fa-trash"></i></button>
            </div>
          </div>
        </div>
        <div style="margin-top:12px;padding:12px;background:rgba(245,158,11,0.08);border:1px solid rgba(245,158,11,0.2);border-radius:10px">
          <p style="font-size:0.78rem;color:#fbbf24;font-weight:600;margin-bottom:6px">💡 Tips Foto Produk</p>
          <ul style="font-size:0.75rem;color:var(--text-muted);padding-left:14px;line-height:1.8">
            <li>Gunakan pencahayaan yang terang</li>
            <li>Background polos lebih menarik</li>
            <li>Resolusi minimal 400×400 px</li>
          </ul>
        </div>
      </div>

      {{-- DETAIL PRODUK --}}
      <div>
        <div class="form-group"><label><i class="fas fa-tag"></i> Nama Produk *</label><input type="text" name="name" value="{{ old('name') }}" placeholder="Contoh: Nasi Ayam Geprek Spesial" required/></div>
        <div class="form-group"><label><i class="fas fa-align-left"></i> Deskripsi</label><textarea name="description" placeholder="Ceritakan produkmu...">{{ old('description') }}</textarea></div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
          <div class="form-group"><label><i class="fas fa-th-large"></i> Kategori *</label>
            <select name="category" required>
              <option value="makanan" {{ old('category')==='makanan'?'selected':'' }}>🍱 Makanan</option>
              <option value="minuman" {{ old('category')==='minuman'?'selected':'' }}>🥤 Minuman</option>
              <option value="fashion" {{ old('category')==='fashion'?'selected':'' }}>👕 Fashion</option>
              <option value="jasa"    {{ old('category')==='jasa'?'selected':'' }}>🛠️ Jasa</option>
              <option value="lainnya" {{ old('category')==='lainnya'?'selected':'' }}>📦 Lainnya</option>
            </select>
          </div>
          <div class="form-group"><label><i class="fas fa-smile"></i> Emoji</label><input type="text" name="emoji" value="{{ old('emoji') }}" placeholder="🍱" maxlength="2"/></div>
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
          <div class="form-group"><label><i class="fas fa-tag"></i> Harga (Rp) *</label><input type="number" name="price" value="{{ old('price') }}" placeholder="15000" min="0" required/></div>
          <div class="form-group"><label><i class="fas fa-boxes"></i> Stok *</label><input type="number" name="stock" value="{{ old('stock') }}" placeholder="50" min="0" required/></div>
        </div>
        <button type="submit" class="btn btn-primary" style="width:100%"><i class="fas fa-save"></i> Simpan Produk</button>
      </div>
    </div>
  </form>
</div>

{{-- DAFTAR PRODUK --}}
<div class="table-card">
  <div class="table-header">
    <h3><i class="fas fa-box-open" style="color:var(--primary-light);margin-right:8px"></i>Daftar Produk ({{ $products->count() }})</h3>
  </div>
  @if($products->isEmpty())
    <div style="text-align:center;padding:50px;color:var(--text-muted)">
      <i class="fas fa-box-open" style="font-size:3rem;display:block;margin-bottom:16px;opacity:0.2"></i>
      <p>Belum ada produk. Tambahkan produk pertamamu di atas!</p>
    </div>
  @else
    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:16px">
      @foreach($products as $product)
        <div style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08);border-radius:14px;overflow:hidden">
          <div style="height:140px;background:{{ $product->categoryGradient() }};display:flex;align-items:center;justify-content:center;position:relative;overflow:hidden">
            @if($product->image_path)
              <img src="{{ $product->imageUrl() }}" style="width:100%;height:100%;object-fit:cover"/>
            @else
              <span style="font-size:3rem">{{ $product->displayEmoji() }}</span>
            @endif
          </div>
          <div style="padding:12px">
            <div style="font-weight:700;color:white;font-size:0.88rem;margin-bottom:4px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">{{ $product->name }}</div>
            <div style="font-weight:800;background:linear-gradient(135deg,#4facfe,#00f2fe);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;margin-bottom:6px">{{ $product->formattedPrice() }}</div>
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:10px">
              <span style="font-size:0.72rem;color:var(--text-muted)">Stok: {{ $product->stock }}</span>
              <span class="badge badge-info" style="font-size:0.65rem">{{ $product->category }}</span>
            </div>
            <div style="display:flex;gap:6px">
              <button class="btn btn-warning btn-sm" style="flex:1" onclick="editProduct({{ $product->id }},'{{ $product->name }}','{{ $product->description }}','{{ $product->category }}','{{ $product->emoji }}',{{ $product->price }},{{ $product->stock }})"><i class="fas fa-edit"></i></button>
              <form method="POST" action="{{ route('seller.products.destroy',$product) }}" onsubmit="return confirm('Hapus produk ini?')">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
              </form>
            </div>
          </div>
        </div>
      @endforeach
    </div>
  @endif
</div>

{{-- EDIT MODAL --}}
<div class="modal-overlay" id="editModal">
  <div class="modal-box">
    <button class="modal-close" onclick="document.getElementById('editModal').classList.remove('active')"><i class="fas fa-times"></i></button>
    <h3 style="color:white;margin-bottom:20px"><i class="fas fa-edit" style="color:var(--primary-light)"></i> Edit Produk</h3>
    <form id="editForm" method="POST" enctype="multipart/form-data">
      @csrf @method('PUT')
      <div class="form-group"><label>Nama Produk</label><input type="text" name="name" id="editName" required/></div>
      <div class="form-group"><label>Deskripsi</label><textarea name="description" id="editDesc"></textarea></div>
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
        <div class="form-group"><label>Kategori</label><select name="category" id="editCategory"><option value="makanan">Makanan</option><option value="minuman">Minuman</option><option value="fashion">Fashion</option><option value="jasa">Jasa</option><option value="lainnya">Lainnya</option></select></div>
        <div class="form-group"><label>Emoji</label><input type="text" name="emoji" id="editEmoji" maxlength="2"/></div>
      </div>
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
        <div class="form-group"><label>Harga (Rp)</label><input type="number" name="price" id="editPrice" required/></div>
        <div class="form-group"><label>Stok</label><input type="number" name="stock" id="editStock" required/></div>
      </div>
      <div class="form-group"><label>Ganti Foto (opsional)</label><input type="file" name="image" accept="image/jpeg,image/png,image/webp"/></div>
      <button type="submit" class="btn btn-primary" style="width:100%"><i class="fas fa-save"></i> Simpan Perubahan</button>
    </form>
  </div>
</div>

@push('scripts')
<script>
function previewPhoto(e) {
  const file = e.target.files[0];
  if (!file) return;
  const reader = new FileReader();
  reader.onload = ev => {
    document.getElementById('photoPreview').src = ev.target.result;
    document.getElementById('uploadPlaceholder').style.display = 'none';
    document.getElementById('previewWrap').style.display = 'block';
  };
  reader.readAsDataURL(file);
}
function removePhoto() {
  document.getElementById('photoInput').value = '';
  document.getElementById('previewWrap').style.display = 'none';
  document.getElementById('uploadPlaceholder').style.display = 'block';
}
function handleDrop(e) {
  e.preventDefault();
  const file = e.dataTransfer.files[0];
  if (file && file.type.startsWith('image/')) {
    const dt = new DataTransfer();
    dt.items.add(file);
    document.getElementById('photoInput').files = dt.files;
    previewPhoto({target:{files:[file]}});
  }
}
function editProduct(id,name,desc,cat,emoji,price,stock) {
  document.getElementById('editForm').action = `/seller/products/${id}`;
  document.getElementById('editName').value = name;
  document.getElementById('editDesc').value = desc;
  document.getElementById('editCategory').value = cat;
  document.getElementById('editEmoji').value = emoji;
  document.getElementById('editPrice').value = price;
  document.getElementById('editStock').value = stock;
  document.getElementById('editModal').classList.add('active');
}
</script>
@endpush
@endsection
