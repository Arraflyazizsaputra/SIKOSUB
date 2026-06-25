@extends('layouts.app')

@section('content')
<style>
    :root {
        --primary: #1a56db;
        --success: #22c55e;
        --warning: #facc15;
        --danger: #ef4444;
        --text-main: #111827;
        --text-muted: #6b7280;
        --bg-main: #f8f9fa;
        --border-color: #e5e7eb;
    }

    body { background-color: var(--bg-main); font-family: 'Outfit', sans-serif; }
    
    .detail-container { max-width: 1200px; margin: 2rem auto; padding: 0 clamp(1rem, 3vw, 2rem); box-sizing: border-box; }

    .breadcrumb { font-size: 0.85rem; color: var(--text-muted); margin-bottom: 1.5rem; display: flex; align-items: center; gap: 8px; flex-wrap: wrap; }
    .breadcrumb i { font-size: 1rem; color: var(--text-main); }
    .breadcrumb span, .breadcrumb a { text-decoration: none; color: inherit; font-weight: 600;}
    .breadcrumb a:hover { color: var(--primary); }

    .gallery-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 10px; border-radius: 16px; overflow: hidden; margin-bottom: 2rem; }
    @media (min-width: 768px) { .gallery-grid { grid-template-columns: 2fr 1fr 1fr; grid-template-rows: 220px 220px; } }
    
    .gallery-img { width: 100%; height: 100%; object-fit: cover; cursor: pointer; transition: transform 0.3s ease; }
    .gallery-img:hover { transform: scale(1.03); }
    .img-large { grid-column: 1 / 2; grid-row: 1 / 3; min-height: 250px; }
    
    .img-overlay-container { position: relative; display: block; overflow: hidden; background: #f1f5f9; }
    .img-overlay { position: absolute; inset: 0; background: rgba(0,0,0,0.5); display: flex; justify-content: center; align-items: center; transition: 0.3s; opacity: 0;}
    .img-overlay-container:hover .img-overlay { opacity: 1; }
    .overlay-btn { background: white; color: var(--text-main); padding: 8px 20px; border-radius: 50px; font-size: 0.85rem; font-weight: 800; pointer-events: none; }

    .content-layout { display: flex; flex-wrap: wrap; gap: 2rem; }
    .left-col { flex: 1 1 60%; min-width: 0; }
    .right-col { flex: 1 1 30%; min-width: 300px; }
    
    .kost-title { font-size: clamp(1.5rem, 3vw, 2rem); font-weight: 900; margin-bottom: 1.5rem; text-transform: uppercase; color: var(--text-main); line-height: 1.2;}

    .info-card { background: white; border: 1px solid var(--border-color); border-radius: 16px; padding: clamp(1.2rem, 3vw, 1.5rem); margin-bottom: 1.5rem; box-shadow: 0 4px 15px rgba(0,0,0,0.02); }

    .owner-header { display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 10px; margin-bottom: 10px; }
    .owner-header h4 { margin: 0; font-size: 1.1rem; font-weight: 800; text-transform: uppercase; color: var(--text-main); }
    .badge-putri { background: var(--text-main); color: white; font-size: 0.75rem; padding: 4px 10px; border-radius: 6px; font-weight: 700; text-transform: uppercase; }
    
    .status-badge { background: var(--text-main); color: white; font-size: 0.75rem; padding: 4px 12px; border-radius: 50px; margin-right: 10px; font-weight: 700;}
    .contact-actions { display: flex; gap: 12px; justify-content: flex-end; align-items: center;}
    
    .icon-btn-green, .icon-btn-blue { font-size: 1.8rem; transition: 0.2s; cursor: pointer; text-decoration: none; display: inline-flex; }
    .icon-btn-green { color: var(--success); }
    .icon-btn-green:hover { transform: scale(1.1); filter: brightness(0.9); }
    .icon-btn-blue { color: #3b82f6; }
    .icon-btn-blue:hover { transform: scale(1.1); filter: brightness(0.9); }

    .spec-section { margin-bottom: 1.5rem; }
    .spec-section h5 { font-size: 0.95rem; font-weight: 800; margin-bottom: 12px; text-transform: uppercase; color: var(--text-main);}
    .spec-list { list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 12px;}
    .spec-list li { display: flex; align-items: flex-start; gap: 15px; font-size: 0.9rem; color: #4b5563; line-height: 1.5;}
    .spec-list i { width: 20px; color: var(--text-muted); font-size: 1.1rem; margin-top: 2px;}

    .price-card { position: sticky; top: 20px; }
    .discount-info { color: var(--danger); font-size: 0.85rem; font-weight: 800; display: flex; align-items: center; gap: 5px; margin-bottom: 5px;}
    .price-text { font-size: 2.2rem; font-weight: 900; color: var(--text-main); margin-bottom: 1rem; line-height: 1;}
    .price-text span { font-size: 0.95rem; font-weight: 600; color: var(--text-muted);}
    
    .btn-chat { width: 100%; padding: 14px; background: white; border: 2px solid var(--success); border-radius: 12px; color: var(--success); font-weight: 800; font-size: 1rem; cursor: pointer; transition: 0.2s; display: flex; justify-content: center; align-items: center; gap: 10px; text-decoration: none; user-select: none;}
    .btn-chat:hover { background: var(--success); color: white; }

    .map-placeholder { width: 100%; height: 250px; border-radius: 12px; overflow: hidden; margin-bottom: 1rem; border: 1px solid var(--border-color); background: #e5e7eb;}
    .map-placeholder iframe { width: 100%; height: 100%; border: none; }
    
    .location-tabs { display: flex; flex-wrap: wrap; gap: 10px; margin-bottom: 15px;}
    .loc-tab { font-size: 0.8rem; font-weight: 700; padding: 6px 16px; border: 1px solid var(--border-color); border-radius: 50px; color: var(--text-main); background: white;}
    .loc-list { list-style: none; padding: 0; font-size: 0.85rem; line-height: 1.8; color: #4b5563; margin: 0;}

    .review-header { display: flex; align-items: center; gap: 10px; font-size: 1.3rem; font-weight: 900; margin-bottom: 15px; color: var(--text-main);}
    .review-header i { color: var(--warning); }

    /* CSS BARU UNTUK BINTANG INTERAKTIF */
    .star-rating-input { display: flex; align-items: center; gap: 8px; margin-bottom: 10px; }
    .star-rating-input i { color: #cbd5e1; font-size: 1.4rem; cursor: pointer; transition: 0.2s; }
    .star-rating-input i.active, .star-rating-input i.hovered { color: var(--warning); }
    .rating-label { font-size: 0.9rem; font-weight: 700; color: var(--text-main); margin-right: 5px; }

    .review-input-box { border: 1px solid var(--border-color); border-radius: 50px; padding: 8px 15px; display: flex; align-items: center; gap: 12px; margin-bottom: 20px; background: white; transition: 0.3s; }
    .review-input-box:focus-within { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(26, 86, 219, 0.1); }
    .review-input-box input { border: none; outline: none; width: 100%; font-size: 0.9rem; background: transparent; padding: 5px 0;}
    .btn-send-review { background: transparent; border: none; outline: none; padding: 8px; display: flex; align-items: center; justify-content: center; cursor: pointer; border-radius: 50%; transition: 0.2s;}
    .btn-send-review i { color: var(--text-muted); font-size: 1.2rem; pointer-events: none;}
    .btn-send-review:hover { background: #dcfce7; }
    .btn-send-review:hover i { color: var(--success); }

    .user-review { margin-bottom: 1.5rem; padding-bottom: 1.5rem; border-bottom: 1px solid #f3f4f6; }
    .user-review:last-child { border-bottom: none; margin-bottom: 0; padding-bottom: 0; }
    .user-review-header { display: flex; align-items: center; gap: 12px; margin-bottom: 8px;}
    .avatar { width: 38px; height: 38px; border-radius: 50%; object-fit: cover; border: 1px solid var(--border-color);}
    .user-review-name { font-weight: 800; font-size: 0.95rem; color: var(--text-main);}
    .user-rating-badge { display: inline-flex; align-items: center; gap: 4px; font-size: 0.8rem; font-weight: 800; color: var(--text-main);}
    .user-review-text { font-size: 0.9rem; color: #4b5563; line-height: 1.6; padding-left: 50px;}

    .btn-show-more { background: white; border: 1px solid var(--border-color); border-radius: 50px; padding: 10px 25px; font-size: 0.9rem; font-weight: 700; cursor: pointer; color: var(--text-main); transition: 0.2s; display: inline-flex; align-items: center; gap: 8px; user-select: none; }
    .btn-show-more:hover { background: #f3f4f6; }

    .alert-box { padding: 15px 20px; border-radius: 12px; margin-bottom: 20px; font-weight: 700; display: flex; align-items: center; gap: 10px; font-size: 0.95rem; }
    .alert-success { background: #dcfce7; color: #166534; border-left: 5px solid var(--success); }
    .alert-error { background: #fee2e2; color: #b91c1c; border-left: 5px solid var(--danger); }
</style>

<div class="detail-container">
    
    @if(session('success'))
        <div class="alert-box alert-success">
            <i class="fa-solid fa-circle-check" style="font-size: 1.2rem;"></i> {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="alert-box alert-error">
            <i class="fa-solid fa-circle-exclamation" style="font-size: 1.2rem;"></i> {{ session('error') }}
        </div>
    @endif

    <div class="breadcrumb">
        <i class="fa-solid fa-arrow-left-long"></i>
        <a href="{{ route('home') }}">HOME</a> <i class="fa-solid fa-angle-right" style="font-size:0.7rem;"></i> 
        <a href="{{ route('kost.search') }}">KOST SUBANG</a> <i class="fa-solid fa-angle-right" style="font-size:0.7rem;"></i> 
        <span>DETAIL KOST</span>
    </div>

    <div class="gallery-grid">
        <div class="img-large img-overlay-container">
            <img src="{{ asset('images/kost/' . $kost->gambar_utama) }}" alt="Foto Utama" class="gallery-img" id="main-image">
            <div class="img-overlay"><span class="overlay-btn">FOTO UTAMA</span></div>
        </div>

        @php
            $galleriesPublic = is_string($kost->gallery_images) ? json_decode($kost->gallery_images, true) : $kost->gallery_images;
            if (!is_array($galleriesPublic)) $galleriesPublic = [];
        @endphp
        
        @for($i = 0; $i < 4; $i++)
            @php 
                $imgName = $galleriesPublic[$i] ?? null;
                $imgSrc = $imgName ? asset('images/kost/' . $imgName) : 'https://placehold.co/400x300/f1f5f9/9ca3af?text=Belum+Ada+Foto';
            @endphp
            <div class="img-overlay-container">
                <img src="{{ $imgSrc }}" alt="Foto Galeri {{ $i+1 }}" class="gallery-img clickable-img">
                <div class="img-overlay"><span class="overlay-btn" style="font-size: 0.7rem;"><i class="fa-solid fa-expand"></i> LIHAT</span></div>

                @if($i == 3 && count($galleriesPublic) > 4)
                    <a href="{{ route('kost.gallery', $kost->id) }}" style="position: absolute; inset: 0; background: rgba(0,0,0,0.7); display: flex; flex-direction: column; align-items: center; justify-content: center; text-decoration: none; color: white; transition: 0.3s; z-index: 10;">
                        <span style="font-size: 2rem; font-weight: 900;">+{{ count($galleriesPublic) - 4 }}</span>
                        <span style="font-size: 0.8rem; font-weight: 700; letter-spacing: 1px;">FOTO LAINNYA</span>
                    </a>
                @endif
            </div>
        @endfor
    </div>

    <div style="text-align: right; margin-top: -15px; margin-bottom: 25px;">
        <a href="{{ route('kost.gallery', $kost->id) }}" style="color: var(--primary); font-weight: 800; text-decoration: none; font-size: 0.95rem; transition: 0.2s;">
            Buka Halaman Galeri Lengkap <i class="fa-solid fa-arrow-right"></i>
        </a>
    </div>

    <h1 class="kost-title">{{ $kost->nama_kost }}</h1>

    <div class="content-layout">
        
        <div class="left-col">
            <div class="info-card">
                <div class="owner-header">
                    <h4>{{ $kost->nama_kost }}</h4>
                    <span class="badge-putri">KOST {{ $kost->tipe_kost }}</span>
                </div>
                <p style="font-size: 0.9rem; color: var(--text-muted); margin-bottom: 12px;"><i class="fa-solid fa-location-dot"></i> {{ $kost->alamat }}</p>
                <p style="font-size: 0.95rem; font-weight: 800; color: var(--warning); margin-bottom: 20px;"><i class="fa-solid fa-star"></i> {{ number_format($kost->rating ?? 5.0, 1) }}</p>
                
                <div style="display: flex; align-items: center; margin-bottom: 20px;">
                    <span class="status-badge">{{ $kost->info_kamar ?? 'Tersedia' }}</span>
                    <a href="#info-kamar" style="font-size: 0.85rem; font-weight: 800; color: var(--text-main); text-decoration: underline;">Info Kamar</a>
                </div>

                <div style="display: flex; flex-wrap: wrap; justify-content: space-between; align-items: flex-end; gap: 15px; border-top: 1px solid var(--border-color); padding-top: 20px;">
                    <div>
                        <p style="font-size: 0.8rem; font-weight: 800; margin: 0; color: var(--text-muted);">KOST DISEWAKAN OLEH</p>
                        <p style="font-size: 0.9rem; font-weight: 900; background: var(--text-main); color: white; display: inline-block; padding: 4px 10px; margin-top: 5px; border-radius: 6px;">{{ strtoupper($kost->disewakan_oleh ?? 'PEMILIK KOST') }}</p>
                        <p style="font-size: 0.8rem; color: var(--text-muted); max-width: 300px; margin-top: 8px; line-height: 1.5;">Untuk booking langsung hubungi ke WhatsApp dengan klik ikon di samping kanan.</p>
                    </div>
                    
                    <div class="contact-actions">
                        <a href="https://wa.me/{{ preg_replace('/^0/', '62', $kost->no_wa ?? $kost->kontak_pemilik) }}?text={{ urlencode('Halo, saya tertarik dengan kost ' . $kost->nama_kost . ' yang ada di SIKOSUB. Apakah kamarnya masih tersedia?') }}" class="icon-btn-green" target="_blank" title="Chat WhatsApp Pemilik">
                            <i class="fa-brands fa-whatsapp"></i>
                        </a>
                        <a href="{{ $kost->maps ?? '#' }}" class="icon-btn-blue" target="_blank" title="Buka Rute di Google Maps">
                            <i class="fa-solid fa-map-location-dot"></i>
                        </a>
                    </div>
                </div>
            </div>

            <div class="info-card" id="info-kamar">
                <div class="spec-section">
                    <h5>SPESIFIKASI TIPE KAMAR</h5>
                    <ul class="spec-list">
                        <li><i class="fa-solid fa-house-chimney"></i> <div>{!! $kost->spesifikasi_kamar ? nl2br(e($kost->spesifikasi_kamar)) : 'Belum ada data' !!}</div></li>
                    </ul>
                </div>
                <div class="spec-section">
                    <h5>FASILITAS KAMAR</h5>
                    <ul class="spec-list">
                        <li><i class="fa-solid fa-bed"></i> <div>{!! $kost->fasilitas_kamar ? nl2br(e($kost->fasilitas_kamar)) : 'Belum ada data' !!}</div></li>
                    </ul>
                </div>
                <div class="spec-section">
                    <h5>FASILITAS KAMAR MANDI</h5>
                    <ul class="spec-list">
                        <li><i class="fa-solid fa-shower"></i> <div>{!! $kost->fasilitas_km ? nl2br(e($kost->fasilitas_km)) : 'Belum ada data' !!}</div></li>
                    </ul>
                </div>
                <div class="spec-section">
                    <h5>FASILITAS UMUM</h5>
                    <ul class="spec-list">
                        <li><i class="fa-solid fa-bolt"></i> <div>{!! $kost->fasilitas_umum ? nl2br(e($kost->fasilitas_umum)) : 'Belum ada data' !!}</div></li>
                    </ul>
                </div>
                <div class="spec-section">
                    <h5>FASILITAS PARKIR</h5>
                    <ul class="spec-list">
                        <li><i class="fa-solid fa-motorcycle"></i> <div>{!! $kost->fasilitas_parkir ? nl2br(e($kost->fasilitas_parkir)) : 'Belum ada data' !!}</div></li>
                    </ul>
                </div>
                <div class="spec-section">
                    <h5>PERATURAN KAMAR KOST</h5>
                    <ul class="spec-list">
                        <li><i class="fa-solid fa-triangle-exclamation"></i> <div>{!! $kost->peraturan ? nl2br(e($kost->peraturan)) : 'Belum ada data' !!}</div></li>
                    </ul>
                </div>
                <div class="spec-section">
                    <h5>KETENTUAN SEWA</h5>
                    <ul class="spec-list">
                        <li><i class="fa-solid fa-clipboard-list"></i> <div>{!! $kost->ketentuan ? nl2br(e($kost->ketentuan)) : 'Belum ada data' !!}</div></li>
                    </ul>
                </div>
            </div>

            <div style="display: flex; align-items: center; gap: 15px; margin-top: 1.5rem; padding-bottom: 2rem;">
                <img src="https://ui-avatars.com/api/?name={{ urlencode($kost->pemilik ?? 'Pemilik Kost') }}&background=e5e7eb&color=374151" alt="Profile" style="width: 55px; height: 55px; border-radius: 50%;">
                <div>
                    <div style="font-weight: 900; font-size: 1.1rem; text-transform: uppercase; color: var(--text-main);">{{ $kost->pemilik ?? $kost->disewakan_oleh ?? 'PEMILIK KOST' }}</div>
                    <div style="color: var(--text-muted); font-size: 0.9rem; font-weight: 600;">Pemilik Kost / Pengelola</div>
                </div>
            </div>

        </div>

        <div class="right-col">
            
            <div class="info-card price-card">
                @if($kost->harga_diskon > 0)
                    <div class="discount-info">
                        <i class="fa-solid fa-bolt"></i> Diskon Rp {{ number_format($kost->harga_per_bulan - $kost->harga_diskon, 0, ',', '.') }} 
                        <span style="text-decoration: line-through; color: #9ca3af; font-weight: 600; margin-left: 8px;">Rp. {{ number_format($kost->harga_per_bulan, 0, ',', '.') }}</span>
                    </div>
                    <div class="price-text">
                        Rp {{ number_format($kost->harga_diskon, 0, ',', '.') }} <span>/ Bulan</span>
                    </div>
                @else
                    <div class="price-text">
                        Rp {{ number_format($kost->harga_per_bulan, 0, ',', '.') }} <span>/ Bulan</span>
                    </div>
                @endif
                
                <a href="https://wa.me/{{ preg_replace('/^0/', '62', $kost->no_wa ?? $kost->kontak_pemilik) }}?text={{ urlencode('Halo, saya tertarik dengan kost ' . $kost->nama_kost . '. Apakah masih kosong?') }}" class="btn-chat" target="_blank">
                    <i class="fa-regular fa-comment-dots"></i> Chat Pemilik Kost
                </a>
            </div>

            <h4 style="font-size: 1.1rem; font-weight: 900; margin: 2rem 0 1rem; text-transform: uppercase; color: var(--text-main);">LOKASI & LINGKUNGAN</h4>
            <div class="info-card" style="padding: 15px;">
                
                <div class="map-placeholder">
                    @if(!empty($kost->maps) && str_contains($kost->maps, '<iframe'))
                        {!! $kost->maps !!}
                    @elseif(!empty($kost->maps) && str_contains($kost->maps, 'embed'))
                        <iframe src="{{ $kost->maps }}" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                    @elseif(!empty($kost->maps))
                        <div style="display:flex; flex-direction:column; justify-content:center; align-items:center; height:100%; background:#f8fafc; padding:20px; text-align:center;">
                            <i class="fa-solid fa-map-location-dot" style="font-size:3rem; color:#9ca3af; margin-bottom:15px;"></i>
                            <p style="font-size:0.85rem; color:#6b7280; font-weight:600; margin-bottom:15px;">Peta menggunakan rute langsung.</p>
                            <a href="{{ $kost->maps }}" target="_blank" style="background:var(--primary); color:white; padding:8px 20px; border-radius:8px; text-decoration:none; font-weight:700; font-size:0.85rem;">
                                <i class="fa-solid fa-up-right-from-square"></i> Buka Google Maps
                            </a>
                        </div>
                    @else
                        <div style="display:flex; justify-content:center; align-items:center; height:100%; color:#9ca3af; font-weight:600;">
                            <i class="fa-solid fa-map-location-dot" style="margin-right:8px;"></i> Map tidak tersedia
                        </div>
                    @endif
                </div>
                
                <div class="location-tabs">
                    <span class="loc-tab">Tempat terdekat</span>
                    <span class="loc-tab" style="border: none; color: var(--text-muted); background:transparent;">Jarak dari pusat ({{ $kost->jarak_km ?? 0 }} km)</span>
                </div>
                
                <div style="display: flex; gap: 15px; margin-top: 15px;">
                    <i class="fa-solid fa-map-location-dot" style="font-size: 1.5rem; color: var(--text-muted); margin-top: 5px;"></i>
                    @if($kost->tempat_terdekat)
                        <ul class="loc-list">
                            @foreach(explode("\n", $kost->tempat_terdekat) as $tempat)
                                @if(trim($tempat))
                                    <li>{{ trim($tempat) }}</li>
                                @endif
                            @endforeach
                        </ul>
                    @else
                        <ul class="loc-list">
                            <li>Belum ada data detail tempat terdekat</li>
                        </ul>
                    @endif
                </div>
            </div>

            <div class="review-header">
                <i class="fa-solid fa-star"></i> {{ number_format($kost->rating ?? 5.0, 1) }} Ulasan Pengunjung
            </div>
            
            <!-- FORMULIR INPUT RATING YANG SUDAH DIPERBAIKI MENJADI INTERAKTIF -->
            <form action="{{ route('review.store.public', $kost->id) }}" method="POST" style="margin-bottom: 20px;">
                @csrf
                <div class="star-rating-input" id="star-rating-container">
                    <span class="rating-label">Beri Rating:</span>
                    <i class="fa-solid fa-star active" data-value="1"></i>
                    <i class="fa-solid fa-star active" data-value="2"></i>
                    <i class="fa-solid fa-star active" data-value="3"></i>
                    <i class="fa-solid fa-star active" data-value="4"></i>
                    <i class="fa-solid fa-star active" data-value="5"></i>
                </div>
                <!-- Nilai asli yang akan dikirim ke database -->
                <input type="hidden" name="rating" id="rating-value" value="5">

                <div class="review-input-box">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name ?? 'Tamu') }}&background=e5e7eb&color=374151" alt="Me" class="avatar" style="width: 32px; height: 32px; border:none;">
                    
                    <input type="text" name="komentar" placeholder="Tulis ulasan pengalaman Anda tentang kost ini..." required>

                    <button type="submit" class="btn-send-review" title="Kirim Ulasan">
                        <i class="fa-solid fa-paper-plane"></i>
                    </button>
                </div>
            </form>

            <div id="reviews-container">
                @if(isset($kost->reviews) && $kost->reviews->count() > 0)
                    @foreach($kost->reviews as $index => $review)
                    <div class="user-review {{ $index >= 5 ? 'hidden-review' : '' }}" style="{{ $index >= 5 ? 'display:none;' : '' }}">
                        <div class="user-review-header">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($review->user->name ?? 'User') }}&background=f1f5f9&color=475569" class="avatar" style="width: 30px; height: 30px; border:none;">
                            <span class="user-review-name">{{ strtoupper($review->user->name ?? 'PENGGUNA') }}</span>
                            <span style="flex-grow: 1;"></span>
                            
                            <span class="user-rating-badge">
                                @for($i=1; $i<=5; $i++)
                                    <i class="fa-solid fa-star" style="{{ $i <= $review->rating ? 'color: var(--warning);' : 'color: var(--border-color);' }}"></i>
                                @endfor
                                <span style="margin-left:5px;">{{ number_format($review->rating, 1) }}</span>
                            </span>
                        </div>
                        <div class="user-review-text">{{ $review->komentar }}</div>
                    </div>
                    @endforeach
                @else
                    <p style="color: var(--text-muted); font-size: 0.9rem; text-align: center; margin: 2rem 0; padding: 2rem; border: 1px dashed var(--border-color); border-radius: 12px;">Belum ada ulasan untuk properti kost ini.</p>
                @endif
            </div>

            @if(isset($kost->reviews) && $kost->reviews->count() > 5)
            <div style="text-align: center; margin-top: 1.5rem;">
                <button type="button" id="btn-show-more" class="btn-show-more" onclick="showAllReviews()">
                    <i class="fa-solid fa-circle-plus"></i> Tampilkan Semua Ulasan ({{ $kost->reviews->count() }})
                </button>
            </div>
            @endif

        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // SCRIPT 1: GALERI FOTO INTERAKTIF
        const mainImg = document.getElementById('main-image');
        const smallImgs = document.querySelectorAll('.clickable-img');
        
        smallImgs.forEach(img => {
            img.addEventListener('click', function(e) {
                e.preventDefault();
                const tempSrc = mainImg.src;
                mainImg.src = this.src;
                this.src = tempSrc;
            });
        });

        // SCRIPT 2: SISTEM BINTANG RATING INTERAKTIF
        const stars = document.querySelectorAll('#star-rating-container i');
        const ratingInput = document.getElementById('rating-value');

        stars.forEach(star => {
            // Efek saat kursor menyentuh bintang (Hover)
            star.addEventListener('mouseover', function() {
                let value = this.getAttribute('data-value');
                stars.forEach(s => {
                    if (s.getAttribute('data-value') <= value) {
                        s.classList.add('hovered');
                        s.classList.remove('active'); // Matikan active sebentar
                    } else {
                        s.classList.remove('hovered');
                        s.classList.remove('active');
                    }
                });
            });

            // Efek saat kursor menjauh (Kembalikan ke nilai yang sudah diklik)
            star.addEventListener('mouseout', function() {
                let currentValue = ratingInput.value;
                stars.forEach(s => {
                    s.classList.remove('hovered');
                    if (s.getAttribute('data-value') <= currentValue) {
                        s.classList.add('active');
                    }
                });
            });

            // Efek saat bintang diklik (Menyimpan Nilai)
            star.addEventListener('click', function() {
                let value = this.getAttribute('data-value');
                ratingInput.value = value; // Simpan nilai ke input tersembunyi
                
                stars.forEach(s => {
                    if (s.getAttribute('data-value') <= value) {
                        s.classList.add('active');
                    } else {
                        s.classList.remove('active');
                    }
                });
            });
        });
    });

    // SCRIPT 3: TAMPILKAN SEMUA ULASAN
    function showAllReviews() {
        document.querySelectorAll('.hidden-review').forEach(el => {
            el.style.display = 'block';
        });
        document.getElementById('btn-show-more').style.display = 'none';
    }
</script>
@endsection