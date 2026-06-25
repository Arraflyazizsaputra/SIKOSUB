@extends('layouts.app')

@section('content')
<style>
    /* =========================================
       MODERN GALLERY DESIGN SYSTEM
       ========================================= */
    :root {
        --primary: #1a56db;
        --primary-hover: #1e40af;
        --bg-main: #f8fafc;
        --text-main: #0f172a;
        --text-muted: #64748b;
        --border-color: #e2e8f0;
    }

    body { background-color: var(--bg-main); font-family: 'Outfit', sans-serif; }
    
    .gallery-page-container { max-width: 1200px; margin: 2rem auto; padding: 0 clamp(1rem, 3vw, 2rem); box-sizing: border-box; }
    
    /* Breadcrumb */
    .breadcrumb { font-size: 0.85rem; color: var(--text-muted); margin-bottom: 2rem; display: flex; align-items: center; gap: 8px; flex-wrap: wrap; }
    .breadcrumb i { font-size: 1rem; color: var(--text-main); }
    .breadcrumb span, .breadcrumb a { text-decoration: none; color: inherit; font-weight: 700; transition: 0.2s;}
    .breadcrumb a:hover { color: var(--primary); }

    /* Header */
    .gallery-header { margin-bottom: 2rem; text-align: center; }
    .gallery-title { font-size: clamp(1.8rem, 3vw, 2.5rem); font-weight: 900; color: var(--text-main); text-transform: uppercase; margin-bottom: 0.5rem; }
    .gallery-subtitle { font-size: 1rem; color: var(--text-muted); font-weight: 500; }

    /* Gallery Box */
    .gallery-box { background: white; border: 1px solid var(--border-color); border-radius: 1.5rem; padding: clamp(1.5rem, 3vw, 2.5rem); box-shadow: 0 10px 40px rgba(0,0,0,0.03); }
    
    /* Responsive Grid Auto-Fit */
    .image-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.5rem; margin-bottom: 2.5rem; }
    
    /* Image Item with Hover Overlay */
    .gallery-item { position: relative; border-radius: 12px; overflow: hidden; cursor: pointer; border: 1px solid var(--border-color); box-shadow: 0 4px 15px rgba(0,0,0,0.05); display: none; /* Disembunyikan dulu oleh JS */ }
    .gallery-item img { width: 100%; aspect-ratio: 4/3; object-fit: cover; transition: transform 0.5s cubic-bezier(0.4, 0, 0.2, 1); display: block; }
    .gallery-item:hover img { transform: scale(1.08); }
    
    .img-overlay { position: absolute; inset: 0; background: rgba(15, 23, 42, 0.4); opacity: 0; display: flex; justify-content: center; align-items: center; transition: 0.3s ease; backdrop-filter: blur(2px); pointer-events: none;}
    .gallery-item:hover .img-overlay { opacity: 1; }
    .img-overlay-text { background: white; color: var(--text-main); padding: 10px 20px; border-radius: 50px; font-weight: 800; font-size: 0.9rem; display: flex; align-items: center; gap: 8px; transform: translateY(20px); transition: 0.3s ease; }
    .gallery-item:hover .img-overlay-text { transform: translateY(0); }

    /* Empty State */
    .empty-gallery { grid-column: 1 / -1; text-align: center; padding: 4rem 2rem; background: #f8fafc; border: 2px dashed var(--border-color); border-radius: 16px; }
    .empty-gallery i { font-size: 3rem; color: #cbd5e1; margin-bottom: 1rem; }
    .empty-gallery p { color: var(--text-muted); font-weight: 600; font-size: 1rem; margin: 0; }

    /* Load More Button */
    .btn-load-more-green { display: flex; align-items: center; justify-content: center; gap: 10px; margin: 0 auto; background-color: white; border: 2px solid var(--primary); color: var(--primary); padding: 12px 35px; border-radius: 50px; font-weight: 800; font-size: 0.95rem; cursor: pointer; transition: all 0.3s ease; }
    .btn-load-more-green:hover { background-color: var(--primary); color: white; transform: translateY(-3px); box-shadow: 0 10px 20px rgba(26, 86, 219, 0.2); }

    /* Modern Lightbox (Glassmorphism) */
    .modal-lightbox { display: none; position: fixed; z-index: 9999; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(15, 23, 42, 0.85); backdrop-filter: blur(8px); opacity: 0; transition: opacity 0.3s ease; }
    .modal-lightbox.show { display: flex; align-items: center; justify-content: center; opacity: 1; }
    .modal-lightbox-content { max-width: 90%; max-height: 85vh; object-fit: contain; border-radius: 12px; box-shadow: 0 20px 50px rgba(0,0,0,0.5); transform: scale(0.9); transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1); }
    .modal-lightbox.show .modal-lightbox-content { transform: scale(1); }
    
    .close-lightbox { position: absolute; top: 20px; right: 30px; color: white; font-size: 40px; font-weight: 300; transition: 0.3s; cursor: pointer; width: 50px; height: 50px; background: rgba(255,255,255,0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; }
    .close-lightbox:hover { background: rgba(255,255,255,0.2); transform: rotate(90deg); }
</style>

<div class="gallery-page-container">
    <div class="breadcrumb">
        <i class="fa-solid fa-arrow-left-long"></i>
        <a href="{{ route('home') }}">HOME</a> <i class="fa-solid fa-angle-right" style="font-size:0.7rem;"></i> 
        <a href="{{ route('kost.search') }}">KOST SUBANG</a> <i class="fa-solid fa-angle-right" style="font-size:0.7rem;"></i> 
        <a href="{{ route('kost.detail', ['id' => $kost->id]) }}">DETAIL KOST</a> <i class="fa-solid fa-angle-right" style="font-size:0.7rem;"></i> 
        <span>DETAIL GAMBAR KOST</span>
    </div>

    <div class="gallery-header">
        <h1 class="gallery-title">Galeri Properti</h1>
        <p class="gallery-subtitle">Jelajahi setiap sudut {{ $kost->nama_kost }} secara lebih dekat</p>
    </div>

    <div class="gallery-box">
        <div class="image-grid" id="gallery-grid">
            
            <div class="gallery-item">
                <img class="clickable-img" src="{{ asset('images/kost/'.$kost->gambar_utama) }}" alt="{{ $kost->nama_kost }}">
                <div class="img-overlay"><div class="img-overlay-text"><i class="fa-solid fa-magnifying-glass-plus"></i> Perbesar</div></div>
            </div>
            
            @php
                // PERBAIKAN: Memastikan pembacaan data gambar yang Tahan Banting dari database
                $galleriesArray = is_string($kost->gallery_images) ? json_decode($kost->gallery_images, true) : $kost->gallery_images;
                if (!is_array($galleriesArray)) $galleriesArray = [];
            @endphp

            @if(count($galleriesArray) > 0)
                @foreach($galleriesArray as $img)
                    <div class="gallery-item">
                        <img class="clickable-img" src="{{ asset('images/kost/'.$img) }}" alt="Gallery Image">
                        <div class="img-overlay"><div class="img-overlay-text"><i class="fa-solid fa-magnifying-glass-plus"></i> Perbesar</div></div>
                    </div>
                @endforeach
            @else
                {{-- Fallback Dummy Image jika gallery kosong (bisa dihapus jika tidak mau ada dummy) --}}
                <div class="gallery-item">
                    <img class="clickable-img" src="https://images.unsplash.com/photo-1512917774080-9991f1c4c750?auto=format&fit=crop&w=800&q=80" alt="Dummy 1">
                    <div class="img-overlay"><div class="img-overlay-text"><i class="fa-solid fa-magnifying-glass-plus"></i> Perbesar</div></div>
                </div>
                <div class="gallery-item">
                    <img class="clickable-img" src="https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?auto=format&fit=crop&w=800&q=80" alt="Dummy 2">
                    <div class="img-overlay"><div class="img-overlay-text"><i class="fa-solid fa-magnifying-glass-plus"></i> Perbesar</div></div>
                </div>
                <div class="gallery-item">
                    <img class="clickable-img" src="https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=800&q=80" alt="Dummy 3">
                    <div class="img-overlay"><div class="img-overlay-text"><i class="fa-solid fa-magnifying-glass-plus"></i> Perbesar</div></div>
                </div>
                <div class="gallery-item">
                    <img class="clickable-img" src="https://images.unsplash.com/photo-1600566753190-17f0baa2a6c3?auto=format&fit=crop&w=800&q=80" alt="Dummy 4">
                    <div class="img-overlay"><div class="img-overlay-text"><i class="fa-solid fa-magnifying-glass-plus"></i> Perbesar</div></div>
                </div>
            @endif

        </div>
        
        <button class="btn-load-more-green" id="btn-load-more">
            <i class="fa-solid fa-images"></i> Muat Lebih Banyak Foto
        </button>
    </div>
</div>

<div id="imageModal" class="modal-lightbox">
  <span class="close-lightbox" id="close-lightbox"><i class="fa-solid fa-xmark"></i></span>
  <img class="modal-lightbox-content" id="expandedImg">
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        
        /* =======================================
           1. FUNGSI LOAD MORE GAMBAR (PAGINATION)
           ======================================= */
        const galleryItems = document.querySelectorAll('.gallery-item');
        const loadMoreBtn = document.getElementById('btn-load-more');
        let itemsShown = 4; // Tampilkan 4 gambar pertama

        // Sembunyikan atau tampilkan gambar saat halaman dimuat
        galleryItems.forEach((item, index) => {
            if (index < itemsShown) {
                item.style.display = 'block';
            }
        });

        // Sembunyikan tombol jika total gambar <= 4
        if (galleryItems.length <= itemsShown) {
            loadMoreBtn.style.display = 'none';
        }

        // Aksi ketika tombol "Muat Lebih Banyak" diklik
        loadMoreBtn.addEventListener('click', function() {
            let nextItems = itemsShown + 4; // Tambah 4 gambar lagi
            
            galleryItems.forEach((item, index) => {
                if (index < nextItems) {
                    item.style.display = 'block';
                }
            });
            
            itemsShown = nextItems;
            
            // Hilangkan tombol jika semua gambar sudah tampil
            if (itemsShown >= galleryItems.length) {
                loadMoreBtn.style.display = 'none';
            }
        });

        /* =======================================
           2. FUNGSI LIGHTBOX (POPUP PERBESAR GAMBAR)
           ======================================= */
        const modal = document.getElementById("imageModal");
        const expandedImg = document.getElementById("expandedImg");
        const closeBtn = document.getElementById("close-lightbox");

        // Buka modal saat kotak gambar diklik
        galleryItems.forEach(function(item) {
            item.addEventListener('click', function() {
                const img = this.querySelector('.clickable-img');
                expandedImg.src = img.src;
                modal.classList.add("show");
            });
        });

        // Tutup modal saat tombol silang diklik
        closeBtn.onclick = function() { 
            modal.classList.remove("show"); 
            setTimeout(() => { expandedImg.src = ''; }, 300); // Bersihkan src setelah animasi selesai
        }

        // Tutup modal saat area gelap di luar gambar diklik
        modal.onclick = function(event) {
            if (event.target === modal) { 
                modal.classList.remove("show"); 
                setTimeout(() => { expandedImg.src = ''; }, 300);
            }
        }
    });
</script>
@endsection