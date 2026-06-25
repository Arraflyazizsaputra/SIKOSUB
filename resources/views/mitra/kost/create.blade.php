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
    <title>Tambah Properti Baru - SIKOSUB</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --primary: #1a56db;
            --primary-dark: #1e40af;
            --secondary: #0ea5e9;
            --success: #10b981;
            --bg-body: #f8fafc;
            --card-bg: #ffffff;
            --text-main: #0f172a;
            --text-muted: #64748b;
            --border: #e2e8f0;
            --danger: #ef4444;
            --warning: #f59e0b;
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
        
        /* Left Section Group */
        .topbar-left { display: flex; align-items: center; gap: 20px; }
        
        .topbar-brand { display: flex; align-items: center; gap: 12px; text-decoration: none;}
        .topbar-logo { width: 42px; height: 42px; object-fit: contain; border-radius: 8px; }
        .topbar-name { font-size: 22px; font-weight: 900; color: var(--primary-dark); }
        .topbar-badge { background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 8px; padding: 4px 12px; color: var(--primary); font-size: 11px; font-weight: 800; letter-spacing: 0.5px; margin-left: 5px; }
        
        .btn-kembali { background: white; border: 1px solid var(--border); padding: 10px 20px; border-radius: 10px; font-weight: 700; color: var(--text-main); text-decoration: none; display: inline-flex; align-items: center; gap: 8px; transition: 0.2s; font-size: 14px;}
        .btn-kembali:hover { background: #f1f5f9; border-color: #cbd5e1; }

        /* Right Section Group & Dropdown (Perbaikan CSS yang hilang) */
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
        .container { max-width: 1100px; margin: 0 auto; padding: 40px 24px; }
        
        .page-header { margin-bottom: 30px; }
        .page-title { font-size: 28px; font-weight: 900; color: var(--text-main); }
        .page-subtitle { font-size: 14px; color: var(--text-muted); font-weight: 500; margin-top: 4px; }

        /* Card Form Base */
        .admin-card { background: white; border-radius: 20px; padding: 30px; margin-bottom: 30px; box-shadow: 0 4px 25px rgba(0,0,0,0.02); border: 1px solid var(--border); }
        .card-header { font-size: 16px; font-weight: 800; color: var(--text-main); margin-bottom: 25px; padding-bottom: 12px; border-bottom: 2px dashed #f1f5f9; display: flex; align-items: center; gap: 10px; text-transform: uppercase; letter-spacing: 0.5px; }
        
        /* Form Grid System (Anti-Tumbukan saat di-Zoom) */
        .form-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 20px; margin-bottom: 15px; }
        .form-group { display: flex; flex-direction: column; gap: 8px; }
        .form-group label { font-size: 13px; font-weight: 700; color: var(--text-main); }
        
        .form-control { width: 100%; padding: 14px 16px; background: #f8fafc; border: 1px solid var(--border); border-radius: 10px; font-size: 14px; color: var(--text-main); outline: none; transition: 0.3s; font-family: 'Outfit', sans-serif; box-sizing: border-box; }
        .form-control:focus { background: white; border-color: var(--primary); box-shadow: 0 0 0 3px rgba(26, 86, 219, 0.1); }
        
        /* Buttons Group */
        .form-actions { display: flex; justify-content: flex-end; gap: 15px; margin-top: 20px; padding-top: 25px; border-top: 1px solid var(--border); }
        .btn-cancel { padding: 14px 30px; background: white; border: 1px solid var(--border); border-radius: 12px; font-weight: 700; color: var(--text-muted); cursor: pointer; transition: 0.2s; font-size: 14px; text-decoration: none; display: inline-flex; align-items: center; }
        .btn-cancel:hover { background: #f1f5f9; color: var(--text-main); }
        .btn-save { padding: 14px 40px; background: var(--primary); border: none; border-radius: 12px; font-weight: 700; color: white; cursor: pointer; transition: 0.3s; font-size: 14px; box-shadow: 0 4px 15px rgba(26, 86, 219, 0.3); display: inline-flex; align-items: center; gap: 8px; }
        .btn-save:hover { background: var(--primary-dark); transform: translateY(-2px); }
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
            <a href="{{ route('mitra.kost.index') }}" class="btn-kembali">
                <i class="fa-solid fa-arrow-left"></i> Batal & Kembali
            </a>
        </div>

        <div class="topbar-right">
            <div class="topbar-user">
                Halo, <span>{{ $namaMitra }}</span>
            </div>
            
            <form action="{{ route('logout') }}" method="POST" style="display:inline;" onsubmit="return confirm('Apakah Anda yakin ingin keluar?');">
                @csrf
                <button type="submit" class="btn-logout"><i class="fa-solid fa-right-from-bracket"></i> LOGOUT</button>
            </form>

            <div class="profile-dropdown-wrapper" onclick="toggleDropdown(event)">
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
            <h1 class="page-title">Daftarkan Kost Baru</h1>
            <p class="page-subtitle">Isi data kelengkapan unit properti Anda dengan benar untuk dipublikasikan</p>
        </div>

        <form action="{{ route('mitra.kost.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="admin-card">
                <div class="card-header"><i class="fa-solid fa-hotel" style="color: var(--primary);"></i> Informasi Utama & Harga</div>
                
                <div class="form-grid">
                    <div class="form-group">
                        <label>Nama Properti Kost <span style="color:var(--danger)">*</span></label>
                        <input type="text" name="nama_kost" class="form-control" placeholder="Cth: Kost Srikandi Jabar" required>
                    </div>
                    <div class="form-group">
                        <label>Tipe Gender Kost <span style="color:var(--danger)">*</span></label>
                        <select name="tipe_kost" class="form-control" required>
                            <option value="putra">Putra</option>
                            <option value="putri">Putri</option>
                            <option value="campur">Campur</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Harga Per Bulan (Rp) <span style="color:var(--danger)">*</span></label>
                        <input type="number" name="harga_per_bulan" class="form-control" placeholder="Cth: 600000" required>
                    </div>
                    <div class="form-group">
                        <label>Harga Diskon Per Bulan (Rp - Opsional)</label>
                        <input type="number" name="harga_diskon" class="form-control" placeholder="Kosongkan jika tidak ada">
                    </div>
                </div>

                <div class="form-grid" style="grid-template-columns: 2fr 1fr;">
                    <div class="form-group">
                        <label>Alamat Lengkap Properti <span style="color:var(--danger)">*</span></label>
                        <input type="text" name="alamat_lengkap" class="form-control" placeholder="Nama jalan, RT/RW, nomor bangunan, kelurahan" required>
                    </div>
                    <div class="form-group">
                        <label>Gambar Utama Unit <span style="color:var(--danger)">*</span></label>
                        <input type="file" name="gambar_utama" class="form-control" accept="image/*" required>
                    </div>
                </div>
            </div>

            <div class="admin-card">
                <div class="card-header"><i class="fa-solid fa-map-location-dot" style="color: var(--success);"></i> Penempatan Lokasi & Kontak</div>
                
                <div class="form-grid">
                    <div class="form-group">
                        <label>Kategori Filter Wilayah</label>
                        <select name="kategori_wilayah" class="form-control">
                            <option value="">-- Pilih Wilayah Terdekat --</option>
                            <option value="unsub">Dekat Instansi Pendidikan (UNSUB)</option>
                            <option value="pemda">Dekat Instansi Pemerintah (Pemda)</option>
                            <option value="perusahaan">Dekat Instansi Perusahaan (Pabrik)</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Nama Detail Tempat Wilayah</label>
                        <input type="text" name="detail_wilayah" class="form-control" placeholder="Cth: Belakang Kampus Utama UNSUB">
                    </div>
                    <div class="form-group">
                        <label>Link Google Maps Share URL</label>
                        <input type="text" name="maps" class="form-control" placeholder="https://goo.gl/maps/...">
                    </div>
                    <div class="form-group">
                        <label>Nomor WhatsApp Utama Pemilik</label>
                        <input type="text" name="no_wa" class="form-control" placeholder="Wajib awali angka 62xxx">
                    </div>
                </div>
            </div>

            <div class="admin-card">
                <div class="card-header"><i class="fa-solid fa-list-check" style="color: var(--warning);"></i> Spesifikasi & Detail Kelengkapan</div>
                
                <div class="form-grid">
                    <div class="form-group">
                        <label>Spesifikasi Luas Kamar</label>
                        <input type="text" name="spesifikasi_kamar" class="form-control" placeholder="Cth: Ukuran 3x4 Meter">
                    </div>
                    <div class="form-group">
                        <label>Fasilitas di Dalam Kamar</label>
                        <input type="text" name="fasilitas_kamar" class="form-control" placeholder="Cth: Kasur, Lemari, Meja Belajar">
                    </div>
                    <div class="form-group">
                        <label>Fasilitas Kamar Mandi</label>
                        <input type="text" name="fasilitas_km" class="form-control" placeholder="Cth: Kamar Mandi Dalam, Kloset Duduk">
                    </div>
                    <div class="form-group">
                        <label>Fasilitas Umum Bersama</label>
                        <input type="text" name="fasilitas_umum" class="form-control" placeholder="Cth: Dapur Bersama, WiFi, Kulkas">
                    </div>
                </div>

                <div class="form-grid">
                    <div class="form-group">
                        <label>Fasilitas Parkir Kendaraan</label>
                        <input type="text" name="fasilitas_parkir" class="form-control" placeholder="Cth: Parkir Motor Kanopi Luas">
                    </div>
                    <div class="form-group">
                        <label>Peraturan Tinggal di Kost</label>
                        <input type="text" name="peraturan" class="form-control" placeholder="Cth: Maksimal bertamu jam 22.00 WIB">
                    </div>
                    <div class="form-group">
                        <label>Ketentuan Durasi Sewa</label>
                        <input type="text" name="ketentuan" class="form-control" placeholder="Cth: Minimal sewa per 3 Bulan">
                    </div>
                    <div class="form-group">
                        <label>Tempat Umum Terdekat</label>
                        <input type="text" name="tempat_terdekat" class="form-control" placeholder="Cth: Alfamart, Masjid, Laundry">
                    </div>
                </div>

                <div class="form-actions">
                    <a href="{{ route('mitra.kost.index') }}" class="btn-cancel">Batal</a>
                    <button type="submit" class="btn-save"><i class="fa-solid fa-cloud-arrow-up"></i> Daftarkan Properti Kost</button>
                </div>
            </div>

        </form>
    </div>

    <script>
        function toggleDropdown(event) {
            event.stopPropagation();
            const menu = document.getElementById('profileDropdown');
            if (menu.style.display === 'block') {
                menu.style.display = 'none';
            } else {
                menu.style.display = 'block';
            }
        }

        // Menutup menu jika klik di sembarang tempat di layar
        window.onclick = function(event) {
            const menu = document.getElementById('profileDropdown');
            if (menu && menu.style.display === 'block') {
                menu.style.display = 'none';
            }
        }
    </script>
</body>
</html>