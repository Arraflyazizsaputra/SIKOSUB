@forelse($kosts as $kost)
<div class="kost-list-card">
    
    <div class="img-wrapper">
        <a href="{{ route('kost.detail', ['id' => $kost->id]) }}" style="text-decoration: none; display: block; height: 100%;">
            <img src="{{ !empty($kost->gambar_utama) ? asset('images/kost/' . $kost->gambar_utama) : 'https://images.unsplash.com/photo-1518780664697-55e3ad937233?auto=format&fit=crop&w=600&q=80' }}" alt="{{ $kost->nama_kost }}" class="kost-list-img">
        </a>
    </div>
    
    <div class="kost-list-content">
        <div class="badge-container">
            <span class="badge-type">{{ strtoupper($kost->tipe_kost) }}</span>
            <span class="badge-rating"><i class="fa-solid fa-star"></i> {{ number_format($kost->rating ?? 5.0, 1) }}</span>
        </div>
        
        <a href="{{ route('kost.detail', ['id' => $kost->id]) }}" class="kost-name-link">
            <h3 class="kost-name">{{ $kost->nama_kost }}</h3>
        </a>
        
        <div class="kost-price">
            Rp {{ number_format($kost->harga_diskon > 0 ? $kost->harga_diskon : $kost->harga_per_bulan, 0, ',', '.') }} 
            <span>/ bln</span>
        </div>
        
        <div class="kost-address">
            <i class="fa-solid fa-map-pin"></i>
            <span>{{ Str::limit($kost->alamat, 75) }}</span>
        </div>

        <div class="kost-actions">
            @php
                // Cek status bookmark dengan lebih aman
                $isBookmarked = false;
                if(Auth::check()) {
                    $isBookmarked = \App\Models\Bookmark::where('user_id', Auth::id())
                                                        ->where('kost_id', $kost->id)
                                                        ->exists();
                }
            @endphp
            
            {{-- TOMBOL LOVE / BOOKMARK --}}
            <button type="button" class="btn-bookmark" data-id="{{ $kost->id }}" title="{{ $isBookmarked ? 'Hapus dari Favorit' : 'Simpan ke Favorit' }}">
                <i class="fa-heart {{ $isBookmarked ? 'fa-solid' : 'fa-regular' }}" style="{{ $isBookmarked ? 'color: var(--danger);' : '' }}"></i>
            </button>
            
            <a href="https://wa.me/{{ preg_replace('/^0/', '62', $kost->no_wa ?? $kost->kontak_pemilik) }}?text={{ urlencode('Halo, saya tertarik dengan kost ' . $kost->nama_kost . ' yang saya lihat di SIKOSUB. Apakah kamarnya masih ada?') }}" target="_blank" class="action-icon" title="Hubungi Pemilik">
                <i class="fa-brands fa-whatsapp"></i>
            </a>
            
            <a href="{{ !empty($kost->maps) ? $kost->maps : 'https://maps.google.com/?q='.urlencode($kost->alamat) }}" target="_blank" class="action-icon" title="Lihat di Peta">
                <i class="fa-solid fa-location-dot"></i>
            </a>
        </div>
    </div>
</div>
@empty
<div style="text-align: center; padding: 60px 20px; background: white; border-radius: 20px; border: 1px dashed var(--border-color); grid-column: 1 / -1;">
    <i class="fa-solid fa-house-circle-xmark" style="font-size: 3.5rem; color: #cbd5e1; margin-bottom: 15px;"></i>
    <h3 style="color: var(--text-main); font-weight: 800; font-size: 1.25rem; margin-bottom: 5px;">Kost Tidak Ditemukan</h3>
    <p style="color: var(--text-muted); font-size: 0.95rem; margin: 0;">Opps! Sepertinya tidak ada properti kost yang sesuai dengan kriteria filter Anda saat ini.</p>
</div>
@endforelse

@if($kosts->hasMorePages())
    <div id="pagination-info" data-next-page="{{ $kosts->currentPage() + 1 }}" style="display: none;"></div>
@endif