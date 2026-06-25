@php
    // SCRIPT PENDETEKSI AKUN OTOMATIS
    $activeUser = Auth::guard('mitra')->user() ?? Auth::guard('web')->user() ?? Auth::guard('superadmin')->user() ?? Auth::user();
    
    // Mengambil nama dari kolom yang tersedia di database
    $namaMitra = $activeUser ? ($activeUser->name ?? $activeUser->nama_pemilik ?? $activeUser->nama ?? 'Mitra') : 'Mitra';
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Properti Saya - SIKOSUB</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --primary: #1a56db;
            --primary-dark: #1e40af;
            --secondary: #0ea5e9;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --bg-body: #f8fafc;
            --text-main: #0f172a;
            --text-muted: #64748b;
            --border: #e2e8f0;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Outfit', sans-serif; background: var(--bg-body); min-height: 100vh; color: var(--text-main); }

        /* --- NAVBAR --- */
        .topbar {
            background: white; padding: 0 clamp(20px, 5vw, 40px); height: 75px;
            display: flex; align-items: center; justify-content: space-between;
            box-shadow: 0 4px 20px rgba(0,0,0,0.03); border-bottom: 1px solid var(--border);
            position: sticky; top: 0; z-index: 100;
        }
        
        .topbar-left { display: flex; align-items: center; gap: 20px; }
        .topbar-brand { display: flex; align-items: center; gap: 12px; text-decoration: none;}
        .topbar-logo { width: 42px; height: 42px; object-fit: contain; border-radius: 8px; }
        .topbar-name { font-size: 22px; font-weight: 900; color: var(--primary-dark); letter-spacing: 0.5px;}
        .topbar-badge { background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 8px; padding: 4px 12px; color: var(--primary); font-size: 11px; font-weight: 800; letter-spacing: 0.5px; margin-left: 5px;}
        
        .btn-kembali { background: white; border: 1px solid var(--border); padding: 10px 20px; border-radius: 10px; font-weight: 700; color: var(--text-main); text-decoration: none; display: inline-flex; align-items: center; gap: 8px; transition: 0.2s; font-size: 14px;}
        .btn-kembali:hover { background: #f1f5f9; border-color: #cbd5e1; }

        /* --- PERBAIKAN CSS DROPDOWN & USER --- */
        .topbar-right { display: flex; align-items: center; gap: 20px; }
        .topbar-user { font-size: 14px; font-weight: 600; color: var(--text-muted); }
        .btn-logout { background-color: var(--danger); border: none; color: white; padding: 8px 16px; border-radius: 8px; font-size: 12px; font-weight: 700; cursor: pointer; transition: 0.2s; }
        .btn-logout:hover { background-color: #dc2626; }
        
        .profile-dropdown-wrapper { position: relative; cursor: pointer; }
        .profile-avatar { width: 42px; height: 42px; border-radius: 50%; object-fit: cover; border: 2px solid var(--border); transition: 0.2s; }
        .profile-avatar:hover { border-color: var(--primary); }
        
        .dropdown-menu { display: none; position: absolute; right: 0; top: 100%; margin-top: 15px; background: white; min-width: 200px; border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); padding: 10px; z-index: 9999; border: 1px solid var(--border); }
        .dropdown-menu a { display: flex; align-items: center; gap: 10px; padding: 12px; color: var(--text-main); text-decoration: none; font-size: 14px; font-weight: 600; border-radius: 8px; transition: 0.2s; }
        .dropdown-menu a:hover { background-color: #eff6ff; color: var(--primary); }

        /* --- CONTAINER --- */
        .container { max-width: 1200px; margin: 0 auto; padding: 40px 24px; }
        
        .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; flex-wrap: wrap; gap: 15px; }
        .page-title h1 { font-size: 28px; font-weight: 900; color: var(--text-main); }
        .page-title p { color: var(--text-muted); font-size: 14px; font-weight: 500; margin-top: 4px; }

        .btn-tambah { background: var(--primary); color: white; padding: 12px 24px; border-radius: 12px; font-weight: 700; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; transition: 0.3s; border: none; font-size: 14px; cursor: pointer; box-shadow: 0 4px 15px rgba(26, 86, 219, 0.2); }
        .btn-tambah:hover { background: var(--primary-dark); transform: translateY(-2px); }

        /* --- PROPERTY CARDS --- */
        .kost-card { 
            background: white; border: 1px solid var(--border); border-radius: 20px; padding: 20px; 
            margin-bottom: 25px; display: flex; gap: 25px; align-items: center;
            box-shadow: 0 4px 20px rgba(0,0,0,0.02); transition: 0.3s cubic-bezier(0.4, 0, 0.2, 1); 
        }
        .kost-card:hover { transform: translateY(-4px); box-shadow: 0 12px 30px rgba(26, 86, 219, 0.06); border-color: #bfdbfe; }
        
        .kost-img-wrapper { width: 260px; height: 170px; border-radius: 14px; overflow: hidden; flex-shrink: 0; position: relative; }
        .kost-img { width: 100%; height: 100%; object-fit: cover; }
        
        .kost-details { flex-grow: 1; min-width: 0; }
        .badge-row { display: flex; gap: 8px; margin-bottom: 10px; }
        .badge { font-weight: 800; font-size: 11px; padding: 4px 10px; border-radius: 6px; text-transform: uppercase; }
        .badge-type { background: #e0f2fe; color: #0369a1; }
        .badge-status { background: #dcfce7; color: #15803d; }
        .badge-status.penuh { background: #fee2e2; color: #b91c1c; }

        .kost-name { font-size: 20px; font-weight: 900; color: var(--text-main); margin-bottom: 6px; text-transform: uppercase; }
        .kost-price { font-size: 18px; font-weight: 800; color: var(--primary); margin-bottom: 8px; }
        .kost-address { font-size: 13px; color: var(--text-muted); line-height: 1.5; display: flex; align-items: flex-start; gap: 6px; }
        
        /* Actions Column */
        .actions-col { display: flex; flex-direction: column; gap: 10px; width: 180px; flex-shrink: 0; border-left: 1px dashed var(--border); padding-left: 25px; }
        .btn-action { width: 100%; padding: 10px 16px; border-radius: 10px; font-weight: 700; font-size: 13px; text-align: center; text-decoration: none; display: flex; align-items: center; justify-content: center; gap: 8px; cursor: pointer; border: none; transition: 0.2s; }
        .btn-edit { background: #eff6ff; color: var(--primary); }
        .btn-edit:hover { background: var(--primary); color: white; }
        .btn-gallery { background: #fffbeb; color: var(--warning); border: 1px solid #fde68a; }
        .btn-gallery:hover { background: var(--warning); color: white; }
        .btn-delete { background: #fef2f2; color: var(--danger); }
        .btn-delete:hover { background: var(--danger); color: white; }

        /* --- MODAL DIALOG PERBAIKAN --- */
        .modal { display: none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(4px); z-index: 1000; align-items: center; justify-content: center; padding: 20px; opacity: 0; transition: opacity 0.3s ease; }
        .modal.show { display: flex; opacity: 1; }
        .modal-content { background: white; border-radius: 20px; width: 100%; max-width: 600px; padding: 30px; box-shadow: 0 20px 50px rgba(0,0,0,0.15); transform: scale(0.95); transition: transform 0.3s ease; position: relative; display: flex; flex-direction: column; gap: 15px;}
        .modal.show .modal-content { transform: scale(1); }
        .modal-close { position: absolute; top: 20px; right: 20px; background: #f1f5f9; border: none; width: 36px; height: 36px; border-radius: 50%; cursor: pointer; display: flex; align-items: center; justify-content: center; color: var(--text-muted); font-size: 16px; transition: 0.2s; }
        .modal-close:hover { background: #e2e8f0; color: var(--text-main); }
        .modal-title { font-size: 18px; font-weight: 800; display: flex; align-items: center; gap: 10px; }

        #galleryForm { display: flex; flex-direction: column; gap: 15px;}

        /* PERBAIKAN KOTAK UNGGAH AGAR TIDAK BERTUMPUK */
        .gallery-upload-box { border: 2px dashed #cbd5e1; border-radius: 12px; padding: 20px; text-align: center; background: #f8fafc; cursor: pointer; transition: all 0.2s ease; display: flex; flex-direction: column; align-items: center; gap: 8px; margin-bottom: 10px; }
        .gallery-upload-box:hover { border-color: var(--primary); background: #eff6ff; }
        .gallery-upload-box i { font-size: 24px; color: #94a3b8; }
        .gallery-upload-box span { font-weight: 700; font-size: 14px; color: var(--text-main); }
        .gallery-upload-box p { font-size: 12px; color: var(--text-muted); }

        .gallery-preview-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(80px, 1fr)); gap: 10px; max-height: 200px; overflow-y: auto; padding-top: 10px;}
        .preview-item { position: relative; aspect-ratio: 1; border-radius: 8px; overflow: hidden; border: 1px solid var(--border); box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
        .preview-item img { width: 100%; height: 100%; object-fit: cover; }

        @media (max-width: 900px) {
            .kost-card { flex-direction: column; align-items: stretch; }
            .kost-img-wrapper { width: 100%; height: 200px; }
            .actions-col { width: 100%; border-left: none; padding-left: 0; border-top: 1px dashed var(--border); padding-top: 15px; }
        }
    </style>
</head>
<body>

    <nav class="topbar">
        <div class="topbar-left">
            <a href="{{ url('/mitra/dashboard') }}" class="topbar-brand">
                <img src="{{ asset('images/sikosub.png') }}" alt="Logo SIKOSUB" class="topbar-logo">
                <span class="topbar-name">SIKOSUB</span>
                <span class="topbar-badge">MITRA</span>
            </a>
            <a href="{{ url('/mitra/dashboard') }}" class="btn-kembali"><i class="fa-solid fa-arrow-left"></i> Kembali ke Dashboard</a>
        </div>

        <div class="topbar-right">
            <!-- Menampilkan nama dinamis menggunakan variabel $namaMitra -->
            <div class="topbar-user">
                Halo, <span>{{ $namaMitra }}</span>
            </div>
            
            <form action="{{ route('logout') }}" method="POST" style="display:inline;" onsubmit="return confirm('Apakah Anda yakin ingin keluar?');">
                @csrf
                <button type="submit" class="btn-logout"><i class="fa-solid fa-right-from-bracket"></i> LOGOUT</button>
            </form>

            <div class="profile-dropdown-wrapper" onclick="toggleDropdown(event)">
                <!-- Avatar menyesuaikan inisial dinamis -->
                <img src="https://ui-avatars.com/api/?name={{ urlencode($namaMitra) }}&background=1a56db&color=fff" alt="Avatar" class="profile-avatar">
                
                <div class="dropdown-menu" id="profileDropdown">
                    <a href="{{ route('mitra.profile.edit') }}"><i class="fa-solid fa-user-pen"></i> Edit Profil Mitra</a>
                    <a href="{{ route('mitra.kost.index') }}"><i class="fa-solid fa-building"></i> Properti Saya</a>
                    <div style="height: 1px; background: var(--border); margin: 8px 0;"></div>
                    <a href="{{ route('jembatan.publik') }}" target="_blank"><i class="fa-solid fa-globe"></i> Halaman Pengunjung</a>
                </div>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="page-header">
            <div class="page-title">
                <h1>Daftar Properti Kost Saya</h1>
                <p>Manajemen unit hunian, status ketersediaan kamar, dan galeri foto properti Anda</p>
            </div>
            <a href="{{ route('mitra.kost.create') }}" class="btn-tambah"><i class="fa-solid fa-plus"></i> Tambah Properti Baru</a>
        </div>

        @if(session('success'))
            <div style="background: #ecfdf5; border-left: 5px solid var(--success); color: #065f46; padding: 15px 20px; border-radius: 12px; margin-bottom: 25px; font-weight: 700;">
                <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
            </div>
        @endif

        {{-- Loop Kamar Kost Mitra --}}
        @forelse($kosts as $kost)
        <div class="kost-card">
            <div class="kost-img-wrapper">
                <img src="{{ $kost->gambar_utama ? asset('images/kost/'.$kost->gambar_utama) : 'https://images.unsplash.com/photo-1555854877-bab0e564b8d5?auto=format&fit=crop&w=600&q=80' }}" class="kost-img" alt="Properti">
            </div>
            
            <div class="kost-details">
                <div class="badge-row">
                    <span class="badge badge-type">Kost {{ $kost->tipe_kost }}</span>
                    <span class="badge badge-status {{ strtolower($kost->info_kamar) == 'penuh' ? 'penuh' : '' }}">Kamar {{ $kost->info_kamar ?? 'Tersedia' }}</span>
                </div>
                <h3 class="kost-name">{{ $kost->nama_kost }}</h3>
                <div class="kost-price">Rp {{ number_format($kost->harga_per_bulan, 0, ',', '.') }} <span style="font-size: 13px; color: var(--text-muted); font-weight: 500;">/ bulan</span></div>
                <div class="kost-address">
                    <i class="fa-solid fa-location-dot" style="color: var(--danger); margin-top: 2px;"></i>
                    <span>{{ $kost->alamat }}</span>
                </div>
            </div>

            <div class="actions-col">
                <a href="{{ route('mitra.kost.edit', $kost->id) }}" class="btn-action btn-edit"><i class="fa-solid fa-pen-to-square"></i> Edit Data</a>
                <button type="button" class="btn-action btn-gallery" onclick="openGalleryModal({{ $kost->id }}, '{{ $kost->nama_kost }}')"><i class="fa-solid fa-images"></i> Kelola Galeri</button>
                
                <form action="{{ route('mitra.kost.destroy', $kost->id) }}" method="POST" style="width: 100%;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus properti ini secara permanen?');">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn-action btn-delete"><i class="fa-solid fa-trash-can"></i> Hapus Unit</button>
                </form>
            </div>
        </div>
        @empty
        <div style="text-align: center; padding: 60px 20px; background: white; border-radius: 20px; border: 2px dashed var(--border);">
            <i class="fa-solid fa-house-circle-xmark" style="font-size: 4rem; color: #cbd5e1; margin-bottom: 15px;"></i>
            <h3>Belum Ada Properti Terdaftar</h3>
            <p style="color: var(--text-muted); margin-bottom: 20px;">Anda belum mengunggah properti kost satupun ke sistem SIKOSUB.</p>
            <a href="{{ route('mitra.kost.create') }}" class="btn-tambah" style="box-shadow: none;"><i class="fa-solid fa-plus"></i> Daftarkan Properti Sekarang</a>
        </div>
        @endforelse
    </div>

    <div class="modal" id="galleryModal">
        <div class="modal-content">
            <button class="modal-close" onclick="closeGalleryModal()"><i class="fa-solid fa-xmark"></i></button>
            <h2 class="modal-title"><i class="fa-solid fa-images" style="color: var(--warning);"></i> Kelola Galeri</h2>
            
            <form action="#" method="POST" enctype="multipart/form-data" id="galleryForm">
                @csrf
                
                <label for="gallery_files" class="gallery-upload-box">
                    <i class="fa-solid fa-camera-retro"></i>
                    <span>Unggah Foto Kamar Tambahan</span>
                    <p>Bisa memilih lebih dari 1 foto sekaligus (Format JPG/PNG)</p>
                    <input type="file" name="gallery_images[]" id="gallery_files" multiple style="display: none;" accept="image/*" onchange="previewImages(this)">
                </label>
                
                <div class="gallery-preview-grid" id="previewGrid"></div>
                
                <button type="submit" class="btn-tambah" style="width: 100%; justify-content: center; border-radius: 10px; padding: 14px;"><i class="fa-solid fa-floppy-disk"></i> Simpan Galeri Foto</button>
            </form>
        </div>
    </div>

    <script>
        const modal = document.getElementById('galleryModal');
        
        function openGalleryModal(kostId, kostName) {
            document.getElementById('galleryForm').action = `/mitra/kost/${kostId}/gallery-update`;
            modal.classList.add('show');
        }

        function closeGalleryModal() {
            modal.classList.remove('show');
            document.getElementById('previewGrid').innerHTML = '';
            document.getElementById('gallery_files').value = '';
        }

        function previewImages(input) {
            const grid = document.getElementById('previewGrid');
            grid.innerHTML = '';
            if (input.files) {
                Array.from(input.files).forEach(file => {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const div = document.createElement('div');
                        div.className = 'preview-item';
                        div.innerHTML = `<img src="${e.target.result}">`;
                        grid.appendChild(div);
                    }
                    reader.readAsDataURL(file);
                });
            }
        }

        // Fungsi baru untuk Profile Dropdown
        function toggleDropdown(event) {
            event.stopPropagation();
            const menu = document.getElementById('profileDropdown');
            if (menu.style.display === 'block') {
                menu.style.display = 'none';
            } else {
                menu.style.display = 'block';
            }
        }

        // Menutup Dropdown atau Modal jika klik di area kosong
        window.onclick = function(e) {
            // Tutup Modal Galeri
            if(e.target === modal) {
                closeGalleryModal();
            }
            // Tutup Dropdown Profil
            const profileMenu = document.getElementById('profileDropdown');
            if (profileMenu && profileMenu.style.display === 'block') {
                profileMenu.style.display = 'none';
            }
        }
    </script>
</body>
</html>