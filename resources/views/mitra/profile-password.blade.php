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
    <title>Ubah Password - SIKOSUB</title>
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
            --danger: #ef4444;
            --success: #10b981;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Outfit', sans-serif; background: var(--bg-body); min-height: 100vh; color: var(--text-main); }

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

        /* --- CONTAINER --- */
        .container { max-width: 500px; margin: 40px auto; padding: 0 24px; }
        .btn-back { display: inline-flex; align-items: center; gap: 8px; margin-bottom: 20px; color: var(--text-muted); text-decoration: none; font-weight: 600; font-size: 14px; }
        .btn-back:hover { color: var(--primary); }

        /* --- CARD & FORM --- */
        .card { background: white; border-radius: 20px; padding: 30px; box-shadow: 0 10px 25px rgba(0,0,0,0.05); border: 1px solid var(--border); }
        .form-group { margin-bottom: 20px; position: relative; }
        label { display: block; font-size: 14px; font-weight: 700; margin-bottom: 8px; color: var(--text-main); }
        
        .input-wrapper { position: relative; }
        input { width: 100%; padding: 14px 16px; border: 1px solid var(--border); border-radius: 12px; font-family: 'Outfit'; font-size: 14px; transition: 0.3s; box-sizing: border-box; }
        input:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(26, 86, 219, 0.1); outline: none; }
        
        .toggle-password { position: absolute; right: 15px; top: 50%; transform: translateY(-50%); cursor: pointer; color: var(--text-muted); }

        .btn-submit { background: var(--primary); color: white; width: 100%; padding: 14px; border: none; border-radius: 12px; font-weight: 700; cursor: pointer; transition: 0.3s; font-size: 15px; margin-top: 10px; box-shadow: 0 4px 15px rgba(26, 86, 219, 0.2); }
        .btn-submit:hover { background: var(--primary-dark); transform: translateY(-2px); }

        /* --- ALERTS --- */
        .alert { padding: 15px; border-radius: 12px; margin-bottom: 20px; font-weight: 600; font-size: 14px; }
        .alert-success { background: #ecfdf5; color: #065f46; border: 1px solid #a7f3d0; }
        .alert-danger { background: #fef2f2; color: #991b1b; border: 1px solid #fecaca; }
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
        
        <div class="card">
            <h2 style="margin-bottom: 25px;"><i class="fa-solid fa-lock" style="color: var(--primary);"></i> Keamanan Akun</h2>
            
            @if(session('success'))
                <div class="alert alert-success">
                    <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
                </div>
            @endif
            
            @if($errors->any())
                <div class="alert alert-danger">
                    <i class="fa-solid fa-circle-xmark"></i> {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('profile.password.update') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label>Password Saat Ini</label>
                    <div class="input-wrapper">
                        <input type="password" name="current_password" id="current_password" required>
                        <i class="fa-solid fa-eye toggle-password" onclick="togglePassword('current_password')"></i>
                    </div>
                </div>
                <div class="form-group">
                    <label>Password Baru</label>
                    <div class="input-wrapper">
                        <input type="password" name="new_password" id="new_password" required>
                        <i class="fa-solid fa-eye toggle-password" onclick="togglePassword('new_password')"></i>
                    </div>
                </div>
                <div class="form-group">
                    <label>Konfirmasi Password Baru</label>
                    <div class="input-wrapper">
                        <input type="password" name="new_password_confirmation" id="new_password_confirmation" required>
                        <i class="fa-solid fa-eye toggle-password" onclick="togglePassword('new_password_confirmation')"></i>
                    </div>
                </div>
                <button type="submit" class="btn-submit">Simpan Password Baru</button>
            </form>
        </div>
    </div>

    <script>
        // Fungsi untuk toggle tipe password
        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            const icon = input.nextElementSibling;
            if (input.type === "password") {
                input.type = "text";
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = "password";
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

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