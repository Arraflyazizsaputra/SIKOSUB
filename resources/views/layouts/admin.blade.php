<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIKOSUB Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        :root { --primary-blue: #0066FF; --bg-gray: #F4F7FE; --danger-red: #E74C3C; }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Outfit', sans-serif; background: var(--bg-gray); display: flex; min-height: 100vh; }
        
        .sidebar { width: 280px; background: var(--primary-blue); color: white; display: flex; flex-direction: column; padding: 25px; position: fixed; height: 100vh; z-index: 1000; overflow-y: auto; }
        
        .sidebar::-webkit-scrollbar { width: 6px; }
        .sidebar::-webkit-scrollbar-track { background: transparent; }
        .sidebar::-webkit-scrollbar-thumb { background: rgba(255, 255, 255, 0.3); border-radius: 10px; }
        .sidebar::-webkit-scrollbar-thumb:hover { background: rgba(255, 255, 255, 0.5); }
        
        .logo-container { display: flex; align-items: center; gap: 10px; margin-bottom: 40px; flex-shrink: 0; }
        .logo-img { width: 40px; height: 40px; object-fit: contain; }
        .logo { font-size: 24px; font-weight: 800; }
        
        .menu-label { font-size: 10px; letter-spacing: 1px; color: rgba(255,255,255,0.5); font-weight: 700; margin: 20px 0 10px 10px; flex-shrink: 0; }
        
        .nav-menu { flex: 1; display: flex; flex-direction: column; gap: 5px; }
        .nav-item { 
            padding: 12px 15px; 
            border-radius: 8px; 
            font-size: 13px; 
            font-weight: 600; 
            cursor: pointer; 
            transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
            opacity: 0.8;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .nav-item:hover { background: rgba(255,255,255,0.15); opacity: 1; padding-left: 20px; }
        .nav-link.active .nav-item { background: white; color: var(--primary-blue); opacity: 1; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
        
        .logout-container { margin-top: auto; padding-top: 20px; flex-shrink: 0; }
        .btn-logout { background: var(--danger-red); color: white; padding: 12px; width: 100%; border-radius: 8px; font-weight: bold; cursor: pointer; border: none; transition: all 0.3s ease; display: flex; align-items: center; justify-content: center; gap: 8px; }
        .btn-logout:hover { background: #C0392B; transform: translateY(-2px); }
        
        .main-content { flex: 1; margin-left: 280px; width: calc(100% - 280px); transition: 0.3s; }
        .top-header { background: white; padding: 20px 40px; display: flex; justify-content: flex-end; align-items: center; border-bottom: 1px solid #eee; }
        .page-content { padding: 40px; }

        @media (max-width: 992px) {
            .sidebar { width: 70px; padding: 20px 10px; }
            .logo, .nav-item span, .menu-label { display: none; }
            .logo-img { width: 35px; margin: 0 auto; }
            .main-content { margin-left: 70px; width: calc(100% - 70px); }
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="logo-container">
            <img src="{{ asset('images/sikosub.png') }}" alt="Logo" class="logo-img">
            <h1 class="logo">SIKOSUB</h1>
        </div>
        
        <div class="nav-menu">
            <div class="menu-label">ADMIN PANEL</div>
            <a href="{{ route('superadmin.dashboard') }}" class="nav-link {{ request()->routeIs('superadmin.dashboard') ? 'active' : '' }}" style="color:white; text-decoration:none;">
                <div class="nav-item"><i class="fa-solid fa-chart-line"></i><span>DASHBOARD</span></div>
            </a>
            <a href="{{ route('landing.manage') }}" class="nav-link {{ request()->routeIs('landing.manage') ? 'active' : '' }}" style="color:white; text-decoration:none;">
                <div class="nav-item"><i class="fa-solid fa-display"></i><span>KELOLA LANDING</span></div>
            </a>
            <a href="{{ route('tambah.kost') }}" class="nav-link {{ request()->routeIs('tambah.kost') ? 'active' : '' }}" style="color:white; text-decoration:none;">
                <div class="nav-item"><i class="fa-solid fa-house-medical"></i><span>TAMBAH KOST</span></div>
            </a>
            <a href="{{ route('daftar.kost') }}" class="nav-link {{ request()->routeIs('daftar.kost') ? 'active' : '' }}" style="color:white; text-decoration:none;">
                <div class="nav-item"><i class="fa-solid fa-list-ul"></i><span>DAFTAR KOST</span></div>
            </a>
            <a href="{{ route('kost.manage-index') }}" class="nav-link {{ request()->routeIs('kost.manage-index') ? 'active' : '' }}" style="color:white; text-decoration:none;">
                <div class="nav-item"><i class="fa-solid fa-gear"></i><span>DETAIL KOST</span></div>
            </a>
            <a href="{{ route('review.manage') }}" class="nav-link {{ request()->routeIs('review.manage') ? 'active' : '' }}" style="color:white; text-decoration:none;">
                <div class="nav-item"><i class="fa-solid fa-star"></i><span>REVIEW KOST</span></div>
            </a>
            
            <div class="menu-label">AKSES HALAMAN</div>
            
            <a href="{{ route('superadmin.ke_publik') }}" target="_blank" class="nav-link" style="color:white; text-decoration:none;">
                <div class="nav-item"><i class="fa-solid fa-globe"></i><span>HALAMAN PENGUNJUNG</span></div>
            </a>

            <a href="{{ route('jembatan.mitra') }}" target="_blank" class="nav-link" style="color:white; text-decoration:none;">
                <div class="nav-item"><i class="fa-solid fa-users"></i><span>DASHBOARD MITRA</span></div>
            </a>
        </div>
        
        <div class="logout-container">
            <form action="{{ route('logout') }}" method="POST">
                @csrf 
                <button type="submit" class="btn-logout" onclick="return confirm('Apakah Anda yakin ingin keluar dari panel admin?')">
                    <i class="fa-solid fa-right-from-bracket"></i> LOGOUT
                </button>
            </form>
        </div>
    </div>

    <div class="main-content">
        <div class="top-header">
            <div class="notif-icon"><i class="fa-solid fa-bell"></i></div>
        </div>
        <div class="page-content">
            @yield('content')
        </div>
    </div>
</body>
</html>