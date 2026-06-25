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
    <title>Pusat Bantuan - SIKOSUB</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root { 
            --primary: #1a56db; 
            --primary-dark: #1e40af;
            --bg-body: #f8fafc; 
            --text-main: #0f172a; 
            --text-muted: #64748b; 
            --border: #e2e8f0; 
            --success: #10b981; 
            --danger: #ef4444;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Outfit', sans-serif; background: var(--bg-body); color: var(--text-main); line-height: 1.6; min-height: 100vh;}
        
        /* --- NAVBAR (Konsisten dengan halaman lain) --- */
        .topbar { background: white; padding: 0 clamp(20px, 5vw, 40px); height: 75px; display: flex; align-items: center; justify-content: space-between; box-shadow: 0 4px 20px rgba(0,0,0,0.03); border-bottom: 1px solid var(--border); position: sticky; top: 0; z-index: 100; }
        .topbar-left { display: flex; align-items: center; gap: 20px; }
        .topbar-brand { display: flex; align-items: center; gap: 12px; text-decoration: none;}
        .topbar-logo { width: 42px; height: 42px; object-fit: contain; border-radius: 8px; }
        .topbar-name { font-size: 22px; font-weight: 900; color: var(--primary-dark); }
        .topbar-badge { background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 8px; padding: 4px 12px; color: var(--primary); font-size: 11px; font-weight: 800; letter-spacing: 0.5px; margin-left: 5px; }

        .topbar-right { display: flex; align-items: center; gap: 20px; }
        .topbar-user { font-size: 14px; font-weight: 600; color: var(--text-muted); }
        .topbar-user span { color: var(--text-main); font-weight: 800; }
        
        .btn-logout { background-color: white; border: 2px solid #fee2e2; color: var(--danger); padding: 8px 18px; border-radius: 8px; font-family: 'Outfit', sans-serif; font-size: 13px; font-weight: 700; cursor: pointer; transition: all 0.2s; display: flex; align-items: center; gap: 6px;}
        .btn-logout:hover { background-color: var(--danger); color: white; border-color: var(--danger); }
        
        .profile-dropdown-wrapper { position: relative; cursor: pointer; display: flex; align-items: center;}
        .profile-avatar { width: 42px; height: 42px; border-radius: 50%; object-fit: cover; border: 2px solid var(--border); transition: 0.2s; }
        .profile-avatar:hover { border-color: var(--primary); }

        .dropdown-menu { display: none; position: absolute; right: 0; top: 100%; margin-top: 15px; background: white; min-width: 220px; border-radius: 16px; box-shadow: 0 10px 40px rgba(0,0,0,0.1); border: 1px solid var(--border); padding: 10px; z-index: 9999; }
        .dropdown-menu::before { content: ''; position: absolute; top: -6px; right: 15px; width: 12px; height: 12px; background: white; transform: rotate(45deg); border-left: 1px solid var(--border); border-top: 1px solid var(--border); }
        .dropdown-menu a { display: flex; align-items: center; gap: 10px; padding: 12px 15px; color: var(--text-muted); text-decoration: none; font-size: 14px; font-weight: 600; transition: 0.2s; border-radius: 10px; }
        .dropdown-menu a:hover { background-color: #eff6ff; color: var(--primary); }

        /* --- CONTAINER & KONTEN --- */
        .container { max-width: 800px; margin: 40px auto; padding: 0 20px; }
        .btn-back { display: inline-flex; align-items: center; gap: 8px; margin-bottom: 20px; color: var(--text-muted); text-decoration: none; font-weight: 600; font-size: 14px; transition: 0.2s; }
        .btn-back:hover { color: var(--primary); }
        
        .hero-section { text-align: center; margin-bottom: 40px; }
        .hero-section h1 { font-size: 32px; font-weight: 900; margin-bottom: 10px; }
        
        /* Grid Navigasi */
        .grid-help { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px; margin-bottom: 40px; }
        .nav-card { background: white; border: 1px solid var(--border); border-radius: 20px; padding: 25px; display: flex; align-items: flex-start; gap: 20px; text-decoration: none; color: var(--text-main); transition: 0.3s; }
        .nav-card:hover { border-color: var(--primary); transform: translateY(-5px); box-shadow: 0 10px 20px rgba(26,86,219,0.08); }
        
        .icon-box { width: 50px; height: 50px; background: #eff6ff; color: var(--primary); border-radius: 14px; display: flex; align-items: center; justify-content: center; font-size: 20px; flex-shrink: 0; }
        .nav-text h3 { font-size: 17px; margin-bottom: 4px; }
        .nav-text p { font-size: 13px; color: var(--text-muted); }
        
        /* Tip Card */
        .tip-card { background: #fffbeb; border: 1px solid #fde68a; border-radius: 16px; padding: 20px; display: flex; gap: 15px; color: #92400e; }
    </style>
</head>
<body>

    <nav class="topbar">
        <div class="topbar-left">
            <a href="{{ url('/mitra/dashboard') }}" class="topbar-brand">
                <img src="{{ asset('images/sikosub.png') }}" alt="Logo" class="topbar-logo">
                <span class="topbar-name">SIKOSUB</span>
                <span class="topbar-badge">MITRA</span>
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
        <a href="{{ route('mitra.dashboard') }}" class="btn-back"><i class="fa-solid fa-arrow-left"></i> Kembali ke Dashboard</a>

        <div class="hero-section">
            <h1>Pusat Bantuan & Info</h1>
            <p style="color: var(--text-muted);">Bagaimana kami bisa membantu Anda hari ini?</p>
        </div>

        <div class="grid-help">
            @php
                // PERBAIKAN: Menggunakan $namaMitra untuk mencegah Error Not Found pada property user
                $message = "Halo Admin SIKOSUB, saya " . $namaMitra . ". Saya ingin meminta bantuan terkait kendala pada dasbor mitra saya.";
                $waLink = "https://wa.me/6281234567890?text=" . urlencode($message);
            @endphp
            <a href="{{ $waLink }}" target="_blank" class="nav-card">
                <div class="icon-box"><i class="fa-brands fa-whatsapp"></i></div>
                <div class="nav-text">
                    <h3>Hubungi Admin SIKOSUB</h3>
                    <p>Bantuan teknis via WhatsApp. Admin siap membantu kendala Anda.</p>
                </div>
            </a>

            <a href="{{ route('mitra.bantuan.panduan') }}" class="nav-card">
                <div class="icon-box" style="background:#fef3c7; color:#d97706;"><i class="fa-solid fa-book-open"></i></div>
                <div class="nav-text">
                    <h3>Panduan Mitra</h3>
                    <p>Pelajari cara mengelola properti, foto, dan harga secara optimal.</p>
                </div>
            </a>
        </div>

        <div class="tip-card">
            <i class="fa-solid fa-lightbulb" style="font-size: 24px;"></i>
            <div>
                <strong>Tips Optimalisasi:</strong>
                <p style="font-size: 13px; margin-top: 5px;">Pastikan foto utama properti memiliki pencahayaan yang terang. Properti dengan foto berkualitas tinggi cenderung mendapatkan 3x lipat lebih banyak klik dari pencari kost.</p>
            </div>
        </div>
    </div>

    <script>
        // Fungsi untuk Profile Dropdown
        function toggleDropdown(event) {
            event.stopPropagation();
            const menu = document.getElementById('profileDropdown');
            if (menu.style.display === 'block') {
                menu.style.display = 'none';
            } else {
                menu.style.display = 'block';
            }
        }

        // Menutup menu dropdown jika klik di area kosong
        window.onclick = function(event) {
            const menu = document.getElementById('profileDropdown');
            if (menu && menu.style.display === 'block') {
                menu.style.display = 'none';
            }
        }
    </script>
</body>
</html>