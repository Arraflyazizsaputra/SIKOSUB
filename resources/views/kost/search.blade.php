@extends('layouts.app')

@section('content')
<style>
    /* =========================================
       MODERN DESIGN SYSTEM - PENCARIAN KOST
       ========================================= */
    :root {
        --primary: #1a56db;
        --primary-hover: #1e40af;
        --success: #22c55e;
        --warning: #facc15;
        --danger: #ef4444;
        --text-main: #111827;
        --text-muted: #6b7280;
        --bg-main: #f8fafc;
        --border-color: #e5e7eb;
    }

    body { background-color: var(--bg-main); font-family: 'Outfit', sans-serif; }
    
    .search-page-container { display: flex; flex-wrap: wrap; max-width: 1300px; margin: 30px auto; gap: 30px; padding: 0 clamp(15px, 3vw, 30px); }

    .breadcrumb { font-size: 0.85rem; color: var(--text-muted); margin-bottom: 5px; display: flex; align-items: center; gap: 8px; flex-wrap: wrap; }
    .breadcrumb i { font-size: 1rem; color: var(--text-main); }
    .breadcrumb span, .breadcrumb a { text-decoration: none; color: inherit; font-weight: 700; transition: 0.2s;}
    .breadcrumb a:hover { color: var(--primary); }

    /* =========================================
       1. SIDEBAR FILTER
       ========================================= */
    .filter-sidebar { 
        flex: 0 0 320px; background: white; border: 1px solid var(--border-color); 
        border-radius: 20px; padding: 30px; height: fit-content; 
        box-shadow: 0 10px 30px rgba(0,0,0,0.03); position: sticky; top: 30px;
    }
    
    .filter-title { font-size: 1.25rem; font-weight: 900; color: var(--text-main); margin-bottom: 25px; text-transform: uppercase; letter-spacing: 1px; display: flex; align-items: center; gap: 10px; }
    .filter-title i { color: var(--primary); }
    
    .filter-group { margin-bottom: 30px; padding-bottom: 20px; border-bottom: 1px dashed var(--border-color); }
    .filter-group:last-child { border-bottom: none; margin-bottom: 0; padding-bottom: 0; }
    
    .filter-group h6 { font-size: 0.85rem; font-weight: 800; margin-bottom: 15px; color: var(--text-main); text-transform: uppercase; display: flex; justify-content: space-between; align-items: center;}
    
    .range-slider { width: 100%; margin-bottom: 10px; accent-color: var(--primary); cursor: pointer; height: 6px; border-radius: 5px; background: #e2e8f0; outline: none; -webkit-appearance: none; }
    .range-slider::-webkit-slider-thumb { -webkit-appearance: none; appearance: none; width: 20px; height: 20px; border-radius: 50%; background: var(--primary); cursor: pointer; border: 3px solid white; box-shadow: 0 2px 5px rgba(0,0,0,0.2); transition: 0.2s; }
    .range-slider::-webkit-slider-thumb:hover { transform: scale(1.2); }
    
    .slider-labels { display: flex; justify-content: space-between; font-size: 0.75rem; font-weight: 600; color: var(--text-muted); }

    .form-check { margin-bottom: 12px; display: flex; align-items: center; gap: 12px; cursor: pointer; transition: 0.2s; padding: 5px; border-radius: 8px; }
    .form-check:hover { background: #f1f5f9; }
    .form-check-input { width: 18px; height: 18px; accent-color: var(--primary); cursor: pointer; }
    .form-check-label { font-size: 0.95rem; color: #4b5563; font-weight: 500; cursor: pointer; flex-grow: 1; }

    .sidebar-map { width: 100%; height: 200px; border-radius: 12px; overflow: hidden; border: 1px solid var(--border-color); }
    .sidebar-map iframe { width: 100%; height: 100%; border: none; }

    /* =========================================
       2. RESULTS AREA & KOST CARDS
       ========================================= */
    .results-area { flex: 1; display: flex; flex-direction: column; gap: 20px; min-width: 0; }
    
    .search-status { background: white; padding: 15px 25px; border-radius: 12px; font-size: 0.95rem; color: var(--text-muted); border: 1px solid var(--border-color); box-shadow: 0 4px 10px rgba(0,0,0,0.02); display: flex; align-items: center; gap: 10px;}
    .search-status strong { color: var(--text-main); font-weight: 800; }
    
    .kost-list-card { display: flex; background: #ffffff; border: 1px solid var(--border-color); border-radius: 20px; overflow: hidden; position: relative; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); box-shadow: 0 4px 15px rgba(0,0,0,0.02); }
    .kost-list-card:hover { box-shadow: 0 15px 35px rgba(26, 86, 219, 0.08); transform: translateY(-5px); border-color: #bfdbfe; }
    
    .kost-list-img { width: 300px; min-height: 220px; height: 100%; object-fit: cover; transition: transform 0.5s ease; }
    .kost-list-card:hover .kost-list-img { transform: scale(1.05); }
    .img-wrapper { overflow: hidden; width: 300px; flex-shrink: 0; position: relative;}
    
    .kost-list-content { padding: 25px; flex-grow: 1; display: flex; flex-direction: column; justify-content: center; position: relative; background: white; z-index: 2;}
    
    .badge-container { position: absolute; top: 15px; left: 15px; display: flex; gap: 8px; z-index: 5;}
    .badge-type { background-color: var(--warning); color: #854d0e; font-weight: 900; font-size: 0.7rem; padding: 6px 12px; border-radius: 8px; letter-spacing: 0.5px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);}
    .badge-rating { background-color: var(--danger); color: #fff; font-weight: 800; font-size: 0.75rem; padding: 6px 10px; border-radius: 8px; display: flex; align-items: center; gap: 4px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);}
    
    .kost-name-link { text-decoration: none; color: inherit; display: inline-block; outline: none; }
    .kost-name { font-size: 1.3rem; font-weight: 900; color: var(--text-main); text-transform: uppercase; margin-bottom: 5px; transition: 0.2s; }
    .kost-name-link:hover .kost-name { color: var(--primary); }
    
    .kost-price { font-size: 1.6rem; font-weight: 900; color: var(--primary); margin-bottom: 10px; }
    .kost-price span { font-size: 0.9rem; color: var(--text-muted); font-weight: 600; }
    
    .kost-address { font-size: 0.9rem; color: var(--text-muted); max-width: 85%; line-height: 1.5; display: flex; align-items: flex-start; gap: 8px;}
    .kost-address i { color: var(--danger); margin-top: 3px; }
    
    .kost-actions { position: absolute; bottom: 25px; right: 25px; display: flex; gap: 12px; z-index: 5; }
    .action-icon, .btn-bookmark { 
        width: 42px; height: 42px; border-radius: 50%; background: #f1f5f9; display: flex; align-items: center; justify-content: center; 
        color: var(--text-muted); font-size: 1.2rem; cursor: pointer; transition: 0.3s; text-decoration: none; border: none; outline: none;
    }
    .action-icon:hover { background: #dbeafe; color: var(--primary); transform: translateY(-3px); }
    .btn-bookmark:hover { background: #fee2e2; color: var(--danger); transform: translateY(-3px); }
    .btn-bookmark .fa-solid.fa-heart { color: var(--danger); } 

    .btn-load-more { 
        display: flex; align-items: center; justify-content: center; gap: 10px; width: 100%; max-width: 300px; 
        margin: 20px auto 40px; padding: 15px; background: white; border: 2px solid var(--border-color); 
        border-radius: 50px; color: var(--text-main); font-size: 0.95rem; font-weight: 800; cursor: pointer; transition: 0.3s; 
    }
    .btn-load-more:hover { background: #f1f5f9; border-color: var(--primary); color: var(--primary); transform: translateY(-2px); box-shadow: 0 5px 15px rgba(26, 86, 219, 0.1); }
    .btn-load-more i { color: var(--success); font-size: 1.2rem; transition: 0.3s;}

    @media (max-width: 992px) {
        .search-page-container { flex-direction: column; }
        .filter-sidebar { flex: none; width: 100%; position: static; }
        .kost-list-card { flex-direction: column; }
        .img-wrapper { width: 100%; height: 220px; }
        .kost-list-img { width: 100%; height: 100%; }
        .kost-address { max-width: 100%; margin-bottom: 40px; }
    }
</style>

<div class="search-page-container">
    
    <aside class="filter-sidebar">
        <h2 class="filter-title"><i class="fa-solid fa-sliders"></i> FILTER PENCARIAN</h2>

        <form id="filter-form">
            <div class="filter-group">
                <h6>JARAK MAKSIMAL <span id="jarak-val" style="color: var(--primary); font-size: 1rem;">5 Km</span></h6>
                <input type="range" class="range-slider filter-input" id="filter-jarak" name="jarak" min="1" max="15" value="5">
                <div class="slider-labels">
                    <span>1km</span>
                    <span>15km</span>
                </div>
            </div>

            <div class="filter-group">
                <h6>HARGA MAKSIMAL <span id="harga-val" style="color: var(--primary); font-size: 1rem;">Rp 3.000.000</span></h6>
                <input type="range" class="range-slider filter-input" id="filter-harga" name="harga" min="500000" max="5000000" step="100000" value="3000000">
                <div class="slider-labels">
                    <span>500Rb</span>
                    <span>5Jt</span>
                </div>
            </div>

            <div class="filter-group">
                <h6>FASILITAS KAMAR & UMUM</h6>
                <div class="form-check">
                    <input class="form-check-input filter-input" type="checkbox" name="fasilitas[]" value="AC" id="fac_ac">
                    <label class="form-check-label" for="fac_ac">Kamar Ber-AC</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input filter-input" type="checkbox" name="fasilitas[]" value="WC Dalam" id="fac_wc">
                    <label class="form-check-label" for="fac_wc">Kamar Mandi Dalam</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input filter-input" type="checkbox" name="fasilitas[]" value="Kasur" id="fac_kasur">
                    <label class="form-check-label" for="fac_kasur">Termasuk Kasur & Lemari</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input filter-input" type="checkbox" name="fasilitas[]" value="WIFI" id="fac_wifi">
                    <label class="form-check-label" for="fac_wifi">Koneksi WiFi</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input filter-input" type="checkbox" name="fasilitas[]" value="Dapur" id="fac_dapur">
                    <label class="form-check-label" for="fac_dapur">Dapur Umum</label>
                </div>
            </div>
        </form>

        <div class="filter-group" style="border:none;">
            <h6><i class="fa-solid fa-map-location-dot"></i> PUSAT KOTA SUBANG</h6>
            <div class="sidebar-map">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d126786.13645391515!2d107.67104035!3d-6.5562705!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e693b749d68dc3b%3A0xc6e4ffcf1a1c3bb8!2sSubang%2C%20Kec.%20Subang%2C%20Kabupaten%20Subang%2C%20Jawa%20Barat!5e0!3m2!1sid!2sid!4v1700000000000!5m2!1sid!2sid" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
        </div>
    </aside>

    <main class="results-area">
        
        <div class="breadcrumb">
            <i class="fa-solid fa-arrow-left-long"></i>
            <a href="{{ route('home') }}">KEMBALI KE BERANDA</a>
        </div>
        
        @if(request('keyword') || request('kategori'))
        <div class="search-status">
            <i class="fa-solid fa-magnifying-glass"></i>
            <div>
                Menampilkan hasil untuk: 
                <strong>"{{ request('keyword') }}"</strong> 
                @if(request('kategori') && request('kategori') != 'semua') 
                    pada kategori <strong>{{ ucfirst(request('kategori')) }}</strong>
                @endif
            </div>
        </div>
        @endif

        <div id="kost-results-container" style="display: flex; flex-direction: column; gap: 20px;">
            @include('partials.kost-list', ['kosts' => $kosts])
        </div>

        <button class="btn-load-more" id="btn-load-more" style="display: {{ $kosts->hasMorePages() ? 'flex' : 'none' }};">
            <i class="fa-solid fa-circle-plus"></i> Tampilkan Lebih Banyak
        </button>

    </main>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        
        const sliderJarak = document.getElementById('filter-jarak');
        const labelJarak = document.getElementById('jarak-val');
        if(sliderJarak) {
            sliderJarak.addEventListener('input', (e) => { labelJarak.textContent = e.target.value + ' Km'; });
        }

        const sliderHarga = document.getElementById('filter-harga');
        const labelHarga = document.getElementById('harga-val');
        if(sliderHarga) {
            sliderHarga.addEventListener('input', (e) => { 
                labelHarga.textContent = 'Rp ' + parseInt(e.target.value).toLocaleString('id-ID'); 
            });
        }

        const filterInputs = document.querySelectorAll('.filter-input');
        const resultsContainer = document.getElementById('kost-results-container');
        const btnLoadMore = document.getElementById('btn-load-more');
        let currentPage = 1;

        function fetchKosts(page = 1, append = false) {
            const formData = new FormData(document.getElementById('filter-form'));
            formData.append('page', page);
            
            const urlParams = new URLSearchParams(window.location.search);
            if(urlParams.has('keyword')) formData.append('keyword', urlParams.get('keyword'));
            if(urlParams.has('kategori')) formData.append('kategori', urlParams.get('kategori'));

            if(!append) resultsContainer.style.opacity = '0.5';

            fetch("{{ route('kost.filter') }}", {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: formData
            })
            .then(response => response.text())
            .then(html => {
                resultsContainer.style.opacity = '1';
                
                if (append) {
                    resultsContainer.insertAdjacentHTML('beforeend', html);
                } else {
                    resultsContainer.innerHTML = html;
                }
                
                const paginationInfo = resultsContainer.querySelector('#pagination-info');
                if (paginationInfo) {
                    btnLoadMore.style.display = 'flex';
                    currentPage = parseInt(paginationInfo.getAttribute('data-next-page'));
                    paginationInfo.remove(); 
                } else {
                    btnLoadMore.style.display = 'none';
                }
            })
            .catch(error => {
                console.error('Error fetching data:', error);
                resultsContainer.style.opacity = '1';
            });
        }

        filterInputs.forEach(input => {
            input.addEventListener('change', () => {
                currentPage = 1; 
                fetchKosts(1, false); 
            });
        });

        if(btnLoadMore) {
            btnLoadMore.addEventListener('click', function() {
                const icon = this.querySelector('i');
                icon.className = 'fa-solid fa-spinner fa-spin';
                fetchKosts(currentPage, true); 
                setTimeout(() => { icon.className = 'fa-solid fa-circle-plus'; }, 500);
            });
        }

        // =========================================================================
        // 4. FUNGSI BOOKMARK AJAX YANG DIPERBAIKI (Penanganan Error 403)
        // =========================================================================
        document.addEventListener('click', function(e) {
            const btn = e.target.closest('.btn-bookmark');
            
            if (btn) {
                e.preventDefault();
                const kostId = btn.getAttribute('data-id');
                const icon = btn.querySelector('i');
                
                // Efek denyut
                icon.style.transform = 'scale(1.3)';
                setTimeout(() => { icon.style.transform = 'scale(1)'; }, 200);

                fetch("{{ route('kost.bookmark') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json', 
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ kost_id: kostId })
                })
                .then(async response => {
                    if(response.status === 401) {
                        alert('Silakan login terlebih dahulu untuk menyimpan kost ke favorit.');
                        window.location.href = "{{ route('login') }}";
                        return null;
                    }

                    // TANGKAPAN KHUSUS JIKA AKUN YANG MENGKLIK ADALAH ADMIN/MITRA (403 Forbidden)
                    if(response.status === 403) {
                        const data = await response.json();
                        alert(data.message); // Menampilkan pesan rapi: "Fitur Favorit HANYA untuk Pencari Kost..."
                        return null;
                    }
                    
                    const data = await response.json();
                    
                    if(!response.ok) {
                        alert("Terjadi Kesalahan Sistem:\n\n" + data.message);
                        return null;
                    }
                    
                    return data;
                })
                .then(data => {
                    if (data && data.status === 'added') {
                        icon.classList.remove('fa-regular');
                        icon.classList.add('fa-solid');
                        icon.style.color = 'var(--danger)'; 
                    } else if (data && data.status === 'removed') {
                        icon.classList.remove('fa-solid');
                        icon.classList.add('fa-regular');
                        icon.style.color = ''; 
                    }
                })
                .catch(error => {
                    console.error('Error Eksekusi Bookmark:', error);
                });
            }
        });

    });
</script>
@endsection