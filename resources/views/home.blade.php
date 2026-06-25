@extends('layouts.app')

@if(session('success'))
    <script>
        alert("{{ session('success') }}");
    </script>
@endif

@section('content')
<style>
    /* Mengaktifkan smooth scrolling secara global */
    html {
        scroll-behavior: smooth;
    }

    /* === HERO SECTION === */
    .hero-wrapper {
        /* Gradient diubah ke arah vertikal (atas ke bawah) dan diberi titik akhir
           berwarna putih, supaya warna biru memudar mulus menyatu dengan
           background putih dari konten di bawahnya (tidak ada garis pemisah
           yang terlihat saat scroll). */
        background: linear-gradient(180deg, #92d3fd 0%, #bde4ff 55%, #ffffff 100%); 
        padding: 60px 20px 120px;
        position: relative;
        font-family: 'Outfit', sans-serif;
        overflow: hidden;
    }

    .welcome-box {
        position: absolute;
        top: 30px;
        left: 5%;
        text-align: left;
        z-index: 5;
    }

    .welcome-text {
        font-size: 1.1rem;
        color: #1e3a8a;
        font-weight: 800;
        margin: 0;
        letter-spacing: 1.5px;
        text-transform: uppercase;
    }

    .user-name {
        font-size: 1.4rem;
        color: #000;
        font-weight: 900;
        margin: 3px 0 0 0;
        text-transform: uppercase;
        letter-spacing: 1px;
        text-shadow: 1px 1px 0px rgba(255,255,255,0.5);
    }

    .main-title {
        text-align: center;
        font-size: 3.5rem;
        font-weight: 900;
        color: #000;
        letter-spacing: 4px;
        margin-top: 60px; 
        margin-bottom: 25px;
        text-shadow: 2px 2px 0px rgba(255, 255, 255, 0.8);
    }

    /* === SEARCH BOX === */
    .search-box {
        display: flex;
        max-width: 750px;
        margin: 0 auto 50px auto; 
        background: white;
        border-radius: 50px;
        padding: 8px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.08);
        align-items: center;
        position: relative; 
        z-index: 20; 
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .search-box:focus-within {
        transform: translateY(-2px);
        box-shadow: 0 12px 30px rgba(91, 66, 243, 0.15);
    }

    .search-box input {
        flex: 1.5;
        border: none;
        padding: 12px 25px;
        border-radius: 50px 0 0 50px;
        outline: none;
        font-size: 0.95rem;
        color: #333;
    }

    .category-select {
        flex: 1;
        border-left: 1px solid #e5e7eb;
        padding: 0 15px;
    }

    .category-select select {
        width: 100%;
        border: none;
        outline: none;
        background: transparent;
        font-size: 0.95rem;
        color: #4b5563;
        cursor: pointer;
        font-weight: 600;
    }

    .btn-search {
        background: #5b42f3; 
        color: white;
        border: none;
        padding: 14px 35px;
        border-radius: 50px;
        font-weight: 800;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(91, 66, 243, 0.3);
    }

    .btn-search:hover {
        background: #4a34ce;
        transform: scale(1.02);
        box-shadow: 0 6px 15px rgba(91, 66, 243, 0.4);
    }

    /* === CAROUSEL PROMO === */
    .hero-overlap-section {
        margin-top: 120px; 
        position: relative;
        z-index: 10;
        max-width: 1000px;
        margin-left: auto;
        margin-right: auto;
    }

    .image-carousel {
        overflow: hidden;
        border-radius: 20px;
        width: 100%;
        box-shadow: 0 15px 35px rgba(0,0,0,0.1);
    }

    .carousel-track {
        display: flex;
        align-items: center;
        width: max-content; 
        will-change: transform; 
    }

    .promo-item {
        width: 600px; 
        max-width: 80vw; 
        height: 280px;
        object-fit: cover;
        border-radius: 20px;
        margin: 0 15px;
        opacity: 0.5;
        transform: scale(0.9);
        transition: opacity 0.6s cubic-bezier(0.25, 1, 0.5, 1), transform 0.6s cubic-bezier(0.25, 1, 0.5, 1);
        cursor: pointer;
        flex-shrink: 0;
    }

    .promo-item.active {
        opacity: 1;
        transform: scale(1);
    }

    .carousel-controls {
        display: flex;
        justify-content: center;
        gap: 15px;
        margin-top: 25px;
    }

    .nav-circle {
        width: 40px;
        height: 40px;
        background: #000;
        color: white;
        border-radius: 50%;
        display: flex;
        justify-content: center;
        align-items: center;
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 0.9rem;
        box-shadow: 0 4px 10px rgba(0,0,0,0.15);
    }

    .nav-circle:hover {
        background: #5b42f3;
        transform: scale(1.1);
    }

    /* Tombol Mulai Cari */
    .scroll-down-container {
        text-align: center;
        margin-top: 35px;
    }
    
    .arrow-down-circle {
        width: 45px;
        height: 45px;
        background: white;
        border-radius: 50%;
        display: flex;
        justify-content: center;
        align-items: center;
        margin: 0 auto 10px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        color: #5b42f3;
        font-size: 1.2rem;
        transition: all 0.3s ease;
    }

    .scroll-down-container:hover .arrow-down-circle {
        transform: translateY(5px);
        background: #5b42f3;
        color: white;
    }

    .btn-mulai {
        background: none;
        border: none;
        color: #1a56db;
        font-weight: 800;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        cursor: pointer;
        transition: color 0.3s;
    }
    
    .scroll-down-container:hover .btn-mulai {
        color: #053baf;
    }

    /* === KATEGORI & KARTU KOST === */
    #kategori-konten {
        max-width: 1200px;
        margin: 60px auto;
        padding: 0 20px;
        scroll-margin-top: 100px;
    }

    .category-section { margin-bottom: 60px; }

    .section-header {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 30px;
        color: #000;
        border-bottom: 2px solid #f3f4f6;
        padding-bottom: 10px;
    }

    .section-header h2 { font-size: 1.4rem; font-weight: 800; margin: 0; }
    .icon-title { font-size: 1.6rem; }

    .grid-container {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 25px;
    }

    /* Kartu Instansi Pemerintah & Perusahaan */
    .property-card {
        height: 260px; 
        border-radius: 16px; 
        overflow: hidden; 
        position: relative; 
        background-size: cover; 
        background-position: center; 
        box-shadow: 0 6px 15px rgba(0,0,0,0.06); 
        transition: all 0.3s ease;
    }
    
    .property-card:hover { 
        transform: translateY(-8px); 
        box-shadow: 0 12px 25px rgba(0,0,0,0.15);
    }
    
    .property-card .overlay { 
        position: absolute; 
        inset: 0; 
        background: linear-gradient(to bottom, rgba(0,0,0,0.2) 0%, rgba(0,0,0,0.8) 100%); 
    }
    
    .card-content-wrapper { 
        position: relative; 
        z-index: 2; 
        height: 100%; 
        display: flex; 
        flex-direction: column; 
        justify-content: space-between; 
        padding: 20px; 
        box-sizing: border-box; 
    }
    
    .top-actions { 
        display: flex; 
        justify-content: space-between; 
        align-items: flex-start; 
        gap: 10px;
    }
    
    .badge-name { 
        color: white; 
        font-size: 1.15rem; 
        font-weight: 800; 
        text-transform: uppercase; 
        max-width: 85%; 
        text-shadow: 1px 2px 4px rgba(0,0,0,0.8);
    }
    
    .badge-name p { 
        margin: 4px 0 0 0; 
        font-size: 0.75rem; 
        font-weight: 600; 
        color: #e5e7eb; 
        letter-spacing: 0.5px;
    }
    
    .bottom-actions { 
        display: flex; 
        justify-content: flex-end; 
    }
    
    .btn-lihat-small { 
        background: white; 
        color: #000; 
        padding: 8px 24px; 
        border-radius: 25px; 
        font-size: 0.85rem; 
        font-weight: 700; 
        text-decoration: none; 
        transition: all 0.2s ease; 
        text-align: center;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }
    
    .btn-lihat-small:hover { 
        background: #3b82f6; 
        color: white; 
        transform: translateX(2px);
    }

    /* Tombol Tampilkan Lebih Banyak */
    .btn-show-more-outline {
        display: inline-flex; 
        align-items: center; 
        gap: 8px; 
        background: white; 
        border: 1px solid #d1d5db; 
        padding: 10px 28px; 
        border-radius: 25px; 
        font-weight: 700; 
        font-size: 0.9rem; 
        color: #374151; 
        cursor: pointer; 
        transition: all 0.3s ease; 
        text-decoration: none;
        box-shadow: 0 2px 5px rgba(0,0,0,0.02);
    }
    
    .btn-show-more-outline:hover { 
        background: #f9fafb; 
        border-color: #9ca3af;
        transform: translateY(-1px);
        box-shadow: 0 4px 10px rgba(0,0,0,0.05);
    }
    
    .btn-show-more-outline i { 
        color: #22c55e; 
        font-size: 1rem;
    }

    /* === INFO SECTION === */
    .info-section { 
        background: linear-gradient(180deg, #bae6fd 0%, #e0f2fe 100%); 
        padding: 60px 20px; 
        margin-top: 80px; 
        text-align: center; 
    }
    
    .info-container { max-width: 1000px; margin: 0 auto; }
    .info-title { font-weight: 900; font-size: 1.4rem; margin-bottom: 20px; color: #000; letter-spacing: 0.5px; }
    .info-desc { font-size: 0.95rem; color: #1f2937; line-height: 1.7; margin-bottom: 45px; text-align: justify; }
    .visi-misi-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 30px; }
    
    .vm-card { 
        background: white; 
        padding: 35px 30px; 
        border-radius: 24px; 
        text-align: center; 
        box-shadow: 0 10px 25px rgba(0,0,0,0.04); 
        transition: transform 0.3s ease;
    }
    
    .vm-card:hover {
        transform: translateY(-5px);
    }
    
    .vm-icon { margin-bottom: 20px; }
    .vm-icon img { width: 60px; height: 60px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); border-radius: 50%; }
    .vm-card h4 { font-weight: 800; font-size: 1.2rem; margin-bottom: 15px; color: #000; letter-spacing: 0.5px; }
    
    /* PERBAIKAN CSS: Menambahkan elemen div agar font dan format Misi tersinkron dengan Visi */
    .vm-card p, .vm-card ol, .vm-card div { font-size: 0.9rem; color: #4b5563; text-align: left; line-height: 1.7; margin: 0;}
    
    .vm-card ol { padding-left: 20px; }
    .vm-card ol li { margin-bottom: 8px; }

    /* === RESPONSIVE STYLES === */
    @media (max-width: 768px) {
        .hero-wrapper { padding: 40px 15px 100px; }
        .welcome-box { position: static; text-align: center; margin-bottom: 25px; padding-top: 10px; }
        .main-title { font-size: 2.5rem; margin-top: 10px; }
        
        .search-box { flex-direction: column; border-radius: 24px; padding: 12px; gap: 8px; }
        .search-box input { border-radius: 16px; width: 100%; box-sizing: border-box; padding: 14px 20px; background: #f9fafb; }
        .category-select { border-left: none; width: 100%; padding: 12px 20px; background: #f9fafb; border-radius: 16px; box-sizing: border-box; }
        .btn-search { border-radius: 16px; width: 100%; padding: 14px; }
        
        .hero-overlap-section { margin-top: 40px; }
        .promo-item { height: 180px; margin: 0 8px; }
        
        .section-header h2 { font-size: 1.15rem; }
        .grid-container { gap: 15px; }
        .property-card { height: 230px; }
        
        .visi-misi-grid { grid-template-columns: 1fr; gap: 20px; }
        .vm-card { padding: 25px 20px; }
    }
</style>

<div class="hero-wrapper">
    <div class="welcome-box">
        <h2 class="welcome-text">Selamat Datang</h2>
        <h3 class="user-name">
            @auth
                {{ Auth::user()->name }}
            @else
                PENGUNJUNG
            @endauth
        </h3>
    </div>

    <h1 class="main-title">SIKOSUB</h1>

    <form action="{{ route('kost.search') }}" method="GET" class="search-box">
        <input type="text" name="keyword" placeholder="Cari Nama Kos Atau Lokasi.......">
        <div class="category-select">
            <select name="kategori">
                <option value="">Kategori/Wilayah</option>
                <option value="semua">Semua Kategori</option>
                <option value="putra">Putra</option>
                <option value="putri">Putri</option>
                <option value="campur">Campur</option>
                <option value="unsub">Unsub</option>
                <option value="pemda">Pemda</option>
            </select>
        </div>
        <button type="submit" class="btn-search">Cari</button>
    </form>

    <div class="hero-overlap-section">
        <div class="image-carousel">
            <div class="carousel-track" id="promo-track">
                @php
                    $banners = isset($landing->banner_image) ? json_decode($landing->banner_image, true) : [];
                @endphp

                @if(!empty($banners))
                    @foreach($banners as $index => $banner)
                        <img src="{{ asset('images/banners/' . $banner) }}" alt="Banner Promosi {{ $index }}" class="promo-item {{ $loop->first ? 'active' : '' }}" data-real-index="{{ $index }}">
                    @endforeach
                @else
                    <img src="https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?auto=format&fit=crop&w=800&q=80" alt="Default 1" class="promo-item active" data-real-index="0">
                    <img src="https://images.unsplash.com/photo-1555854877-bab0e564b8d5?auto=format&fit=crop&w=800&q=80" alt="Default 2" class="promo-item" data-real-index="1">
                    <img src="https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?auto=format&fit=crop&w=800&q=80" alt="Default 3" class="promo-item" data-real-index="2">
                @endif
            </div>
        </div>

        <div class="carousel-controls">
            <div class="nav-circle" id="prevBtn"><i class="fa fa-chevron-left"></i></div>
            <div class="nav-circle" id="nextBtn"><i class="fa fa-chevron-right"></i></div>
        </div>

        <div class="scroll-down-container">
            <a href="#kategori-konten" style="text-decoration: none;">
                <div class="arrow-down-circle">
                    <i class="fa fa-chevron-down"></i>
                </div>
                <button class="btn-mulai">MULAI CARI</button>
            </a>
        </div>
    </div>
</div>

<main id="kategori-konten">
    
    {{-- ==================== KATEGORI 1: INSTANSI PEMERINTAHAN ==================== --}}
    <section class="category-section">
        <div class="section-header">
            <i class="fa-solid fa-building-columns icon-title" style="color: #f59e0b; margin-right: 5px;"></i>
            <h2>Dekat Instansi Pemerintahan <i class="fa-solid fa-caret-down text-gray" style="font-size:1rem; margin-left: 4px;"></i></h2>
        </div>
        
        <div class="grid-container">
            @forelse(array_slice($filterPemerintah, 0, 6) as $item)
                <div class="property-card" style="background-image: url('{{ asset($item['gambar']) }}');">
                    <div class="overlay"></div>
                    <div class="card-content-wrapper">
                        <div class="top-actions">
                            <div class="badge-name">
                                {{ $item['nama'] }}
                                <p>SUBANG</p>
                            </div>
                        </div>
                        <div class="bottom-actions">
                            {{-- MENGIRIM NAMA INSTANSI SEBAGAI KEYWORD PENCARIAN --}}
                            <a href="{{ route('kost.search', ['keyword' => $item['nama']]) }}" class="btn-lihat-small">Lihat Kost Terdekat</a>
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-muted" style="grid-column: 1/-1;">Belum ada instansi yang ditambahkan oleh admin.</p>
            @endforelse
        </div>
        
        <div class="text-center mt-4" style="text-align: center;">
            <a href="{{ route('instansi.daftar', ['kategori' => 'instansi-pemerintah']) }}" class="btn-show-more-outline">
                <i class="fa-solid fa-circle-plus"></i> Lihat lebih banyak
            </a>
        </div>
    </section>

    {{-- ==================== KATEGORI 2: INSTANSI PENDIDIKAN ==================== --}}
    <section class="category-section">
        <div class="section-header">
            <i class="fa-solid fa-graduation-cap icon-title" style="color: #3b82f6; margin-right: 5px;"></i>
            <h2>Dekat Instansi Pendidikan <i class="fa-solid fa-caret-down text-gray" style="font-size:1rem; margin-left: 4px;"></i></h2>
        </div>
        
        <div class="grid-container">
            @forelse(array_slice($filterPendidikan, 0, 6) as $item)
                <div class="property-card" style="background-image: url('{{ asset($item['gambar']) }}');">
                    <div class="overlay"></div>
                    <div class="card-content-wrapper">
                        <div class="top-actions">
                            <div class="badge-name">
                                {{ $item['nama'] }}
                                <p>SUBANG</p>
                            </div>
                        </div>
                        <div class="bottom-actions">
                            {{-- MENGIRIM NAMA INSTANSI SEBAGAI KEYWORD PENCARIAN --}}
                            <a href="{{ route('kost.search', ['keyword' => $item['nama']]) }}" class="btn-lihat-small">Lihat Kost Terdekat</a>
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-muted" style="grid-column: 1/-1;">Belum ada instansi yang ditambahkan oleh admin.</p>
            @endforelse
        </div>
        
        <div class="text-center mt-4" style="text-align: center;">
            <a href="{{ route('instansi.daftar', ['kategori' => 'instansi-pendidikan']) }}" class="btn-show-more-outline">
                <i class="fa-solid fa-circle-plus"></i> Lihat lebih banyak
            </a>
        </div>
    </section>

    {{-- ==================== KATEGORI 3: INSTANSI PERUSAHAAN ==================== --}}
    <section class="category-section">
        <div class="section-header">
            <i class="fa-solid fa-city icon-title" style="color: #8b5cf6; margin-right: 5px;"></i>
            <h2>Dekat Instansi Perusahaan <i class="fa-solid fa-caret-down text-gray" style="font-size:1rem; margin-left: 4px;"></i></h2>
        </div>
        
        <div class="grid-container">
            @forelse(array_slice($filterPerusahaan, 0, 6) as $item)
                <div class="property-card" style="background-image: url('{{ asset($item['gambar']) }}');">
                    <div class="overlay"></div>
                    <div class="card-content-wrapper">
                        <div class="top-actions">
                            <div class="badge-name">
                                {{ $item['nama'] }}
                                <p>SUBANG</p>
                            </div>
                        </div>
                        <div class="bottom-actions">
                            {{-- MENGIRIM NAMA INSTANSI SEBAGAI KEYWORD PENCARIAN --}}
                            <a href="{{ route('kost.search', ['keyword' => $item['nama']]) }}" class="btn-lihat-small">Lihat Kost Terdekat</a>
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-muted" style="grid-column: 1/-1;">Belum ada instansi yang ditambahkan oleh admin.</p>
            @endforelse
        </div>
        
        <div class="text-center mt-4" style="text-align: center;">
            <a href="{{ route('instansi.daftar', ['kategori' => 'instansi-perusahaan']) }}" class="btn-show-more-outline">
                <i class="fa-solid fa-circle-plus"></i> Lihat lebih banyak
            </a>
        </div>
    </section>
</main>

<section class="info-section">
    <div class="info-container">
        <h3 class="info-title">SIKOSUB (SISTEM INFORMASI KOST DI SUBANG)</h3>
        <p class="info-desc">
            {!! isset($landing->tentang) && $landing->tentang != '' ? nl2br(e($landing->tentang)) : 'Kota Subang terus mengalami perkembangan yang pesat, baik sebagai pusat pendidikan dengan hadirnya berbagai institusi seperti Universitas Subang, maupun sebagai kawasan pertumbuhan industri. Hal ini menarik banyak pendatang baru setiap tahunnya, yang didominasi oleh mahasiswa dan pekerja profesional. Kebutuhan primer akan tempat tinggal sementara (kost) sangat tinggi, namun proses pencariannya masih sangat konvensional. Pencari kost seringkali harus berkeliling kota dari satu tempat ke tempat lain yang tidak efisien, memakan waktu, dan menyulitkan pencari dalam membandingkan spesifikasi, kesesuaian anggaran (budget), dan jarak ke lokasi aktivitas.' !!}
        </p>

        <h3 class="info-title mt-4">VISI DAN MISI PLATFORM</h3>
        <div class="visi-misi-grid">
            <div class="vm-card">
                <div class="vm-icon">
                    <img src="https://ui-avatars.com/api/?name=Visi&background=0D8ABC&color=fff&rounded=true" alt="Visi">
                </div>
                <h4>VISI</h4>
                <p>
                    {!! isset($landing->visi) && $landing->visi != '' ? nl2br(e($landing->visi)) : 'Menjadi platform direktori terpusat yang memfasilitasi pencarian dan promosi hunian sementara yang layak dan strategis di Kota Subang.' !!}
                </p>
            </div>
            <div class="vm-card">
                <div class="vm-icon">
                    <img src="https://ui-avatars.com/api/?name=Misi&background=0D8ABC&color=fff&rounded=true" alt="Misi">
                </div>
                <h4>MISI</h4>
                <div>
                    {{-- PERBAIKAN: Menambahkan fungsi nl2br(e()) untuk mengubah baris baru otomatis menjadi <br> --}}
                    {!! isset($landing->misi) && $landing->misi != '' ? nl2br(e($landing->misi)) : '<ol><li>Menyediakan fitur pencarian kos berdasarkan lokasi, harga, fasilitas, dan peruntukannya (putra/putri).</li><li>Menyediakan platform digital bagi pemilik kost untuk mempromosikan properti dan menjawab direct queries dari calon konsumen.</li><li>Memfasilitasi proses penyewaan yang secara digital dan menyediakan informasi properti yang transparan dan up-to-date.</li></ol>' !!}
                </div>
            </div>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const track = document.getElementById('promo-track');
    const btnPrev = document.getElementById('prevBtn');
    const btnNext = document.getElementById('nextBtn');

    // Ambil daftar gambar ASLI (sebelum ada clone apapun ditambahkan)
    const originalImages = Array.from(track.querySelectorAll('.promo-item'));
    const totalReal = originalImages.length;

    if (totalReal > 0) {
        const AUTOPLAY_DELAY = 3500;
        const TRANSITION_DURATION = 600; // harus sama dengan durasi transition CSS (0.6s)

        // ===================================================================
        // STRATEGI INFINITE LOOP: clone slide pertama & terakhir
        // ---------------------------------------------------------------
        // Sebelumnya, saat geser dari slide terakhir ke pertama, index
        // langsung "dipaksa" balik ke 0 sehingga track snap seketika tanpa
        // animasi (terasa seperti lompat, bukan bergulir mulus).
        //
        // Solusinya: tambahkan duplikat (clone) slide pertama di AKHIR
        // track, dan duplikat slide terakhir di AWAL track. Dengan begitu,
        // geseran "maju terus" selalu punya slide visual untuk dituju —
        // baik maju dari akhir maupun mundur dari awal. Setelah transisi ke
        // clone selesai, posisi dipindahkan diam-diam (tanpa transisi) ke
        // slide asli yang identik secara visual, sehingga mata tidak
        // menyadari adanya "reset" sama sekali.
        // ===================================================================

        if (totalReal > 1) {
            const firstClone = originalImages[0].cloneNode(true);
            const lastClone = originalImages[totalReal - 1].cloneNode(true);
            firstClone.classList.add('clone');
            lastClone.classList.add('clone');
            firstClone.classList.remove('active');
            lastClone.classList.remove('active');

            track.appendChild(firstClone);
            track.insertBefore(lastClone, track.firstChild);
        }

        // Daftar lengkap elemen slide setelah clone ditambahkan (urutan DOM final)
        const slides = Array.from(track.querySelectorAll('.promo-item'));

        // Index posisi di dalam array `slides` (bukan index data asli).
        // Jika ada clone, slide asli pertama berada di posisi 1 (karena
        // posisi 0 diisi clone dari slide terakhir).
        const offset = totalReal > 1 ? 1 : 0;
        let currentPos = offset; // mulai di slide asli pertama
        let isAnimating = false;
        let autoPlayInterval;

        function setActiveBySlide(slideEl) {
            slides.forEach((s) => s.classList.remove('active'));
            slideEl.classList.add('active');
        }

        function renderPosition(withTransition) {
            const activeSlide = slides[currentPos];
            setActiveBySlide(activeSlide);

            requestAnimationFrame(() => {
                const containerWidth = track.parentElement.clientWidth;

                // Pusat slide aktif dihitung langsung dari DOM (offsetLeft + setengah
                // lebar), sehingga selalu akurat di semua ukuran layar tanpa hardcode.
                const imageCenter = activeSlide.offsetLeft + activeSlide.offsetWidth / 2;
                const centerPoint = containerWidth / 2;
                const scrollPosition = imageCenter - centerPoint;

                track.style.transition = withTransition
                    ? `transform ${TRANSITION_DURATION}ms cubic-bezier(0.25, 1, 0.5, 1)`
                    : 'none';
                track.style.transform = `translateX(-${scrollPosition}px)`;
            });
        }

        function goToPos(newPos, withTransition = true) {
            if (isAnimating) return;
            isAnimating = true;
            currentPos = newPos;
            renderPosition(withTransition);

            setTimeout(() => {
                // Setelah transisi selesai, jika kita mendarat di sebuah CLONE,
                // pindahkan diam-diam (tanpa transisi) ke slide ASLI yang sama
                // secara visual. Browser tidak akan terlihat "reset" karena
                // gambar di posisi clone & posisi asli identik.
                if (totalReal > 1) {
                    if (currentPos === slides.length - 1) {
                        // Mendarat di clone slide pertama (paling kanan) → lompat diam-diam ke slide asli pertama
                        currentPos = offset;
                        renderPosition(false);
                    } else if (currentPos === 0) {
                        // Mendarat di clone slide terakhir (paling kiri) → lompat diam-diam ke slide asli terakhir
                        currentPos = slides.length - 1 - 1;
                        renderPosition(false);
                    }
                }
                isAnimating = false;
            }, TRANSITION_DURATION);
        }

        function nextSlide() {
            goToPos(currentPos + 1);
        }

        function prevSlide() {
            goToPos(currentPos - 1);
        }

        function startAutoPlay() {
            stopAutoPlay();
            if (totalReal > 1) {
                autoPlayInterval = setInterval(nextSlide, AUTOPLAY_DELAY);
            }
        }

        function stopAutoPlay() {
            if (autoPlayInterval) clearInterval(autoPlayInterval);
        }

        // Render posisi awal setelah semua gambar (termasuk clone) selesai dimuat,
        // supaya offsetLeft/offsetWidth akurat sejak pertama kali tampil.
        let imagesLoaded = 0;
        const totalSlides = slides.length;
        function onAnyImageReady() {
            imagesLoaded++;
            if (imagesLoaded === totalSlides) {
                renderPosition(false);
            }
        }
        slides.forEach((img) => {
            if (img.complete) {
                onAnyImageReady();
            } else {
                img.addEventListener('load', onAnyImageReady);
                img.addEventListener('error', onAnyImageReady);
            }
        });
        // Fallback jika event load tidak terpicu (misalnya gambar dari cache)
        renderPosition(false);

        startAutoPlay();

        btnNext.addEventListener('click', () => {
            stopAutoPlay();
            nextSlide();
            startAutoPlay();
        });

        btnPrev.addEventListener('click', () => {
            stopAutoPlay();
            prevSlide();
            startAutoPlay();
        });

        slides.forEach((img, pos) => {
            img.addEventListener('click', () => {
                if (pos === currentPos) return;
                stopAutoPlay();
                goToPos(pos);
                startAutoPlay();
            });
        });

        // Hindari perhitungan ulang yang terlalu sering saat resize (debounce)
        let resizeTimeout;
        window.addEventListener('resize', () => {
            clearTimeout(resizeTimeout);
            resizeTimeout = setTimeout(() => renderPosition(false), 150);
        });

        // Pause autoplay saat tab tidak aktif, supaya tidak ada lonjakan animasi
        // yang terasa "patah" ketika tab dibuka kembali.
        document.addEventListener('visibilitychange', () => {
            if (document.hidden) {
                stopAutoPlay();
            } else {
                startAutoPlay();
            }
        });
    }
});
</script>
@endsection