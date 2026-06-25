@extends('layouts.admin')

@section('content')
<style>
    /* =========================================
       MODERN & SCALABLE DESIGN SYSTEM
       ========================================= */
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

    .content-wrapper { padding: 2rem; max-width: 1200px; margin: 0 auto; background: var(--bg-body); min-height: 100vh; font-family: 'Outfit', sans-serif; box-sizing: border-box; }
    
    .page-header { margin-bottom: 2rem; }
    .page-title { font-size: clamp(1.5rem, 2.5vw, 2rem); color: var(--text-main); font-weight: 900; margin-bottom: 0.5rem; letter-spacing: -0.5px; }
    .page-subtitle { font-size: clamp(0.8rem, 1vw, 0.9rem); color: var(--text-muted); font-weight: 500; display: flex; align-items: center; gap: 8px; }
    
    /* Card Container */
    .admin-card { background: var(--card-bg); border-radius: 1.25rem; padding: clamp(1.5rem, 3vw, 2rem); margin-bottom: 2rem; box-shadow: 0 10px 40px rgba(0,0,0,0.03); border: 1px solid rgba(255,255,255,0.8); position: relative; overflow: hidden; }
    .admin-card::before { content: ''; position: absolute; top: 0; left: 0; width: 100%; height: 4px; background: linear-gradient(90deg, var(--primary), var(--secondary)); opacity: 0; transition: 0.4s; }
    .admin-card:hover::before { opacity: 1; }
    
    .card-title { font-size: clamp(1.1rem, 2vw, 1.25rem); font-weight: 800; color: var(--text-main); margin-bottom: 1.5rem; display: flex; align-items: center; gap: 12px; }
    .card-title i { background: #eff6ff; padding: 10px; border-radius: 10px; color: var(--primary); }
    
    /* Banner Grid */
    .banner-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 1.5rem; }
    .banner-card { 
        min-height: 220px; border-radius: 1rem; position: relative; background-size: cover; background-position: center; 
        border: 2px dashed var(--border-light); background-color: #f8fafc; transition: 0.3s; 
        display: flex; flex-direction: column; align-items: center; justify-content: center; overflow: hidden; 
    }
    .banner-card.has-image { border: none; box-shadow: 0 8px 20px rgba(0,0,0,0.08); }
    .banner-card::after { content: ''; position: absolute; inset: 0; background: linear-gradient(to top, rgba(0,0,0,0.8) 0%, transparent 100%); opacity: 0; transition: 0.3s; pointer-events: none; }
    .banner-card.has-image:hover::after { opacity: 1; }
    
    .banner-text { font-size: 0.9rem; font-weight: 800; color: var(--text-muted); z-index: 2; transition: 0.3s; }
    .has-image .banner-text { color: white; position: absolute; top: 1rem; left: 1rem; background: rgba(0,0,0,0.4); backdrop-filter: blur(4px); padding: 6px 12px; border-radius: 8px; font-size: 0.8rem; }
    
    .card-actions { 
        position: absolute; inset: 0; display: flex; align-items: center; justify-content: center; gap: 10px; z-index: 10; 
        opacity: 0; transform: scale(0.9); transition: all 0.3s ease; pointer-events: none; 
    }
    .banner-card:hover .card-actions { opacity: 1; transform: scale(1); pointer-events: auto; }
    
    /* Buttons */
    .btn-primary { background: var(--primary); color: white; box-shadow: 0 4px 15px rgba(79, 70, 229, 0.3); padding: 0.75rem 1.5rem; border-radius: 0.6rem; font-weight: 700; font-size: 0.9rem; cursor: pointer; border: none; transition: 0.3s; display: inline-flex; align-items: center; gap: 8px; position: relative; z-index: 10; }
    .btn-primary:hover { background: var(--primary-hover); transform: translateY(-2px); box-shadow: 0 6px 20px rgba(79, 70, 229, 0.4); }
    .btn-pill { padding: 0.5rem 1rem; border-radius: 50px; font-size: 0.8rem; font-weight: 700; cursor: pointer !important; border: none; display: flex; align-items: center; gap: 6px; user-select: none; position: relative; z-index: 10; }
    .btn-pill * { pointer-events: none; }
    
    /* Filter Item Layout */
    .filter-item { background: #f8fafc; border-radius: 1rem; padding: clamp(1rem, 2vw, 1.5rem); margin-bottom: 1.5rem; border: 1px solid var(--border-light); transition: 0.3s; }
    .filter-item:focus-within { border-color: var(--primary); background: white; box-shadow: 0 10px 30px rgba(79, 70, 229, 0.05); }
    
    .header-action-group { display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 10px; margin-bottom: 1rem; }
    .group-title { font-size: clamp(0.9rem, 1.5vw, 1rem); font-weight: 800; color: var(--text-main); display: flex; align-items: center; gap: 10px; margin: 0; }
    
    .action-buttons { display: flex; flex-wrap: wrap; gap: 8px; }
    .btn-action-small { background: white; border: 1px solid var(--border-light); color: var(--text-muted); padding: 0.5rem 0.8rem; border-radius: 0.5rem; font-size: 0.8rem; font-weight: 700; cursor: pointer !important; transition: 0.2s; display: flex; align-items: center; gap: 6px; user-select: none; position: relative; z-index: 10;}
    .btn-action-small * { pointer-events: none; } 
    .btn-action-small.edit:hover { border-color: var(--primary); color: var(--primary); background: #eff6ff; }
    .btn-action-small.delete:hover { border-color: var(--danger); color: var(--danger); background: #fef2f2; }

    /* Modern Photo Management CSS */
    .filter-photo-manager {
        display: flex;
        flex-direction: column;
        gap: 12px;
        background: #ffffff;
        border: 1px solid var(--border-light);
        border-radius: 0.75rem;
        padding: 1rem;
        margin-bottom: 1rem;
    }
    .photo-grid-preview {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        align-items: center;
    }
    .photo-thumb-wrapper {
        position: relative;
        width: 60px;
        height: 60px;
        border-radius: 8px;
        border: 1px solid var(--border-light);
        overflow: hidden;
    }
    .photo-thumb-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .photo-thumb-wrapper .btn-delete-photo {
        position: absolute;
        top: 2px;
        right: 2px;
        background: rgba(239, 68, 68, 0.85);
        color: white;
        border: none;
        border-radius: 50%;
        width: 18px;
        height: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.65rem;
        cursor: pointer;
        z-index: 12;
        transition: 0.2s;
    }
    .photo-thumb-wrapper .btn-delete-photo:hover {
        background: rgb(239, 68, 68);
        transform: scale(1.1);
    }
    .photo-thumb-wrapper.new-preview {
        border: 2px dashed var(--secondary);
    }

    /* Input & Textarea */
    .input-group-grid {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }
    .input-text-wrapper {
        display: flex;
        border-radius: 0.75rem;
        overflow: hidden;
        border: 1px solid var(--border-light);
        background: white;
    }
    .input-text { flex: 1; border: none; padding: 1rem; font-size: 0.95rem; outline: none; color: var(--text-main); font-weight: 500; }
    
    .form-group { margin-bottom: 1.5rem; }
    .form-control { width: 100%; padding: 1.25rem; background: #f8fafc; border: 1px solid var(--border-light); border-radius: 1rem; font-size: 0.95rem; outline: none; transition: 0.3s; resize: vertical; color: var(--text-main); font-family: 'Outfit', sans-serif; line-height: 1.6; box-sizing: border-box; }
    .form-control:focus { background: white; border-color: var(--primary); box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1); }

    .grid-2 { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.5rem; }
    .alert-success { background: #ecfdf5; border-left: 5px solid var(--secondary); color: #065f46; padding: 1.25rem; border-radius: 0.75rem; margin-bottom: 2rem; font-weight: 700; display: flex; align-items: center; gap: 12px; box-shadow: 0 4px 15px rgba(16, 185, 129, 0.1); }
</style>

{{-- Setup Data --}}
@php 
    $defaultTentang = 'Kota Subang terus mengalami perkembangan yang pesat, baik sebagai pusat pendidikan...';
    $defaultVisi = 'Menjadi platform direktori terpusat yang memfasilitasi pencarian dan promosi hunian sementara...';
    $defaultMisi = "1. Menyediakan fitur pencarian kos...\n\n2. Menyediakan platform digital...\n\n3. Memfasilitasi proses penyewaan...";
@endphp

<div class="content-wrapper">
    @if(session('success'))
        <div class="alert-success">
            <i class="fa-solid fa-circle-check" style="font-size: 1.25rem;"></i> {{ session('success') }}
        </div>
    @endif

    <div class="page-header">
        <h1 class="page-title">Kelola Landing Page</h1>
        <span class="page-subtitle"><i class="fa-solid fa-palette"></i> Desain & Informasi Halaman Utama SIKOSUB</span>
    </div>

    {{-- CARD 1: BANNER PROMOSI --}}
    <div class="admin-card">
        <h2 class="card-title"><i class="fa-solid fa-images"></i> Gambar Banner Promosi</h2>
        <div class="banner-grid">
            @php $banners = json_decode($landing->banner_image ?? '[]', true); @endphp
            @for($i=1; $i<=4; $i++)
                @php $currentBanner = $banners[$i-1] ?? null; @endphp
                
                <div class="banner-card {{ $currentBanner ? 'has-image' : '' }}" style="{{ $currentBanner ? 'background-image: url('.asset('images/banners/'.$currentBanner).');' : '' }}">
                    @if(!$currentBanner)
                        <i class="fa-solid fa-cloud-arrow-up" style="font-size: 2rem; color: #cbd5e1; margin-bottom: 0.5rem;"></i>
                    @endif
                    <div class="banner-text">Slot Banner {{ $i }}</div>
                    
                    <div class="card-actions">
                        @if($currentBanner)
                        <form action="{{ route('landing.banner.delete', $i-1) }}" method="POST" style="margin: 0;">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-pill" style="background: #ef4444; color: white;" onclick="return confirm('Hapus banner ini dari halaman depan?')"><i class="fa-solid fa-trash"></i> Hapus</button>
                        </form>
                        @endif
                        
                        <form action="{{ route('landing.banner.upload', $i-1) }}" method="POST" enctype="multipart/form-data" style="margin: 0;">
                            @csrf
                            <label class="btn-pill" style="background: white; color: #111;">
                                <i class="fa-solid fa-pen"></i> {{ $currentBanner ? 'Ganti Foto' : 'Pilih Foto' }}
                                <input type="file" name="banner_file" onchange="this.form.submit()" style="display: none;" accept="image/*" required>
                            </label>
                        </form>
                    </div>
                </div>
            @endfor
        </div>
    </div>

    {{-- CARD 2: FILTER WILAYAH KOST --}}
    <div class="admin-card">
        <h2 class="card-title"><i class="fa-solid fa-map-location-dot"></i> Filter Wilayah Kost & Pengelolaan Foto</h2>

        {{-- 1. DEKAT PENDIDIKAN --}}
        <div class="filter-item" id="box-pendidikan">
            <div class="header-action-group">
                <h3 class="group-title"><i class="fa-solid fa-graduation-cap" style="color: #3b82f6;"></i> Dekat Pendidikan</h3>
                <div class="action-buttons">
                    <button type="button" class="btn-action-small edit" onclick="fokusInput('input-pendidikan')"><i class="fa-solid fa-pen"></i> Tambah Instansi</button>
                    <!-- Tombol Reset Teks -->
                    <button type="button" class="btn-action-small delete" onclick="hapusFilter('input-pendidikan', 'reset-pendidikan')"><i class="fa-solid fa-eraser"></i> Bersihkan Semua Teks</button>
                    
                    <form action="{{ route('landing.filter.clear_all', 'pendidikan') }}" method="POST" style="margin:0;">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn-action-small delete" onclick="return confirm('Yakin hapus SEMUA foto di kategori ini?')">
                            <i class="fa-solid fa-trash-can"></i> Hapus Semua Foto
                        </button>
                    </form>
                </div>
            </div>

            <div class="filter-photo-manager">
                <div style="font-size: 0.85rem; font-weight: 700; color: var(--text-main);">Daftar Foto Saat Ini (Klik [x] untuk Hapus Satuan):</div>
                <div class="photo-grid-preview" id="container-prev-pendidikan">
                    @if(!empty($landing->image_pendidikan))
                        @foreach(explode(',', $landing->image_pendidikan) as $index => $img)
                            @if(!empty(trim($img)))
                            <div class="photo-thumb-wrapper">
                                <img src="{{ asset('images/filters/'.trim($img)) }}">
                                <form action="{{ route('landing.filter.delete_photo', ['category' => 'pendidikan', 'photo_index' => $index]) }}" method="POST" style="margin:0;">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-delete-photo" onclick="return confirm('Hapus foto ini?')">×</button>
                                </form>
                            </div>
                            @endif
                        @endforeach
                    @else
                        <span style="font-size:0.8rem; color: var(--text-muted); font-style:italic;">Belum ada foto.</span>
                    @endif
                </div>
            </div>

            <form action="{{ route('landing.filter.update', 'pendidikan') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="reset_text" id="reset-pendidikan" value="false">
                
                <div class="input-group-grid">
                    <!-- TAMPILAN LABEL DAFTAR NAMA SAAT INI -->
                    <div style="background: #f8fafc; border: 1px dashed var(--border-light); padding: 12px; border-radius: 0.5rem; margin-bottom: 5px;">
                        <div style="font-size: 0.8rem; font-weight: 700; color: var(--text-muted); margin-bottom: 8px;">Daftar Instansi Saat Ini:</div>
                        <div style="display: flex; flex-wrap: wrap; gap: 8px;">
                            @if(!empty($landing->filter_pendidikan))
                                @foreach(explode(',', $landing->filter_pendidikan) as $nama)
                                    @if(trim($nama))
                                        <span style="background: white; border: 1px solid #e2e8f0; padding: 4px 12px; border-radius: 15px; font-size: 0.85rem; font-weight: 700; color: var(--primary);">{{ trim($nama) }}</span>
                                    @endif
                                @endforeach
                            @else
                                <span style="font-size:0.8rem; color: var(--danger); font-style:italic;">Belum ada nama instansi.</span>
                            @endif
                        </div>
                    </div>

                    <div style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
                        <label class="btn-primary" style="background: #e2e8f0; color: var(--text-main); box-shadow: none;">
                            <i class="fa-solid fa-images"></i> Tambah File Foto
                            <input type="file" name="image[]" style="display: none;" accept="image/*" multiple onchange="previewIconMultiple(this, 'container-prev-pendidikan')">
                        </label>
                        <span style="font-size: 0.75rem; color: var(--text-muted);">*Anda bisa memilih lebih dari 1 foto baru sekaligus</span>
                    </div>

                    <div class="input-text-wrapper">
                        <!-- Value sengaja kosong karena fungsinya sekarang untuk menambah (Append) -->
                        <input type="text" id="input-pendidikan" name="name" class="input-text" placeholder="Tambah nama baru. Cth: UNSUB, STIESA, SMK Subang" value="">
                        <button type="submit" class="btn-primary" style="border-radius: 0; padding: 0 2rem; box-shadow: none; background: var(--secondary);"><i class="fa-solid fa-plus"></i> Tambah Data</button>
                    </div>
                </div>
            </form>
        </div>

        {{-- 2. DEKAT PEMERINTAH --}}
        <div class="filter-item" id="box-pemerintah">
            <div class="header-action-group">
                <h3 class="group-title"><i class="fa-solid fa-building-columns" style="color: #f59e0b;"></i> Dekat Pemerintah</h3>
                <div class="action-buttons">
                    <button type="button" class="btn-action-small edit" onclick="fokusInput('input-pemerintah')"><i class="fa-solid fa-pen"></i> Tambah Instansi</button>
                    <!-- Tombol Reset Teks -->
                    <button type="button" class="btn-action-small delete" onclick="hapusFilter('input-pemerintah', 'reset-pemerintah')"><i class="fa-solid fa-eraser"></i> Bersihkan Semua Teks</button>
                    
                    <form action="{{ route('landing.filter.clear_all', 'pemerintah') }}" method="POST" style="margin:0;">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn-action-small delete" onclick="return confirm('Yakin hapus SEMUA foto di kategori Instansi Pemerintah?')">
                            <i class="fa-solid fa-trash-can"></i> Hapus Semua Foto
                        </button>
                    </form>
                </div>
            </div>

            <div class="filter-photo-manager">
                <div style="font-size: 0.85rem; font-weight: 700; color: var(--text-main);">Daftar Foto Saat Ini (Klik [x] untuk Hapus Satuan):</div>
                <div class="photo-grid-preview" id="container-prev-pemerintah">
                    @if(!empty($landing->image_pemerintah))
                        @foreach(explode(',', $landing->image_pemerintah) as $index => $img)
                            @if(!empty(trim($img)))
                            <div class="photo-thumb-wrapper">
                                <img src="{{ asset('images/filters/'.trim($img)) }}">
                                <form action="{{ route('landing.filter.delete_photo', ['category' => 'pemerintah', 'photo_index' => $index]) }}" method="POST" style="margin:0;">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-delete-photo" onclick="return confirm('Hapus foto ini?')">×</button>
                                </form>
                            </div>
                            @endif
                        @endforeach
                    @else
                        <span style="font-size:0.8rem; color: var(--text-muted); font-style:italic;">Belum ada foto.</span>
                    @endif
                </div>
            </div>

            <form action="{{ route('landing.filter.update', 'pemerintah') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="reset_text" id="reset-pemerintah" value="false">
                
                <div class="input-group-grid">
                    <!-- TAMPILAN LABEL DAFTAR NAMA SAAT INI -->
                    <div style="background: #f8fafc; border: 1px dashed var(--border-light); padding: 12px; border-radius: 0.5rem; margin-bottom: 5px;">
                        <div style="font-size: 0.8rem; font-weight: 700; color: var(--text-muted); margin-bottom: 8px;">Daftar Instansi Saat Ini:</div>
                        <div style="display: flex; flex-wrap: wrap; gap: 8px;">
                            @if(!empty($landing->filter_pemerintah))
                                @foreach(explode(',', $landing->filter_pemerintah) as $nama)
                                    @if(trim($nama))
                                        <span style="background: white; border: 1px solid #e2e8f0; padding: 4px 12px; border-radius: 15px; font-size: 0.85rem; font-weight: 700; color: var(--primary);">{{ trim($nama) }}</span>
                                    @endif
                                @endforeach
                            @else
                                <span style="font-size:0.8rem; color: var(--danger); font-style:italic;">Belum ada nama instansi.</span>
                            @endif
                        </div>
                    </div>

                    <div style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
                        <label class="btn-primary" style="background: #e2e8f0; color: var(--text-main); box-shadow: none;">
                            <i class="fa-solid fa-images"></i> Tambah File Foto
                            <input type="file" name="image[]" style="display: none;" accept="image/*" multiple onchange="previewIconMultiple(this, 'container-prev-pemerintah')">
                        </label>
                        <span style="font-size: 0.75rem; color: var(--text-muted);">*Anda bisa memilih lebih dari 1 foto baru sekaligus</span>
                    </div>

                    <div class="input-text-wrapper">
                        <!-- Value sengaja kosong karena fungsinya sekarang untuk menambah (Append) -->
                        <input type="text" id="input-pemerintah" name="name" class="input-text" placeholder="Tambah nama baru. Cth: PEMDA, BPJS, Disnakertrans" value="">
                        <button type="submit" class="btn-primary" style="border-radius: 0; padding: 0 2rem; box-shadow: none; background: var(--secondary);"><i class="fa-solid fa-plus"></i> Tambah Data</button>
                    </div>
                </div>
            </form>
        </div>

        {{-- 3. DEKAT PERUSAHAAN --}}
        <div class="filter-item" id="box-perusahaan">
            <div class="header-action-group">
                <h3 class="group-title"><i class="fa-solid fa-city" style="color: #8b5cf6;"></i> Dekat Perusahaan</h3>
                <div class="action-buttons">
                    <button type="button" class="btn-action-small edit" onclick="fokusInput('input-perusahaan')"><i class="fa-solid fa-pen"></i> Tambah Instansi</button>
                    <!-- Tombol Reset Teks -->
                    <button type="button" class="btn-action-small delete" onclick="hapusFilter('input-perusahaan', 'reset-perusahaan')"><i class="fa-solid fa-eraser"></i> Bersihkan Semua Teks</button>
                    
                    <form action="{{ route('landing.filter.clear_all', 'perusahaan') }}" method="POST" style="margin:0;">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn-action-small delete" onclick="return confirm('Yakin hapus SEMUA foto di kategori Instansi Perusahaan?')">
                            <i class="fa-solid fa-trash-can"></i> Hapus Semua Foto
                        </button>
                    </form>
                </div>
            </div>

            <div class="filter-photo-manager">
                <div style="font-size: 0.85rem; font-weight: 700; color: var(--text-main);">Daftar Foto Saat Ini (Klik [x] untuk Hapus Satuan):</div>
                <div class="photo-grid-preview" id="container-prev-perusahaan">
                    @if(!empty($landing->image_perusahaan))
                        @foreach(explode(',', $landing->image_perusahaan) as $index => $img)
                            @if(!empty(trim($img)))
                            <div class="photo-thumb-wrapper">
                                <img src="{{ asset('images/filters/'.trim($img)) }}">
                                <form action="{{ route('landing.filter.delete_photo', ['category' => 'perusahaan', 'photo_index' => $index]) }}" method="POST" style="margin:0;">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-delete-photo" onclick="return confirm('Hapus foto ini?')">×</button>
                                </form>
                            </div>
                            @endif
                        @endforeach
                    @else
                        <span style="font-size:0.8rem; color: var(--text-muted); font-style:italic;">Belum ada foto.</span>
                    @endif
                </div>
            </div>

            <form action="{{ route('landing.filter.update', 'perusahaan') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="reset_text" id="reset-perusahaan" value="false">
                
                <div class="input-group-grid">
                    <!-- TAMPILAN LABEL DAFTAR NAMA SAAT INI -->
                    <div style="background: #f8fafc; border: 1px dashed var(--border-light); padding: 12px; border-radius: 0.5rem; margin-bottom: 5px;">
                        <div style="font-size: 0.8rem; font-weight: 700; color: var(--text-muted); margin-bottom: 8px;">Daftar Instansi Saat Ini:</div>
                        <div style="display: flex; flex-wrap: wrap; gap: 8px;">
                            @if(!empty($landing->filter_perusahaan))
                                @foreach(explode(',', $landing->filter_perusahaan) as $nama)
                                    @if(trim($nama))
                                        <span style="background: white; border: 1px solid #e2e8f0; padding: 4px 12px; border-radius: 15px; font-size: 0.85rem; font-weight: 700; color: var(--primary);">{{ trim($nama) }}</span>
                                    @endif
                                @endforeach
                            @else
                                <span style="font-size:0.8rem; color: var(--danger); font-style:italic;">Belum ada nama instansi.</span>
                            @endif
                        </div>
                    </div>

                    <div style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
                        <label class="btn-primary" style="background: #e2e8f0; color: var(--text-main); box-shadow: none;">
                            <i class="fa-solid fa-images"></i> Tambah File Foto
                            <input type="file" name="image[]" style="display: none;" accept="image/*" multiple onchange="previewIconMultiple(this, 'container-prev-perusahaan')">
                        </label>
                        <span style="font-size: 0.75rem; color: var(--text-muted);">*Anda bisa memilih lebih dari 1 foto baru sekaligus</span>
                    </div>

                    <div class="input-text-wrapper">
                        <!-- Value sengaja kosong karena fungsinya sekarang untuk menambah (Append) -->
                        <input type="text" id="input-perusahaan" name="name" class="input-text" placeholder="Tambah nama baru. Cth: PT Taekwang, PT Evoluzione" value="">
                        <button type="submit" class="btn-primary" style="border-radius: 0; padding: 0 2rem; box-shadow: none; background: var(--secondary);"><i class="fa-solid fa-plus"></i> Tambah Data</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- CARD 3: PROFIL & IDENTITAS SIKOSUB --}}
    <div class="admin-card">
        <h2 class="card-title"><i class="fa-solid fa-address-card"></i> Profil & Identitas SIKOSUB</h2>
        
        <form action="{{ route('landing.update_info') }}" method="POST" id="form-profil">
            @csrf
            
            <div class="form-group">
                <div class="header-action-group">
                    <label style="margin:0;">Tentang SIKOSUB</label>
                    <div class="action-buttons">
                        <button type="button" class="btn-action-small edit" onclick="fokusInput('tentang')"><i class="fa-solid fa-pen"></i> Edit Teks</button>
                        <button type="button" class="btn-action-small delete" onclick="resetTextarea('tentang')"><i class="fa-solid fa-rotate-left"></i> Default</button>
                    </div>
                </div>
                <textarea id="tentang" name="tentang" class="form-control" rows="6" placeholder="Tuliskan deskripsi platform..." required>{{ !empty($landing->tentang) ? $landing->tentang : $defaultTentang }}</textarea>
            </div>

            <div class="grid-2">
                <div class="form-group">
                    <div class="header-action-group">
                        <label style="margin:0;">Visi SIKOSUB</label>
                        <div class="action-buttons">
                            <button type="button" class="btn-action-small edit" onclick="fokusInput('visi')"><i class="fa-solid fa-pen"></i> Edit</button>
                            <button type="button" class="btn-action-small delete" onclick="resetTextarea('visi')"><i class="fa-solid fa-rotate-left"></i> Default</button>
                        </div>
                    </div>
                    <textarea id="visi" name="visi" class="form-control" rows="6" placeholder="Tuliskan Visi platform..." required>{{ !empty($landing->visi) ? $landing->visi : $defaultVisi }}</textarea>
                </div>
                
                <div class="form-group">
                    <div class="header-action-group">
                        <label style="margin:0;">Misi SIKOSUB</label>
                        <div class="action-buttons">
                            <button type="button" class="btn-action-small edit" onclick="fokusInput('misi')"><i class="fa-solid fa-pen"></i> Edit</button>
                            <button type="button" class="btn-action-small delete" onclick="resetTextarea('misi')"><i class="fa-solid fa-rotate-left"></i> Default</button>
                        </div>
                    </div>
                    <textarea id="misi" name="misi" class="form-control" rows="6" placeholder="Gunakan Enter untuk daftar nomor..." required>{{ !empty($landing->misi) ? $landing->misi : $defaultMisi }}</textarea>
                </div>
            </div>
            
            <div style="text-align: right; margin-top: 1rem; border-top: 1px solid var(--border-light); padding-top: 1.5rem;">
                <button type="submit" class="btn-primary"><i class="fa-solid fa-floppy-disk"></i> Simpan Perubahan Profil SIKOSUB</button>
            </div>
        </form>
    </div>
</div>

<script>
    function fokusInput(elementId) {
        const el = document.getElementById(elementId);
        el.focus();
        const val = el.value;
        el.value = '';
        el.value = val;
        el.style.boxShadow = '0 0 0 4px rgba(79, 70, 229, 0.2)';
        el.style.borderColor = '#4f46e5';
        setTimeout(() => { el.style.boxShadow = ''; el.style.borderColor = ''; }, 1500);
    }

    // UPDATE: Fungsi ini sekarang akan mengirim sinyal reset sebelum form disubmit
    function hapusFilter(elementId, resetId) {
        if(confirm('Yakin ingin MENGHAPUS SEMUA daftar teks nama instansi ini dari awal? (Foto tidak akan terhapus)')) {
            document.getElementById(resetId).value = 'true'; // Kirim sinyal reset ke controller
            document.getElementById(elementId).value = ''; // Kosongkan input
            document.getElementById(elementId).closest('form').submit(); 
        }
    }

    function resetTextarea(elementId) {
        if(confirm('Apakah Anda yakin ingin mereset teks ini ke kalimat bawaan (default) aplikasi?')) {
            document.getElementById(elementId).value = '';
            document.getElementById('form-profil').submit();
        }
    }

    function previewIconMultiple(input, containerId) {
        const container = document.getElementById(containerId);
        
        // Hapus preview sementara/baru yang digenerate JavaScript sebelumnya (jika ada)
        const oldNewPreviews = container.querySelectorAll('.new-preview');
        oldNewPreviews.forEach(el => el.remove());

        if (input.files) {
            Array.from(input.files).forEach(file => {
                var reader = new FileReader();
                reader.onload = function(e) {
                    var wrapper = document.createElement('div');
                    wrapper.className = 'photo-thumb-wrapper new-preview';
                    
                    var img = document.createElement('img');
                    img.src = e.target.result;
                    
                    wrapper.appendChild(img);
                    container.appendChild(wrapper);
                }
                reader.readAsDataURL(file);
            });
        }
    }
</script>
@endsection