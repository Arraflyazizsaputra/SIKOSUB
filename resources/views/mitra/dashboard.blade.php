@php
    // SCRIPT PENDETEKSI AKUN OTOMATIS
    $activeUser = Auth::guard('mitra')->user() ?? Auth::guard('web')->user() ?? Auth::guard('superadmin')->user() ?? Auth::user();
    
    // Mengambil nama dari kolom yang tersedia di database
    $namaMitra = $activeUser ? ($activeUser->name ?? $activeUser->nama_pemilik ?? $activeUser->nama ?? 'Mitra') : 'Mitra';
    $mitraId = $activeUser ? $activeUser->id : 0;

    // SINKRONISASI STATISTIK OTOMATIS (Mendeteksi berdasarkan ID atau Nama Pemilik)
    $myKosts = \Illuminate\Support\Facades\DB::table('kosts')
        ->where('mitra_id', $mitraId)
        ->orWhere('pemilik', $namaMitra)
        ->orWhere('disewakan_oleh', $namaMitra)
        ->get();
        
    $totalProperti = $myKosts->count();
    $totalKunjungan = $myKosts->sum('visit_count');
    
    // Kalkulasi rata-rata rating (Mencegah error jika belum ada rating)
    $rataRating = $myKosts->avg('rating');
    $rataRatingFormat = number_format($rataRating ?? 0, 1);
    
    // Hitung total ulasan/review yang masuk ke properti milik mitra ini
    $kostIds = $myKosts->pluck('id')->toArray();
    $totalUlasan = empty($kostIds) ? 0 : \Illuminate\Support\Facades\DB::table('reviews')->whereIn('kost_id', $kostIds)->count();
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Mitra - SIKOSUB</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        /* =========================================
           MODERN DASHBOARD MITRA SYSTEM
           ========================================= */
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

        /* --- NAVBAR / TOPBAR STYLING --- */
        .topbar {
            background: white;
            padding: 0 clamp(20px, 5vw, 40px); height: 75px;
            display: flex; align-items: center; justify-content: space-between;
            box-shadow: 0 4px 20px rgba(0,0,0,0.03);
            border-bottom: 1px solid var(--border);
            position: sticky; top: 0; z-index: 100;
        }
        .topbar-brand { display: flex; align-items: center; gap: 12px; text-decoration: none;}
        .topbar-logo { 
            width: 42px; 
            height: 42px; 
            object-fit: contain; 
            border-radius: 8px; 
        }
        .topbar-name { font-size: 22px; font-weight: 900; color: var(--primary-dark); letter-spacing: 0.5px;}
        .topbar-badge { background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 8px; padding: 4px 12px; color: var(--primary); font-size: 11px; font-weight: 800; letter-spacing: 0.5px; }
        
        .topbar-right { display: flex; align-items: center; gap: 20px; }
        .topbar-user { color: var(--text-muted); font-weight: 600; font-size: 14px; display: flex; align-items: center; gap: 8px;}
        .topbar-user span { color: var(--text-main); font-weight: 800; }
        
        .btn-logout { background-color: white; border: 2px solid #fee2e2; color: var(--danger); padding: 8px 18px; border-radius: 8px; font-family: 'Outfit', sans-serif; font-size: 13px; font-weight: 700; cursor: pointer; transition: all 0.2s; display: flex; align-items: center; gap: 6px;}
        .btn-logout:hover { background-color: var(--danger); color: white; border-color: var(--danger); }

        /* --- DROPDOWN PROFIL --- */
        .profile-dropdown-wrapper { position: relative; cursor: pointer; display: flex; align-items: center; }
        .profile-avatar { width: 42px; height: 42px; border-radius: 50%; object-fit: cover; border: 2px solid var(--border); transition: 0.2s; }
        .profile-dropdown-wrapper:hover .profile-avatar { border-color: var(--primary); }
        
        .dropdown-menu { display: none; position: absolute; right: 0; top: 100%; margin-top: 15px; background: white; min-width: 220px; border-radius: 16px; box-shadow: 0 10px 40px rgba(0,0,0,0.1); border: 1px solid var(--border); padding: 10px; z-index: 9999; }
        .dropdown-menu::before { content: ''; position: absolute; top: -6px; right: 15px; width: 12px; height: 12px; background: white; transform: rotate(45deg); border-left: 1px solid var(--border); border-top: 1px solid var(--border); }
        .dropdown-menu a { display: flex; align-items: center; gap: 10px; padding: 12px 15px; color: var(--text-muted); text-decoration: none; font-size: 14px; font-weight: 600; transition: 0.2s; border-radius: 10px; }
        .dropdown-menu a:hover { background-color: #eff6ff; color: var(--primary); }

        /* --- CONTENT STYLING --- */
        .container { max-width: 1200px; margin: 0 auto; padding: 40px 24px; }

        /* Welcome Card */
        .welcome-card { background: linear-gradient(135deg, var(--primary), var(--primary-dark)); border-radius: 20px; padding: 40px; color: white; margin-bottom: 40px; position: relative; overflow: hidden; box-shadow: 0 15px 30px rgba(26, 86, 219, 0.2); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 20px;}
        .welcome-card::after { content: '\f1ad'; font-family: 'Font Awesome 6 Free'; font-weight: 900; position: absolute; right: -20px; bottom: -40px; font-size: 180px; opacity: 0.1; transform: rotate(-15deg); pointer-events: none;}
        
        .welcome-text h1 { font-size: clamp(24px, 4vw, 32px); font-weight: 900; margin-bottom: 10px; }
        .welcome-text p { color: #e0e7ff; font-size: 15px; font-weight: 400; max-width: 500px; line-height: 1.6;}
        
        .welcome-action { position: relative; z-index: 2; }
        .btn-tambah-utama { background: white; color: var(--primary); padding: 14px 28px; border-radius: 50px; font-weight: 800; font-size: 15px; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; transition: 0.3s; box-shadow: 0 10px 20px rgba(0,0,0,0.1); }
        .btn-tambah-utama:hover { transform: translateY(-3px); box-shadow: 0 15px 25px rgba(0,0,0,0.15); }

        /* Stats Grid */
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 20px; margin-bottom: 40px; }
        .stat-card { background: white; border-radius: 20px; padding: 25px; box-shadow: 0 4px 20px rgba(0,0,0,0.02); border: 1px solid var(--border); transition: 0.3s; display: flex; align-items: center; gap: 20px; }
        .stat-card:hover { transform: translateY(-5px); box-shadow: 0 15px 30px rgba(0,0,0,0.05); border-color: #cbd5e1;}
        .stat-icon { width: 60px; height: 60px; border-radius: 16px; display: flex; align-items: center; justify-content: center; font-size: 24px; flex-shrink: 0; }
        .stat-icon.blue { background: #eff6ff; color: var(--primary); }
        .stat-icon.purple { background: #f5f3ff; color: #7c3aed; }
        .stat-icon.green { background: #ecfdf5; color: var(--success); }
        .stat-icon.orange { background: #fffbeb; color: var(--warning); }
        
        .stat-info h3 { font-size: 28px; font-weight: 900; color: var(--text-main); line-height: 1;}
        .stat-info p { font-size: 13px; font-weight: 600; color: var(--text-muted); margin-top: 5px; text-transform: uppercase; letter-spacing: 0.5px;}

        /* Modul Grouping Grid */
        .module-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 30px; }
        
        .module-card { background: white; border-radius: 20px; padding: 30px; box-shadow: 0 4px 20px rgba(0,0,0,0.02); border: 1px solid var(--border); }
        .module-header { display: flex; align-items: center; gap: 12px; margin-bottom: 25px; padding-bottom: 15px; border-bottom: 2px dashed var(--border); }
        .module-header-icon { width: 45px; height: 45px; background: #f1f5f9; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 18px; color: var(--text-main); }
        .module-header h2 { font-size: 18px; font-weight: 800; color: var(--text-main); }
        
        .menu-list { display: flex; flex-direction: column; gap: 15px; }
        .menu-item { display: flex; align-items: center; justify-content: space-between; padding: 16px 20px; background: white; border: 1px solid var(--border); border-radius: 14px; cursor: pointer; transition: 0.2s; text-decoration: none; }
        .menu-item:hover { background: #f8fafc; border-color: var(--primary); transform: translateX(5px); box-shadow: 0 5px 15px rgba(26, 86, 219, 0.05); }
        
        .menu-item-left { display: flex; align-items: center; gap: 15px; }
        .menu-item-icon { font-size: 20px; color: var(--primary); width: 24px; text-align: center; }
        .menu-item-text { display: flex; flex-direction: column; gap: 2px; }
        .menu-item-title { font-size: 15px; font-weight: 700; color: var(--text-main); }
        .menu-item-desc { font-size: 12px; font-weight: 500; color: var(--text-muted); }
        .menu-item-arrow { color: #cbd5e1; transition: 0.2s; }
        .menu-item:hover .menu-item-arrow { color: var(--primary); transform: translateX(3px); }

        @media (max-width: 768px) {
            .welcome-card { flex-direction: column; align-items: flex-start; text-align: left; }
            .topbar-name { display: none; }
            .topbar-user { display: none; }
        }
    </style>
</head>
<body>

    <nav class="topbar">
        
        <a href="{{ url('/mitra/dashboard') }}" class="topbar-brand">
            
            <img src="{{ asset('images/sikosub.png') }}" alt="Logo SIKOSUB" class="topbar-logo">
            
            <span class="topbar-name">SIKOSUB</span>
            <span class="topbar-badge">MITRA</span>
        </a>

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
        
        <div class="welcome-card">
            <div class="welcome-text">
                <h1>Selamat Datang di Dasbor Mitra! 📊</h1>
                <p>Kelola properti kost Anda dengan mudah, pantau statistik pengunjung, dan tingkatkan visibilitas penyewaan melalui SIKOSUB.</p>
            </div>
            <div class="welcome-action">
                <a href="{{ route('mitra.kost.create') }}" class="btn-tambah-utama">
                    <i class="fa-solid fa-circle-plus"></i> Tambah Properti Baru
                </a>
            </div>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon blue"><i class="fa-solid fa-building-user"></i></div>
                <div class="stat-info">
                    <!-- MENAMPILKAN TOTAL PROPERTI DINAMIS -->
                    <h3>{{ $totalProperti }}</h3>
                    <p>Total Properti</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon purple"><i class="fa-solid fa-eye"></i></div>
                <div class="stat-info">
                    <!-- MENAMPILKAN TOTAL KUNJUNGAN DINAMIS -->
                    <h3>{{ $totalKunjungan }}</h3>
                    <p>Kunjungan Profil</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon green"><i class="fa-solid fa-star"></i></div>
                <div class="stat-info">
                    <!-- MENAMPILKAN RATA-RATA RATING DINAMIS -->
                    <h3>{{ $rataRatingFormat }}</h3>
                    <p>Rata-rata Rating</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon orange"><i class="fa-solid fa-comments"></i></div>
                <div class="stat-info">
                    <!-- MENAMPILKAN TOTAL ULASAN DINAMIS -->
                    <h3>{{ $totalUlasan }}</h3>
                    <p>Ulasan Masuk</p>
                </div>
            </div>
        </div>

        <div class="module-grid">
            
            <div class="module-card">
                <div class="module-header">
                    <div class="module-header-icon"><i class="fa-solid fa-house-laptop"></i></div>
                    <h2>Manajemen Properti</h2>
                </div>
                <div class="menu-list">
                    <a href="{{ route('mitra.kost.index') }}" class="menu-item">
                        <div class="menu-item-left">
                            <i class="fa-solid fa-list-check menu-item-icon"></i>
                            <div class="menu-item-text">
                                <span class="menu-item-title">Daftar Properti Saya</span>
                                <span class="menu-item-desc">Lihat, edit, atau hapus data kost Anda.</span>
                            </div>
                        </div>
                        <i class="fa-solid fa-chevron-right menu-item-arrow"></i>
                    </a>
                    
                    <a href="{{ route('mitra.kost.create') }}" class="menu-item">
                        <div class="menu-item-left">
                            <i class="fa-solid fa-folder-plus menu-item-icon" style="color: var(--success);"></i>
                            <div class="menu-item-text">
                                <span class="menu-item-title">Tambah Properti Baru</span>
                                <span class="menu-item-desc">Publikasikan unit kost baru ke sistem.</span>
                            </div>
                        </div>
                        <i class="fa-solid fa-chevron-right menu-item-arrow"></i>
                    </a>

                    <a href="{{ route('mitra.kost.index') }}" class="menu-item">
                        <div class="menu-item-left">
                            <i class="fa-solid fa-images menu-item-icon" style="color: var(--warning);"></i>
                            <div class="menu-item-text">
                                <span class="menu-item-title">Kelola Galeri Foto</span>
                                <span class="menu-item-desc">Atur foto-foto detail setiap kamar kost.</span>
                            </div>
                        </div>
                        <i class="fa-solid fa-chevron-right menu-item-arrow"></i>
                    </a>
                </div>
            </div>

            <div class="module-card">
                <div class="module-header">
                    <div class="module-header-icon"><i class="fa-solid fa-user-shield"></i></div>
                    <h2>Pengaturan Akun & Profil</h2>
                </div>
                <div class="menu-list">
                    <a href="{{ route('mitra.profile.edit') }}" class="menu-item">
                        <div class="menu-item-left">
                            <i class="fa-solid fa-id-card menu-item-icon"></i>
                            <div class="menu-item-text">
                                <span class="menu-item-title">Informasi Pribadi</span>
                                <span class="menu-item-desc">Perbarui nama, email, dan detail profil.</span>
                            </div>
                        </div>
                        <i class="fa-solid fa-chevron-right menu-item-arrow"></i>
                    </a>
                    
                    <a href="{{ route('mitra.profile.password') }}" class="menu-item">
                        <div class="menu-item-left">
                            <i class="fa-solid fa-lock menu-item-icon" style="color: var(--danger);"></i>
                            <div class="menu-item-text">
                                <span class="menu-item-title">Keamanan Sandi</span>
                                <span class="menu-item-desc">Ubah password untuk keamanan akun.</span>
                            </div>
                        </div>
                        <i class="fa-solid fa-chevron-right menu-item-arrow"></i>
                    </a>

                    <a href="{{ route('mitra.profile.edit') }}" class="menu-item">
                        <div class="menu-item-left">
                            <i class="fa-brands fa-whatsapp menu-item-icon" style="color: var(--success);"></i>
                            <div class="menu-item-text">
                                <span class="menu-item-title">Kontak WhatsApp</span>
                                <span class="menu-item-desc">Ubah nomor yang terhubung ke pencari kost.</span>
                            </div>
                        </div>
                        <i class="fa-solid fa-chevron-right menu-item-arrow"></i>
                    </a>
                </div>
            </div>

            <div class="module-card">
                <div class="module-header">
                    <div class="module-header-icon"><i class="fa-solid fa-circle-info"></i></div>
                    <h2>Pusat Bantuan & Info</h2>
                </div>
                <div class="menu-list">
                    <a href="{{ route('mitra.bantuan.index') }}" class="menu-item">
                        <div class="menu-item-left">
                            <i class="fa-solid fa-headset menu-item-icon"></i>
                            <div class="menu-item-text">
                                <span class="menu-item-title">Hubungi Admin SIKOSUB</span>
                                <span class="menu-item-desc">Bantuan terkait kendala teknis aplikasi.</span>
                            </div>
                        </div>
                        <i class="fa-solid fa-chevron-right menu-item-arrow"></i>
                    </a>
                    
                    <a href="{{ route('mitra.bantuan.panduan') }}" class="menu-item">
                        <div class="menu-item-left">
                            <i class="fa-solid fa-book-open-reader menu-item-icon" style="color: #8b5cf6;"></i>
                            <div class="menu-item-text">
                                <span class="menu-item-title">Panduan Mitra</span>
                                <span class="menu-item-desc">Cara menggunakan dasbor dengan optimal.</span>
                            </div>
                        </div>
                        <i class="fa-solid fa-chevron-right menu-item-arrow"></i>
                    </a>
                </div>
            </div>

        </div>
    </div>

    <script>
        function toggleDropdown(event) {
            event.stopPropagation(); 
            const menu = document.getElementById('profileDropdown');
            if(menu.style.display === 'block') {
                menu.style.display = 'none';
                menu.style.opacity = '0';
            } else {
                menu.style.display = 'block';
                setTimeout(() => menu.style.opacity = '1', 10);
            }
        }

        window.onclick = function(event) {
            const menu = document.getElementById('profileDropdown');
            if (menu.style.display === 'block') {
                menu.style.display = 'none';
                menu.style.opacity = '0';
            }
        }
    </script>
</body>
</html>