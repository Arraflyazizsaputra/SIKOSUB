@extends('layouts.admin')

@section('content')
<style>
    :root {
        --primary: #4f46e5;
        --primary-hover: #4338ca;
        --secondary: #10b981;
        --danger: #ef4444;
        --bg-body: #f8fafc;
        --card-bg: #ffffff;
        --text-main: #0f172a;
        --text-muted: #64748b;
        --border-light: #e2e8f0;
    }

    .content-wrapper { padding: 40px; max-width: 1200px; margin: 0 auto; background: var(--bg-body); min-height: 100vh; font-family: 'Outfit', sans-serif; }
    
    .page-header { display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 30px; }
    .page-title { font-size: 32px; color: var(--text-main); font-weight: 900; margin-bottom: 8px; letter-spacing: -0.5px; }
    .page-subtitle { font-size: 14px; color: var(--text-muted); font-weight: 500; display: flex; align-items: center; gap: 8px; }
    .btn-top { background: white; border: 1px solid var(--border-light); padding: 10px 20px; border-radius: 10px; font-weight: 700; color: var(--text-main); cursor: pointer; display: flex; align-items: center; gap: 8px; transition: 0.3s; box-shadow: 0 2px 5px rgba(0,0,0,0.02); text-decoration: none; }
    .btn-top:hover { background: #f1f5f9; border-color: #cbd5e1; }

    .kost-summary-card { background: white; border: 1px solid var(--border-light); border-radius: 20px; padding: 20px; margin-bottom: 35px; display: flex; align-items: stretch; box-shadow: 0 10px 30px rgba(0,0,0,0.03); position: relative; overflow: hidden; }
    .kost-summary-card::before { content: ''; position: absolute; left: 0; top: 0; height: 100%; width: 5px; background: var(--primary); }
    .kost-img-wrapper { flex-shrink: 0; margin-right: 25px; display: flex; align-items: center; }
    .kost-img { width: 240px; height: 150px; border-radius: 12px; object-fit: cover; box-shadow: 0 4px 10px rgba(0,0,0,0.05); }
    .kost-details { flex-grow: 1; position: relative; display: flex; flex-direction: column; justify-content: center; padding-right: 20px; }
    
    .badge-container { position: absolute; top: -5px; right: 0; display: flex; }
    .badge-tipe { background: #fef08a; color: #854d0e; padding: 5px 15px; font-size: 11px; font-weight: 800; border-radius: 6px 0 0 6px; text-transform: uppercase; }
    .badge-rating { background: var(--danger); color: white; padding: 5px 12px; font-size: 11px; font-weight: 800; border-radius: 0 6px 6px 0; }
    
    .kost-title { font-size: 20px; font-weight: 900; color: var(--text-main); margin-bottom: 5px; text-transform: uppercase; }
    .kost-price { font-size: 22px; font-weight: 800; color: var(--primary); margin-bottom: 4px; }
    .discount-row { display: flex; align-items: center; gap: 10px; margin-bottom: 12px; }
    .txt-diskon { font-size: 11px; background: #fee2e2; color: var(--danger); padding: 2px 8px; border-radius: 4px; font-weight: 700; }
    .txt-coret { font-size: 11px; color: #9ca3af; text-decoration: line-through; font-weight: 600; }
    
    .kost-address { font-size: 13px; color: var(--text-muted); line-height: 1.5; }
    .kost-address strong { color: var(--text-main); font-weight: 700; display: flex; align-items: center; gap: 5px; margin-bottom: 4px; }
    
    .action-icons { position: absolute; bottom: 0; right: 0; display: flex; gap: 10px; }
    .icon-circle { width: 32px; height: 32px; background: #f1f5f9; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 14px; transition: 0.2s; text-decoration: none; cursor: pointer; }
    .icon-circle:hover { transform: scale(1.1); filter: brightness(0.9); }
    
    .admin-card { background: white; border-radius: 20px; padding: 30px; margin-bottom: 30px; box-shadow: 0 4px 20px rgba(0,0,0,0.02); border: 1px solid var(--border-light); }
    .card-header { font-size: 18px; font-weight: 800; color: var(--text-main); margin-bottom: 25px; padding-bottom: 15px; border-bottom: 2px solid #f1f5f9; display: flex; align-items: center; gap: 10px; }
    
    .form-grid { display: grid; gap: 20px; margin-bottom: 15px; }
    .grid-2 { grid-template-columns: repeat(2, 1fr); }
    .grid-3 { grid-template-columns: repeat(3, 1fr); }
    .grid-4 { grid-template-columns: repeat(4, 1fr); }
    
    .form-group label { display: block; font-size: 13px; font-weight: 700; color: var(--text-main); margin-bottom: 8px; }
    .form-control { width: 100%; padding: 14px 16px; background: #f8fafc; border: 1px solid var(--border-light); border-radius: 10px; font-size: 14px; color: var(--text-main); outline: none; transition: 0.3s; font-family: 'Outfit', sans-serif; }
    .form-control:focus { background: white; border-color: var(--primary); box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1); }
    
    .gallery-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-top: 15px; }
    .gallery-item { background: #f8fafc; height: 180px; border-radius: 16px; border: 2px dashed var(--border-light); position: relative; display: flex; flex-direction: column; align-items: center; justify-content: center; overflow: hidden; transition: 0.3s; }
    .gallery-item:hover { border-color: var(--primary); background: #eff6ff; }
    .gallery-preview { position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; z-index: 1; }
    .gallery-overlay { position: absolute; inset: 0; background: rgba(0,0,0,0.4); z-index: 2; opacity: 0; transition: 0.3s; display: flex; align-items: center; justify-content: center; }
    .gallery-item:hover .gallery-overlay { opacity: 1; }
    
    .btn-pill { background: white; color: var(--text-main); padding: 8px 16px; border-radius: 50px; font-size: 12px; font-weight: 700; cursor: pointer; border: none; display: flex; align-items: center; gap: 6px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); transition: 0.2s; }
    .btn-pill:hover { transform: scale(1.05); color: var(--primary); }
    
    .bottom-actions { display: flex; justify-content: flex-end; gap: 15px; margin-top: 40px; padding-top: 30px; border-top: 2px solid #f1f5f9; }
    .btn-cancel { background: white; color: var(--text-main); border: 1px solid var(--border-light); padding: 14px 35px; border-radius: 12px; font-weight: 700; font-size: 15px; cursor: pointer; text-decoration: none; transition: 0.3s; }
    .btn-cancel:hover { background: #f1f5f9; }
    .btn-save { background: var(--primary); color: white; border: none; padding: 14px 40px; border-radius: 12px; font-weight: 700; font-size: 15px; cursor: pointer; transition: 0.3s; box-shadow: 0 4px 15px rgba(79, 70, 229, 0.3); display: flex; align-items: center; gap: 8px; }
    .btn-save:hover { background: var(--primary-hover); transform: translateY(-2px); box-shadow: 0 6px 20px rgba(79, 70, 229, 0.4); }
</style>

<div class="content-wrapper">
    
    <div class="page-header">
        <div>
            <h1 class="page-title">Kelola Detail Kost</h1>
            <span class="page-subtitle"><i class="fa-solid fa-sliders"></i> Lengkapi Informasi Spesifik & Galeri Properti</span>
        </div>
        <a href="{{ route('kost.manage-index') }}" class="btn-top"><i class="fa-solid fa-list"></i> Kembali ke Daftar</a>
    </div>

    <div class="kost-summary-card">
        <div class="kost-img-wrapper">
            <img src="{{ asset('images/kost/'.$kost->gambar_utama) }}" class="kost-img" alt="Kost">
        </div>
        <div class="kost-details">
            <div class="badge-container">
                <span class="badge-tipe">{{ $kost->tipe_kost }}</span>
                <span class="badge-rating"><i class="fa-solid fa-star"></i> {{ number_format($kost->rating ?? 5.0, 1) }}</span>
            </div>
            <div class="kost-title">{{ $kost->nama_kost }}</div>
            <div class="kost-price">Rp {{ number_format($kost->harga_diskon > 0 ? $kost->harga_diskon : $kost->harga_per_bulan, 0, ',', '.') }} <span style="font-size: 13px; color: var(--text-muted);">/ bln</span></div>
            
            @if($kost->harga_diskon > 0)
            <div class="discount-row">
                <span class="txt-diskon">Hemat Rp {{ number_format($kost->harga_per_bulan - $kost->harga_diskon, 0, ',', '.') }}</span>
                <span class="txt-coret">Rp {{ number_format($kost->harga_per_bulan, 0, ',', '.') }}</span>
            </div>
            @else
            <div class="discount-row" style="visibility: hidden;"><span class="txt-diskon">Space</span></div>
            @endif
            
            <div class="kost-address">
                <strong><i class="fa-solid fa-map-pin" style="color: var(--danger);"></i> Lokasi Kost</strong>
                {{ $kost->alamat }}
            </div>
            
            <div class="action-icons">
                <a href="https://wa.me/{{ $kost->no_wa ?? '' }}" target="_blank" class="icon-circle" style="color:#16a34a; background: #dcfce7;" title="Hubungi Pemilik">
                    <i class="fa-brands fa-whatsapp"></i>
                </a>
                <a href="{{ !empty($kost->maps) ? $kost->maps : '#' }}" target="_blank" class="icon-circle" style="color:#2563eb; background: #dbeafe;" title="Lihat Lokasi di Maps">
                    <i class="fa-solid fa-location-dot"></i>
                </a>
            </div>
        </div>
    </div>

    <form action="{{ route('kost.manage-update', $kost->id) }}" method="POST" enctype="multipart/form-data">
        @csrf @method('PUT')

        <div class="admin-card">
            <div class="card-header"><i class="fa-solid fa-circle-info" style="color: var(--primary);"></i> Informasi Dasar & Harga</div>
            
            <div class="form-grid grid-3">
                <div class="form-group">
                    <label>Nama Kost</label>
                    <input type="text" name="nama_kost" class="form-control" value="{{ $kost->nama_kost }}" required>
                </div>
                <div class="form-group">
                    <label>Tipe Kost</label>
                    <select name="tipe_kost" class="form-control" required>
                        <option value="putra" {{ $kost->tipe_kost == 'putra' ? 'selected' : '' }}>Putra</option>
                        <option value="putri" {{ $kost->tipe_kost == 'putri' ? 'selected' : '' }}>Putri</option>
                        <option value="campur" {{ $kost->tipe_kost == 'campur' ? 'selected' : '' }}>Campur</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Status Kamar</label>
                    <select name="info_kamar" class="form-control">
                        <option value="Tersedia" {{ $kost->info_kamar == 'Tersedia' || $kost->info_kamar == 'Kosong' ? 'selected' : '' }}>Tersedia (Kosong)</option>
                        <option value="Penuh" {{ $kost->info_kamar == 'Penuh' ? 'selected' : '' }}>Penuh</option>
                    </select>
                </div>
            </div>

            <div class="form-grid grid-3">
                <div class="form-group">
                    <label>Harga Per Bulan (Rp)</label>
                    <input type="number" name="harga_per_bulan" class="form-control" value="{{ $kost->harga_per_bulan }}" required>
                </div>
                <div class="form-group">
                    <label>Harga Diskon (Rp)</label>
                    <input type="number" name="harga_diskon" class="form-control" value="{{ $kost->harga_diskon }}">
                </div>
                <div class="form-group">
                    <label>Disewakan Oleh</label>
                    <input type="text" name="disewakan_oleh" class="form-control" value="{{ $kost->disewakan_oleh }}">
                </div>
            </div>
        </div>

        <div class="admin-card">
            <div class="card-header"><i class="fa-solid fa-map" style="color: var(--secondary);"></i> Lokasi & Aksesibilitas</div>
            
            <div class="form-group">
                <label>Alamat Lengkap</label>
                <input type="text" name="alamat" class="form-control" value="{{ $kost->alamat }}" required>
            </div>

            <div class="form-grid grid-3">
                <div class="form-group">
                    <label>Jarak Dari Filter Wilayah</label>
                    <input type="number" step="0.01" name="jarak_km" value="{{ $kost->jarak_km }}"> <small>*Masukkan angka saja (contoh: 500)</small>
                </div>
                <div class="form-group">
                    <label>Tempat Terdekat</label>
                    <input type="text" name="tempat_terdekat" class="form-control" placeholder="Indomaret, Fotocopy..." value="{{ $kost->tempat_terdekat }}">
                </div>
                <div class="form-group">
                    <label>Link Google Maps</label>
                    <input type="text" name="maps" class="form-control" placeholder="https://maps.google.com/..." value="{{ $kost->maps }}">
                </div>
            </div>
        </div>

        <div class="admin-card">
            <div class="card-header"><i class="fa-solid fa-bed" style="color: #f59e0b;"></i> Spesifikasi & Fasilitas</div>
            
            <div class="form-grid grid-4">
                <div class="form-group">
                    <label>Fasilitas Kamar</label>
                    <input type="text" name="fasilitas_kamar" class="form-control" placeholder="Kasur, Lemari..." value="{{ $kost->fasilitas_kamar }}">
                </div>
                <div class="form-group">
                    <label>Fasilitas Kamar Mandi</label>
                    <input type="text" name="fasilitas_km" class="form-control" placeholder="KM Dalam, Kloset Duduk..." value="{{ $kost->fasilitas_km }}">
                </div>
                <div class="form-group">
                    <label>Fasilitas Umum</label>
                    <input type="text" name="fasilitas_umum" class="form-control" placeholder="Dapur, WiFi..." value="{{ $kost->fasilitas_umum }}">
                </div>
                <div class="form-group">
                    <label>Fasilitas Parkir</label>
                    <input type="text" name="fasilitas_parkir" class="form-control" placeholder="Motor/Mobil..." value="{{ $kost->fasilitas_parkir }}">
                </div>
            </div>
            
            <div class="form-grid grid-2">
                <div class="form-group">
                    <label>Spesifikasi Kamar</label>
                    <input type="text" name="spesifikasi_kamar" class="form-control" placeholder="Ukuran 3x3 Meter..." value="{{ $kost->spesifikasi_kamar }}">
                </div>
                <div class="form-group">
                    <label>Peraturan Kost</label>
                    <input type="text" name="peraturan" class="form-control" placeholder="Akses 24 Jam..." value="{{ $kost->peraturan }}">
                </div>
            </div>
        </div>

        <div class="admin-card">
            <div class="card-header"><i class="fa-solid fa-user-tie" style="color: #8b5cf6;"></i> Manajemen & Kontak</div>
            
            <div class="form-grid grid-4">
                <div class="form-group">
                    <label>Nama Pemilik Kost</label>
                    <input type="text" name="pemilik" class="form-control" value="{{ $kost->pemilik }}">
                </div>
                <div class="form-group">
                    <label>Kontak Pemilik (WA)</label>
                    <input type="text" name="kontak_pemilik" class="form-control" placeholder="628xxx" value="{{ $kost->kontak_pemilik }}">
                </div>
                <div class="form-group">
                    <label>Ketentuan Sewa</label>
                    <input type="text" name="ketentuan" class="form-control" placeholder="Minimal 3 Bulan" value="{{ $kost->ketentuan }}">
                </div>
                <div class="form-group">
                    <label>Review Admin</label>
                    <select name="review_admin" class="form-control">
                        <option value="">Pilih Status Rekomendasi</option>
                        <option value="Sangat Direkomendasikan" {{ $kost->review_admin == 'Sangat Direkomendasikan' ? 'selected' : '' }}>Sangat Direkomendasikan</option>
                        <option value="Bagus" {{ $kost->review_admin == 'Bagus' ? 'selected' : '' }}>Bagus</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="admin-card">
            <div class="card-header"><i class="fa-solid fa-images" style="color: #ec4899;"></i> Galeri Detail Kost (Opsional)</div>
            <p style="font-size: 13px; color: var(--text-muted); margin-bottom: 20px;">Unggah foto tambahan seperti foto dalam kamar, kamar mandi, atau lingkungan kost.</p>
            
            <div class="gallery-grid">
                @php
                    // Menerjemahkan JSON dari database agar tidak error
                    $galleriesAdmin = is_string($kost->gallery_images) ? json_decode($kost->gallery_images, true) : $kost->gallery_images;
                    if (!is_array($galleriesAdmin)) $galleriesAdmin = [];
                @endphp

                @for($i=1; $i<=4; $i++)
                <div class="gallery-item">
                    @php 
                        $imgName = $galleriesAdmin[$i-1] ?? null;
                        $imgSrc = $imgName ? asset('images/kost/'.$imgName) : '';
                    @endphp

                    <i class="fa-solid fa-image" style="font-size: 30px; color: #cbd5e1; margin-bottom: 10px; {{ $imgSrc ? 'display:none;' : '' }}" id="icon-gal-{{$i}}"></i>
                    <span style="font-size: 12px; font-weight: 700; color: var(--text-muted); {{ $imgSrc ? 'display:none;' : '' }}" id="text-gal-{{$i}}">Slot Galeri {{ $i }}</span>
                    
                    <img id="prev-gal-{{$i}}" class="gallery-preview" src="{{ $imgSrc }}" style="{{ $imgSrc ? 'display:block;' : 'display:none;' }}">
                    
                    <div class="gallery-overlay">
                        <label class="btn-pill">
                            <i class="fa-solid fa-camera"></i> Pilih Foto
                            <input type="file" name="gallery_{{$i}}" style="display: none;" accept="image/*" onchange="previewGallery(this, 'prev-gal-{{$i}}', 'icon-gal-{{$i}}', 'text-gal-{{$i}}')">
                        </label>
                    </div>
                </div>
                @endfor
            </div>

            <div style="margin-top: 25px; padding: 25px; background: #f8fafc; border-radius: 12px; border: 1px dashed var(--border-light);">
                <label style="font-weight: 800; display: block; margin-bottom: 10px; color: var(--text-main);"><i class="fa-solid fa-folder-plus"></i> Tambah Foto Lainnya (Lebih dari 4)</label>
                <p style="font-size: 13px; color: var(--text-muted); margin-bottom: 15px;">Anda bisa memilih banyak foto sekaligus dari perangkat Anda. Foto-foto ini akan ditambahkan secara otomatis ke halaman Galeri Khusus.</p>
                <input type="file" name="gallery_new[]" multiple accept="image/*" class="form-control" style="background: white;">
            </div>

        </div>

        <div class="bottom-actions">
            <a href="{{ route('kost.manage-index') }}" class="btn-cancel">Batal</a>
            <button type="submit" class="btn-save"><i class="fa-solid fa-floppy-disk"></i> Simpan Detail Kost</button>
        </div>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@if(session('success'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '{!! session('success') !!}',
            confirmButtonColor: '#4f46e5',
            timer: 3000
        });
    });
</script>
@endif

<script>
    // Fungsi Live Preview Gambar Galeri
    function previewGallery(input, imgId, iconId, textId) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                var img = document.getElementById(imgId);
                img.src = e.target.result;
                img.style.display = 'block';
                
                var icon = document.getElementById(iconId);
                if(icon) icon.style.display = 'none';
                
                var text = document.getElementById(textId);
                if(text) text.style.display = 'none';
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection