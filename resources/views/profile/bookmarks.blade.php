@extends('layouts.app')

@section('content')
<style>
    /* =========================================
       MODERN PROFILE & BOOKMARK LAYOUT
       ========================================= */
    :root {
        --primary: #3b82f6;
        --primary-hover: #2563eb;
        --danger: #ef4444;
        --warning: #facc15;
        --success: #22c55e;
        --text-main: #111827;
        --text-muted: #6b7280;
        --bg-body: #f9fafb;
        --border-color: #e5e7eb;
    }

    html { scroll-behavior: smooth; }

    /* === LAYOUT UTAMA === */
    .profile-layout {
        display: flex;
        min-height: calc(100vh - 70px);
        background: var(--bg-body);
        border-top: 1px solid var(--border-color);
        font-family: 'Outfit', sans-serif;
    }

    /* Sidebar Biru Paling Kiri */
    .blue-sidebar {
        width: 80px;
        background-color: var(--primary);
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 30px 0;
        gap: 35px;
        box-shadow: 4px 0 15px rgba(59, 130, 246, 0.2);
        z-index: 10;
    }

    .blue-sidebar a, .blue-sidebar i {
        color: white;
        font-size: 22px;
        text-decoration: none;
        cursor: pointer;
        transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .blue-sidebar i:hover { transform: scale(1.2); }

    .blue-sidebar .icon-top {
        color: var(--primary);
        background: #fff;
        border-radius: 50%;
        font-size: 32px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }

    .blue-sidebar .icon-bottom {
        color: var(--primary);
        background: #fff;
        border-radius: 50%;
        font-size: 28px;
        padding: 8px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }

    /* Sidebar Putih (Menu Setting) */
    .white-sidebar {
        width: 280px;
        border-right: 1px solid var(--border-color);
        padding: 30px 25px;
        background: #fff;
        z-index: 5;
    }

    .white-sidebar .menu-title {
        font-size: 1.2rem;
        font-weight: 800;
        margin-bottom: 35px;
        display: flex;
        align-items: center;
        gap: 15px;
        cursor: pointer;
        color: var(--text-main);
    }

    .white-sidebar .menu-item {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 20px;
        font-weight: 600;
        color: var(--text-muted);
        cursor: pointer;
        font-size: 1.05rem;
        transition: all 0.2s;
        padding: 10px 15px;
        border-radius: 10px;
        text-decoration: none;
    }

    .white-sidebar .menu-item:hover { color: var(--primary); background: #eff6ff; transform: translateX(5px); }
    .white-sidebar .menu-item.active { color: var(--primary); font-weight: 800; background: #eff6ff; }

    /* Area Konten Utama */
    .main-content {
        flex: 1;
        padding: 40px clamp(20px, 5vw, 60px);
        background: #fff;
    }

    .profile-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 40px;
        max-width: 900px;
        border-bottom: 1px solid var(--border-color);
        padding-bottom: 20px;
    }

    .profile-header h1 {
        font-size: clamp(24px, 3vw, 32px);
        font-weight: 900;
        color: var(--text-main);
        margin: 0;
    }

    .profile-avatar {
        width: 75px;
        height: 75px;
        border-radius: 50%;
        object-fit: cover;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        border: 3px solid white;
    }

    /* === STYLING KARTU BOOKMARK === */
    .bookmark-list {
        display: flex;
        flex-direction: column;
        gap: 20px;
        max-width: 900px;
    }

    .kost-list-card {
        display: flex;
        background: #ffffff;
        border: 1px solid var(--border-color);
        border-radius: 16px;
        overflow: hidden;
        position: relative;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 4px 15px rgba(0,0,0,0.02);
    }

    .kost-list-card:hover {
        box-shadow: 0 15px 35px rgba(59, 130, 246, 0.08);
        transform: translateY(-4px);
        border-color: #bfdbfe;
    }

    .img-wrapper { overflow: hidden; width: 300px; flex-shrink: 0; }
    .kost-list-img {
        width: 100%;
        height: 100%;
        min-height: 200px;
        object-fit: cover;
        transition: transform 0.5s ease;
    }
    .kost-list-card:hover .kost-list-img { transform: scale(1.05); }

    .kost-list-content {
        padding: 25px;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
        justify-content: center;
        position: relative;
    }

    .badge-container {
        position: absolute;
        top: 15px;
        right: 15px;
        display: flex;
        gap: 8px;
    }

    .badge-type {
        background-color: var(--warning);
        color: #854d0e;
        font-weight: 800;
        font-size: 0.7rem;
        padding: 4px 10px;
        border-radius: 6px;
        text-transform: uppercase;
    }

    .badge-rating {
        background-color: var(--danger);
        color: #fff;
        font-weight: 800;
        font-size: 0.75rem;
        padding: 4px 10px;
        border-radius: 6px;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .kost-name-link { text-decoration: none; color: inherit; display: inline-block; outline: none; }
    .kost-name {
        font-size: 1.1rem;
        font-weight: 800;
        color: var(--text-main);
        text-transform: uppercase;
        margin-bottom: 8px;
        transition: color 0.2s;
    }
    .kost-name-link:hover .kost-name { color: var(--primary); }

    .kost-price {
        font-size: 1.5rem;
        font-weight: 900;
        color: var(--primary);
        margin-bottom: 5px;
    }

    .discount-text {
        font-size: 0.8rem;
        color: var(--danger);
        font-weight: 700;
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .discount-text span {
        color: var(--text-muted);
        text-decoration: line-through;
        font-weight: 500;
    }

    .kost-address {
        font-size: 0.85rem;
        color: var(--text-muted);
        max-width: 85%;
        line-height: 1.5;
        display: flex;
        align-items: flex-start;
        gap: 6px;
    }
    .kost-address i { color: var(--danger); margin-top: 3px; }

    .kost-actions {
        position: absolute;
        bottom: 20px;
        right: 20px;
        display: flex;
        gap: 12px;
    }

    .action-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: #f1f5f9;
        color: var(--text-muted);
        font-size: 1.1rem;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s;
        text-decoration: none;
        border: none;
        outline: none;
    }

    .action-icon:hover { background: #dbeafe; color: var(--primary); transform: translateY(-3px); }
    
    /* Warna khusus tombol hapus bookmark */
    .btn-remove-bookmark { color: var(--danger); background: #fee2e2; }
    .btn-remove-bookmark:hover { background: var(--danger); color: white; transform: translateY(-3px); }

    /* Empty State */
    .empty-state { text-align: center; padding: 60px 20px; background: #f8fafc; border: 2px dashed var(--border-color); border-radius: 16px; max-width: 900px;}
    .empty-state i { font-size: 4rem; color: #cbd5e1; margin-bottom: 15px; }
    .empty-state h3 { font-size: 1.25rem; font-weight: 800; color: var(--text-main); margin-bottom: 5px; }
    .empty-state p { color: var(--text-muted); font-size: 0.95rem; margin-bottom: 20px; }
    .btn-cari-kost { background: var(--primary); color: white; padding: 10px 25px; border-radius: 50px; font-weight: 700; text-decoration: none; display: inline-block; transition: 0.3s;}
    .btn-cari-kost:hover { background: var(--primary-hover); transform: translateY(-2px); box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3); }

    /* Responsif */
    @media (max-width: 900px) {
        .profile-layout { flex-direction: column; }
        .blue-sidebar { width: 100%; flex-direction: row; justify-content: space-around; padding: 15px 0; height: auto;}
        .white-sidebar { width: 100%; border-right: none; border-bottom: 1px solid var(--border-color); display: flex; justify-content: center; gap: 20px; padding: 15px; flex-wrap: wrap;}
        .white-sidebar .menu-title { width: 100%; justify-content: center; margin-bottom: 10px; }
        .white-sidebar .menu-item { margin-bottom: 0; }
        .main-content { padding: 30px 20px; }
        .kost-list-card { flex-direction: column; }
        .img-wrapper { width: 100%; height: 220px; }
        .kost-address { max-width: 100%; margin-bottom: 40px; }
        .badge-container { left: 15px; right: auto; }
    }
</style>

<div class="profile-layout">
    
    <aside class="blue-sidebar">
        <a href="{{ route('profile.edit') }}" title="Profil Saya" style="text-decoration: none; display: flex;">
            <i class="fa-solid fa-circle-user icon-top"></i>
        </a>
        
        <a href="{{ route('home') }}" title="Beranda">
            <i class="fa-solid fa-house"></i>
        </a>
        
        <a href="{{ route('profile.bookmarks') }}" title="Bookmark Kost">
            <i class="fa-solid fa-bookmark"></i>
        </a>
        
        <div style="flex-grow: 1;"></div>
        
        <a href="{{ route('profile.edit') }}" title="Pengaturan Akun" style="text-decoration: none; display: flex;">
            <i class="fa-solid fa-gear icon-bottom"></i>
        </a>
    </aside>

    <aside class="white-sidebar">
        <div class="menu-title">
            <i class="fa-solid fa-chevron-left"></i> Settings
        </div>
        
        <a href="{{ route('profile.edit') }}" class="menu-item">
            <i class="fa-solid fa-pen"></i> Edit profile
        </a>
        
        <div class="menu-item active">
            <i class="fa-solid fa-bookmark"></i> Bookmark Kost
        </div>
    </aside>

    <main class="main-content">
        
        <div class="profile-header">
            <h1>Daftar Kost Terfavorit</h1>
            @php
                $avatarUrl = Auth::user()->avatar ? asset('storage/avatars/' . Auth::user()->avatar) : 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name).'&background=3b82f6&color=fff';
            @endphp
            <img src="{{ $avatarUrl }}" alt="Avatar" class="profile-avatar">
        </div>

        <div class="bookmark-list" id="bookmark-container">
            
            {{-- Asumsi data dilempar dengan nama $bookmarks dari Controller --}}
            @forelse(isset($bookmarks) ? $bookmarks : [] as $bookmark)
                @php 
                    // Mendapatkan model kost dari relasi bookmark
                    $item = $bookmark->kost ?? $bookmark; 
                @endphp
                
                <div class="kost-list-card" id="card-bookmark-{{ $item->id }}">
                    <div class="img-wrapper">
                        <a href="{{ route('kost.detail', ['id' => $item->id]) }}" style="text-decoration: none; display: block; height:100%;">
                            <img src="{{ $item->gambar_utama ? asset('images/kost/'.$item->gambar_utama) : 'https://images.unsplash.com/photo-1555854877-bab0e564b8d5?auto=format&fit=crop&w=600&q=80' }}" alt="{{ $item->nama_kost }}" class="kost-list-img">
                        </a>
                    </div>
                    
                    <div class="kost-list-content">
                        <div class="badge-container">
                            <span class="badge-type">{{ strtoupper($item->tipe_kost) }}</span>
                            <span class="badge-rating"><i class="fa-solid fa-star"></i> {{ number_format($item->rating ?? 5.0, 1) }}</span>
                        </div>
                        
                        <a href="{{ route('kost.detail', ['id' => $item->id]) }}" class="kost-name-link">
                            <h3 class="kost-name">{{ $item->nama_kost }}</h3>
                        </a>
                        
                        <div class="kost-price">Rp {{ number_format($item->harga_diskon > 0 ? $item->harga_diskon : $item->harga_per_bulan, 0, ',', '.') }} / bln</div>
                        
                        @if($item->harga_diskon > 0)
                        <div class="discount-text">
                            Hemat Rp {{ number_format($item->harga_per_bulan - $item->harga_diskon, 0, ',', '.') }} 
                            <span>Rp {{ number_format($item->harga_per_bulan, 0, ',', '.') }}</span>
                        </div>
                        @else
                        <div class="discount-text" style="visibility: hidden;">Space</div>
                        @endif
                        
                        <div class="kost-address">
                            <i class="fa-solid fa-map-pin"></i>
                            <span>{{ $item->alamat }}</span>
                        </div>

                        <div class="kost-actions">
                            <button type="button" class="action-icon btn-remove-bookmark" data-id="{{ $item->id }}" title="Hapus dari Favorit" onclick="removeBookmark({{ $item->id }}, this)">
                                <i class="fa-solid fa-heart-crack"></i>
                            </button>
                            
                            <a href="https://wa.me/{{ preg_replace('/^0/', '62', $item->no_wa ?? $item->kontak_pemilik) }}?text={{ urlencode('Halo, saya tertarik dengan kost ' . $item->nama_kost . '. Apakah masih tersedia?') }}" target="_blank" class="action-icon" style="color:#16a34a; background:#dcfce7;" title="Hubungi Pemilik">
                                <i class="fa-brands fa-whatsapp"></i>
                            </a>
                            
                            <a href="{{ !empty($item->maps) ? $item->maps : '#' }}" target="_blank" class="action-icon" style="color:#3b82f6; background:#dbeafe;" title="Lihat Lokasi">
                                <i class="fa-solid fa-location-dot"></i>
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="empty-state" id="empty-state-alert">
                    <i class="fa-solid fa-heart-crack"></i>
                    <h3>Belum Ada Kost Favorit</h3>
                    <p>Anda belum menyimpan properti kost manapun ke dalam daftar favorit.</p>
                    <a href="{{ route('kost.search') }}" class="btn-cari-kost"><i class="fa-solid fa-magnifying-glass"></i> Cari Kost Sekarang</a>
                </div>
            @endforelse

            <div class="empty-state" id="empty-state-js" style="display: none;">
                <i class="fa-solid fa-heart-crack"></i>
                <h3>Belum Ada Kost Favorit</h3>
                <p>Anda belum menyimpan properti kost manapun ke dalam daftar favorit.</p>
                <a href="{{ route('kost.search') }}" class="btn-cari-kost"><i class="fa-solid fa-magnifying-glass"></i> Cari Kost Sekarang</a>
            </div>

        </div>

    </main>
</div>

<script>
    function removeBookmark(kostId, buttonElement) {
        // Beri efek loading sementara
        const icon = buttonElement.querySelector('i');
        icon.className = 'fa-solid fa-spinner fa-spin';
        
        fetch('{{ route("kost.bookmark") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ kost_id: kostId })
        })
        .then(response => response.json())
        .then(data => {
            if(data.status === 'removed' || data.status === 'error') {
                // Hapus card dari layar dengan animasi fade out
                const card = document.getElementById('card-bookmark-' + kostId);
                card.style.opacity = '0';
                card.style.transform = 'scale(0.95)';
                
                setTimeout(() => {
                    card.remove();
                    
                    // Cek apakah masih ada card tersisa di dalam container
                    const remainingCards = document.querySelectorAll('.kost-list-card');
                    if(remainingCards.length === 0) {
                        document.getElementById('empty-state-js').style.display = 'block';
                    }
                }, 300); // Tunggu animasi selesai
            }
        })
        .catch(error => {
            console.error('Error:', error);
            // Kembalikan icon jika gagal
            icon.className = 'fa-solid fa-heart-crack';
            alert('Gagal menghapus bookmark, periksa koneksi Anda.');
        });
    }
</script>
@endsection