<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
    /* Styling Navbar Warna Putih */
    .navbar { 
        display: flex; 
        align-items: center; 
        justify-content: space-between; 
        padding: 15px 5%; 
        background: #ffffff; 
        color: #333; 
        border-bottom: 1px solid #e5e7eb; 
        flex-wrap: wrap; 
        gap: 15px;
    }
    
    .logo-container { 
        display: flex; 
        align-items: center; 
        gap: 12px; 
        cursor: pointer; 
        text-decoration: none; 
        color: #000; 
        transition: 0.3s; 
    }
    .logo-container:hover { opacity: 0.8; }
    .logo-img { width: 45px; height: 45px; object-fit: cover; border-radius: 50%; }
    .logo-text-wrapper { display: flex; flex-direction: column; }
    .logo-text { font-size: 20px; font-weight: 800; display: block; }
    .logo-subtext { font-size: 10px; color: #666; }
    
    .nav-actions { display: flex; align-items: center; gap: 15px; }
    
    /* Search Bar disesuaikan */
    .search-nav { position: relative; display: flex; align-items: center; background: #f3f4f6; border: 1px solid #ddd; border-radius: 20px; padding: 0 10px; transition: 0.3s; }
    .search-nav:focus-within { background: #fff; border-color: #1a56db; }
    .search-nav input { border: none; background: transparent; padding: 8px 10px; color: #333; outline: none; width: 140px; }
    
    .btn-login { padding: 8px 18px; border-radius: 20px; font-weight: 700; cursor: pointer; border: none; font-size: 11px; transition: 0.3s; background: #1a56db; color: white; }
    .btn-login:hover { transform: scale(1.05); }

    .profile-dropdown-wrapper { position: relative; cursor: pointer; display: flex; align-items: center; }
    
    .dropdown-menu {
        display: none; position: absolute; right: 0; top: 100%; margin-top: 15px;
        background: white; min-width: 160px; border-radius: 10px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.1); padding: 8px 0; z-index: 9999;
    }
    .dropdown-menu a { display: block; padding: 10px 20px; color: #374151; text-decoration: none; font-size: 13px; transition: 0.2s; }
    .dropdown-menu a:hover { background-color: #f3f4f6; color: #1a56db; }

    @media (max-width: 768px) {
        .navbar { justify-content: center; }
        .search-nav input { width: 100px; }
    }
</style>

<nav class="navbar">
    <a href="{{ route('home') }}" class="logo-container">
        <img src="{{ asset('images/sikosub.png') }}" class="logo-img" alt="Logo SIKOSUB">
        <div class="logo-text-wrapper">
            <span class="logo-text">SIKOSUB</span>
            <span class="logo-subtext">Sistem Informasi Kost Subang</span>
        </div>
    </a>

    <div class="nav-actions">
        <form action="{{ route('kost.search') }}" method="GET" class="search-nav">
            <input type="text" name="keyword" placeholder="Cari Kost...">
            <button type="submit" style="background:none; border:none; color:#333; cursor:pointer;"><i class="fa fa-search"></i></button>
        </form>

        @guest
            <a href="{{ route('login') }}" style="text-decoration: none;">
                <button class="btn-login">LOGIN</button>
            </a>
        @else
            <span style="color: #333; font-weight: 600; margin-right: 5px; font-family: 'Outfit', sans-serif; font-size: 12px;">
                Halo, {{ Auth::user()->name }}
            </span>
            
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:inline;">
                @csrf
                <button type="button" class="btn-login" 
                        onclick="confirmLogout()"
                        style="background-color: #ef4444; color: white;">
                    LOGOUT
                </button>
            </form>
            
            <div class="profile-dropdown-wrapper" onclick="toggleDropdown(event)">
                @php
                    // Logika memanggil foto profil persis seperti di halaman edit profil
                    $navAvatar = Auth::user()->foto_profil ? asset('storage/avatars/' . Auth::user()->foto_profil) : 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name).'&background=3b82f6&color=fff';
                @endphp
                <img src="{{ $navAvatar }}" alt="Profile" style="width: 35px; height: 35px; border-radius: 50%; object-fit: cover; border: 2px solid #1a56db;">

                <div class="dropdown-menu" id="profileDropdown">
                    <a href="{{ route('profile.edit') }}"><i class="fa fa-edit" style="margin-right: 8px;"></i> Edit Profil</a>
                    <a href="{{ route('profile.bookmarks') }}"><i class="fa fa-bookmark" style="margin-right: 8px;"></i> Bookmark Saya</a>
                </div>
            </div>
        @endguest
    </div>
</nav>

<script>
    function toggleDropdown(event) {
        event.stopPropagation(); 
        const menu = document.getElementById('profileDropdown');
        menu.style.display = (menu.style.display === 'block') ? 'none' : 'block';
    }

    window.onclick = function(event) {
        const menu = document.getElementById('profileDropdown');
        if (menu && menu.style.display === 'block') {
            menu.style.display = 'none';
        }
    }

    function confirmLogout() {
        if (confirm('Apakah Anda yakin ingin keluar dari akun?')) {
            document.getElementById('logout-form').submit();
        }
    }
</script>