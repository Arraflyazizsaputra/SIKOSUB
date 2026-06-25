@extends('layouts.admin')

@section('content')
<style>
    /* Global Container */
    .content-wrapper { padding: 40px; max-width: 1200px; margin: 0 auto; }
    
    /* Header Typography */
    .page-title { font-size: 24px; color: #111; font-weight: 700; margin-bottom: 8px; }
    .page-subtitle { font-size: 12px; color: #555; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 30px; display: block; }
    
    /* Card Styling */
    .kost-card { 
        background: white; 
        border: 1px solid #e5e7eb;
        border-radius: 12px; 
        padding: 16px; 
        margin-bottom: 20px; 
        display: flex; 
        align-items: stretch; 
        box-shadow: 0 4px 6px rgba(0,0,0,0.02); 
    }
    
    /* Image Section */
    .kost-img-wrapper { flex-shrink: 0; margin-right: 24px; display: flex; align-items: center; }
    .kost-img { width: 260px; height: 160px; border-radius: 8px; object-fit: cover; }
    
    /* Details Section */
    .kost-details { flex-grow: 1; position: relative; display: flex; flex-direction: column; justify-content: center; padding-right: 20px; }
    
    /* Badges (Top Right of Details) */
    .badge-container { position: absolute; top: 0; right: 0; display: flex; }
    .badge-tipe { background: #FFEA00; color: #000; padding: 4px 12px; font-size: 11px; font-weight: 800; border-radius: 4px 0 0 4px; text-transform: uppercase; }
    .badge-rating { background: #FF0000; color: #FFF; padding: 4px 10px; font-size: 11px; font-weight: 800; border-radius: 0 4px 4px 0; }
    
    /* Content Typography */
    .kost-title { font-size: 14px; font-weight: 800; color: #333; margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.5px; margin-top: 5px; }
    .kost-price { font-size: 20px; font-weight: 800; color: #000; margin-bottom: 4px; }
    
    /* Discount Row */
    .discount-row { display: flex; align-items: center; gap: 10px; margin-bottom: 12px; }
    .txt-diskon { font-size: 10px; color: #FF0000; font-weight: 700; }
    .txt-coret { font-size: 10px; color: #aaa; text-decoration: line-through; font-weight: 600; }
    
    /* Address */
    .kost-address { font-size: 12px; color: #888; line-height: 1.5; }
    .kost-address strong { color: #666; font-weight: 600; display: block; margin-bottom: 2px; }
    
    /* Small Icons (WA, Maps) */
    .action-icons { position: absolute; bottom: 0; right: 0; display: flex; gap: 12px; }
    .icon-circle { width: 28px; height: 28px; background: #f3f4f6; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #555; text-decoration: none; transition: 0.2s; }
    .icon-circle:hover { background: #e5e7eb; }
    .icon-wa { color: #25D366; }
    .icon-map { color: #4285F4; }
    
    /* Action Column (Right Side) - Khusus Halaman Review */
    .actions-col { width: 170px; border-left: 1px solid #f0f0f0; display: flex; flex-direction: column; align-items: center; justify-content: center; padding-left: 24px; flex-shrink: 0; }
    .review-icon { font-size: 32px; color: #111; margin-bottom: 15px; }
    
    .btn-action { width: 100%; padding: 8px 10px; border-radius: 50px; font-weight: 700; font-size: 12px; cursor: pointer; text-align: center; background: white; transition: 0.2s; text-decoration: none; display: block; margin-bottom: 8px; }
    .btn-outline-indigo { border: 1px solid #4f46e5; color: #111; }
    .btn-outline-indigo:hover { background: #f5f3ff; color: #4f46e5; }
</style>

<div class="content-wrapper">
    <h1 class="page-title">Daftar Riview Kost</h1>
    <span class="page-subtitle">SIKOSUB</span>

    {{-- Menggunakan @forelse agar bisa menampilkan pesan jika data kosong --}}
    @forelse($kosts as $kost)
    <div class="kost-card">
        
        <div class="kost-img-wrapper">
            <img src="{{ asset('images/kost/'.$kost->gambar_utama) }}" class="kost-img" alt="Kost">
        </div>
        
        <div class="kost-details">
            <div class="badge-container">
                <span class="badge-tipe">{{ $kost->tipe_kost }}</span>
                <span class="badge-rating">★ {{ number_format($kost->rating ?? 5.0, 1) }}</span>
            </div>
            
            <div class="kost-title">{{ $kost->nama_kost }}</div>
            
            <div class="kost-price">
                RP. {{ number_format($kost->harga_diskon > 0 ? $kost->harga_diskon : $kost->harga_per_bulan, 2, ',', '.') }} / bln
            </div>
            
            @if($kost->harga_diskon > 0)
            <div class="discount-row">
                <span class="txt-diskon">Diskon Rp {{ number_format($kost->harga_per_bulan - $kost->harga_diskon, 2, ',', '.') }}</span>
                <span class="txt-coret">Rp {{ number_format($kost->harga_per_bulan, 2, ',', '.') }}</span>
            </div>
            @else
            <div class="discount-row" style="visibility: hidden;">
                <span class="txt-diskon">Space</span>
            </div>
            @endif
            
            <div class="kost-address">
                <strong>Soklat</strong>
                {{ $kost->alamat }}
            </div>
            
            <div class="action-icons">
                <a href="https://wa.me/{{ $kost->no_wa }}" target="_blank" class="icon-circle icon-wa">
                    <i class="fa-brands fa-whatsapp" style="font-size: 16px;"></i>
                </a>
                <a href="{{ $kost->maps }}" target="_blank" class="icon-circle icon-map">
                    <i class="fa-solid fa-location-dot" style="font-size: 14px;"></i>
                </a>
            </div>
        </div>

        <div class="actions-col">
            <i class="fa-solid fa-pen-to-square review-icon"></i>
            
            <a href="{{ route('review.view', $kost->id) }}" class="btn-action btn-outline-indigo">lihat ulasan kost</a>
            <a href="{{ route('review.manage-list', $kost->id) }}" class="btn-action btn-outline-indigo">kelola ulasan kost</a>
        </div>
        
    </div>
    @empty
        <div style="text-align: center; padding: 40px; background: white; border-radius: 12px; border: 1px solid #e5e7eb;">
            <p style="color: #888;">Belum ada data kost untuk direview saat ini.</p>
        </div>
    @endforelse
</div>
@endsection