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
    <title>Panduan Mitra - SIKOSUB</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root { 
            --primary: #1a56db; 
            --primary-dark: #1e40af;
            --primary-light: #eff6ff;
            --bg-body: #f8fafc; 
            --text-main: #0f172a; 
            --text-muted: #64748b; 
            --border: #e2e8f0; 
            --danger: #ef4444;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Outfit', sans-serif; background: var(--bg-body); color: var(--text-main); line-height: 1.6; }
        
        /* --- NAVBAR (Terintegrasi) --- */
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
        .container { max-width: 850px; margin: 40px auto; padding: 0 20px; }
        .btn-back { display: inline-flex; align-items: center; gap: 8px; color: var(--text-muted); text-decoration: none; font-weight: 600; margin-bottom: 20px; transition: 0.3s; font-size: 14px;}
        .btn-back:hover { color: var(--primary); }

        /* --- HERO CARD --- */
        .hero-card {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            border-radius: 24px;
            padding: 40px 30px;
            text-align: center;
            margin-bottom: -30px; 
            box-shadow: 0 15px 35px rgba(26, 86, 219, 0.15);
            position: relative;
            overflow: hidden;
            z-index: 1;
        }
        .hero-card::after { content: '\f02d'; font-family: 'Font Awesome 6 Free'; font-weight: 900; position: absolute; right: -20px; bottom: -40px; font-size: 150px; opacity: 0.1; transform: rotate(-15deg); pointer-events: none; color: white;}
        .hero-card h1 { color: white; font-size: 32px; font-weight: 900; margin-bottom: 8px; position: relative; z-index: 2;}
        .hero-card p { color: #e0e7ff; font-size: 15px; font-weight: 400; max-width: 500px; margin: 0 auto; line-height: 1.5; position: relative; z-index: 2;}

        /* TOMBOL BUKA PDF */
        .btn-pdf {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: white;
            color: var(--primary);
            padding: 14px 28px;
            border-radius: 50px;
            font-weight: 800;
            font-size: 14px;
            text-decoration: none;
            margin-top: 25px;
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
            transition: 0.3s;
            position: relative;
            z-index: 2;
        }
        .btn-pdf:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 25px rgba(0,0,0,0.15);
            color: var(--primary-dark);
        }

        /* --- SEARCH BOX --- */
        .search-wrapper { position: relative; z-index: 2; max-width: 600px; margin: 0 auto 50px auto; }
        .search-box { 
            width: 100%; 
            padding: 20px 25px 20px 55px; 
            border-radius: 50px; 
            border: 2px solid white; 
            font-family: 'Outfit'; 
            font-size: 15px; 
            transition: 0.3s; 
            background: white; 
            box-shadow: 0 10px 30px rgba(0,0,0,0.08); 
            color: var(--text-main);
        }
        .search-box:focus { outline: none; border-color: var(--primary); box-shadow: 0 10px 30px rgba(26, 86, 219, 0.15); }
        .search-icon { position: absolute; left: 25px; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 18px; }
        
        /* --- ADVANCED ACCORDION --- */
        .faq-item { 
            background: white; 
            border: 1px solid var(--border); 
            border-radius: 16px; 
            margin-bottom: 15px; 
            overflow: hidden; 
            transition: all 0.3s ease; 
        }
        .faq-item:hover { border-color: #cbd5e1; box-shadow: 0 5px 15px rgba(0,0,0,0.03); }
        .faq-item.active { border-color: var(--primary); box-shadow: 0 10px 20px rgba(26, 86, 219, 0.08); }
        
        .faq-q { 
            padding: 22px 25px; 
            cursor: pointer; 
            font-weight: 700; 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            transition: 0.2s;
            font-size: 16px;
        }
        .faq-item.active .faq-q { background: var(--primary-light); color: var(--primary-dark); }
        
        .badge { font-size: 11px; background: #e0e7ff; color: var(--primary); padding: 5px 12px; border-radius: 50px; margin-right: 15px; font-weight: 800; letter-spacing: 0.5px; }
        
        .faq-a { 
            padding: 0 25px; 
            max-height: 0; 
            overflow: hidden; 
            transition: all 0.4s ease-in-out; 
            color: var(--text-muted); 
            font-size: 15px; 
            line-height: 1.7;
            opacity: 0;
        }
        .faq-item.active .faq-a { padding: 0 25px 25px 25px; max-height: 500px; opacity: 1; margin-top: -5px; }
        
        .faq-q i { transition: 0.4s; color: #cbd5e1; font-size: 18px;}
        .faq-item.active .faq-q i { transform: rotate(180deg); color: var(--primary); }
        
        #noResults { display: none; text-align: center; color: var(--text-muted); padding: 60px 20px; background: white; border-radius: 20px; border: 2px dashed var(--border); }
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
        <a href="{{ route('mitra.bantuan.index') }}" class="btn-back"><i class="fa-solid fa-arrow-left"></i> Kembali ke Pusat Bantuan</a>
        
        <div class="hero-card">
            <h1>Panduan Mitra SIKOSUB</h1>
            <p>Temukan solusi, dokumentasi lengkap, dan tips terbaik untuk mengelola manajemen properti kost Anda.</p>
            
            <!-- TOMBOL BUKA PDF BARU -->
            <a href="{{ asset('docs/Panduan_Mitra_SIKOSUB.pdf') }}" target="_blank" class="btn-pdf">
                <i class="fa-solid fa-file-pdf" style="color: #ef4444; font-size: 16px;"></i> Buka / Unduh Panduan Lengkap (PDF)
            </a>
        </div>

        <div class="search-wrapper">
            <i class="fa-solid fa-magnifying-glass search-icon"></i>
            <input type="text" id="faqSearch" class="search-box" placeholder="Cari topik (misal: 'harga', 'foto', 'ketersediaan')..." onkeyup="filterFaq()">
        </div>

        <div id="faqContainer">
            <div class="faq-item" data-category="properti">
                <div class="faq-q" onclick="toggleFaq(this)">
                    <div><span class="badge">PROPERTI</span> Bagaimana cara menambah kost baru?</div>
                    <i class="fa-solid fa-chevron-down"></i>
                </div>
                <div class="faq-a">
                    Buka menu <b>Properti Saya</b> di dashboard, lalu klik tombol <b>"Tambah Properti Baru"</b>. Pastikan Anda mengisi deskripsi detail (luas kamar, fasilitas) dan mengunggah foto berkualitas tinggi. Kami merekomendasikan ukuran foto minimal 1200x800px untuk hasil terbaik.
                </div>
            </div>

            <div class="faq-item" data-category="properti">
                <div class="faq-q" onclick="toggleFaq(this)">
                    <div><span class="badge">PROPERTI</span> Mengapa properti tidak muncul di pencarian?</div>
                    <i class="fa-solid fa-chevron-down"></i>
                </div>
                <div class="faq-a">
                    Kemungkinan status properti Anda diatur ke <b>"Penuh"</b>. Sistem SIKOSUB secara otomatis menyembunyikan properti yang kamarnya penuh dari hasil pencarian umum agar pengunjung tidak kecewa. Cek kembali di menu "Daftar Properti Saya" dan pastikan status ketersediaan diubah menjadi "Tersedia" jika sudah ada kamar yang kosong.
                </div>
            </div>

            <div class="faq-item" data-category="foto">
                <div class="faq-q" onclick="toggleFaq(this)">
                    <div><span class="badge">FOTO</span> Tips unggah galeri foto yang menarik?</div>
                    <i class="fa-solid fa-chevron-down"></i>
                </div>
                <div class="faq-a">
                    Calon penyewa lebih suka melihat visual yang detail. Selain foto utama kamar, pastikan Anda juga mengunggah foto: 
                    <br><br>
                    1. Kondisi Kamar Mandi (sangat penting).<br>
                    2. Area dapur atau ruang bersama.<br>
                    3. Area parkir kendaraan.<br>
                    4. Foto tampak depan kost (akses masuk).<br><br>
                    Properti dengan galeri foto yang lengkap terbukti meningkatkan potensi disewa sebesar 60%.
                </div>
            </div>

            <div class="faq-item" data-category="harga">
                <div class="faq-q" onclick="toggleFaq(this)">
                    <div><span class="badge">HARGA</span> Cara memaksimalkan fitur diskon?</div>
                    <i class="fa-solid fa-chevron-down"></i>
                </div>
                <div class="faq-a">
                    Gunakan fitur <b>Harga Diskon</b> saat masa sepi penyewa atau untuk promosi pembukaan unit baru. Harga diskon yang ditampilkan di sistem akan otomatis mencoret harga asli Anda, hal ini secara psikologis sangat ampuh menarik perhatian pengguna dibandingkan memajang harga normal.
                </div>
            </div>

            <div class="faq-item" data-category="akun">
                <div class="faq-q" onclick="toggleFaq(this)">
                    <div><span class="badge">AKUN</span> Cara menjaga keamanan akun mitra?</div>
                    <i class="fa-solid fa-chevron-down"></i>
                </div>
                <div class="faq-a">
                    Jangan pernah memberikan password Anda kepada siapa pun, termasuk admin SIKOSUB. Jika Anda merasa akun Anda sedang diakses oleh pihak yang tidak bertanggung jawab, segera ubah sandi Anda melalui menu <b>Pengaturan Akun > Keamanan Sandi</b> di Dasbor Anda.
                </div>
            </div>
        </div>

        <div id="noResults">
            <i class="fa-solid fa-box-open" style="font-size: 50px; margin-bottom: 15px; color: #cbd5e1;"></i>
            <h3>Topik Tidak Ditemukan</h3>
            <p>Maaf, kami tidak menemukan panduan yang cocok dengan kata kunci pencarian Anda.</p>
        </div>
    </div>

    <script>
        function toggleFaq(element) {
            const parent = element.parentElement;
            // Menutup item lain saat membuka item baru
            document.querySelectorAll('.faq-item').forEach(item => {
                if (item !== parent) item.classList.remove('active');
            });
            parent.classList.toggle('active');
        }

        function filterFaq() {
            const input = document.getElementById('faqSearch').value.toLowerCase();
            const items = document.querySelectorAll('.faq-item');
            const noResults = document.getElementById('noResults');
            let found = false;
            
            items.forEach(item => {
                const text = item.innerText.toLowerCase();
                if (text.includes(input)) {
                    item.style.display = "";
                    found = true;
                } else {
                    item.style.display = "none";
                }
            });
            noResults.style.display = found ? "none" : "block";
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

        // Menutup dropdown jika klik di area kosong
        window.onclick = function(event) {
            const menu = document.getElementById('profileDropdown');
            if (menu && menu.style.display === 'block') {
                menu.style.display = 'none';
            }
        }
    </script>
</body>
</html>