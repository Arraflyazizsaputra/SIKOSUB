@extends('layouts.admin')

@section('content')

{{-- MENGAMBIL DATA LANGSUNG DARI DATABASE --}}
@php
    use App\Models\Kost;
    use Illuminate\Support\Str;

    $totalKost = Kost::count();
    $kostPutra = Kost::where('tipe_kost', 'putra')->count();
    $kostPutri = Kost::where('tipe_kost', 'putri')->count();
    $kostCampur = Kost::where('tipe_kost', 'campur')->count();

    $kostTerfavorit = Kost::orderByDesc('rating')->first();

    $wilayahTerbanyak = Kost::select('kategori_wilayah')
        ->groupBy('kategori_wilayah')
        ->orderByRaw('COUNT(*) DESC')
        ->first();

    $kostDiskon = Kost::where('harga_diskon', '>', 0)->take(5)->get();

    $wilayahTerdaftar = Kost::whereNotNull('detail_wilayah')
        ->select('detail_wilayah')
        ->distinct()
        ->take(6)
        ->get();
@endphp

<style>
    /* ========================================================
       CSS TAMBAHAN UNTUK SIDEBAR LOGO MENJADI PUTIH ELEGAN
       ======================================================== */
    .main-sidebar .brand-link, 
    .sidebar-brand, 
    .app-brand, 
    aside .logo,
    .sidebar-header {
        background-color: #ffffff !important; 
        border-bottom: 1px solid #e2e8f0 !important; 
        box-shadow: 0 4px 15px -3px rgba(0, 0, 0, 0.05) !important; 
        padding: 15px 20px !important; 
    }
    
    .main-sidebar .brand-link *, 
    .sidebar-brand *, 
    .app-brand *, 
    aside .logo *,
    .sidebar-header * {
        color: #1a56db !important;
        font-weight: 900 !important;
    }

    /* Global Dashboard Styling */
    .dashboard-header { margin-bottom: 30px; }
    .dashboard-header h1 { font-size: 28px; color: #111; font-weight: 800; margin-bottom: 4px; }
    .dashboard-header p { color: #6b7280; font-size: 14px; font-weight: 500; letter-spacing: 1px; }

    /* Stats Grid Utama */
    .stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 20px; }
    
    .stat-card { 
        background: white; 
        padding: 24px; 
        border-radius: 16px; 
        box-shadow: 0 4px 20px rgba(0,0,0,0.03); 
        position: relative; 
        overflow: hidden; 
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border: 1px solid #f3f4f6;
    }
    .stat-card:hover { transform: translateY(-5px); box-shadow: 0 10px 25px rgba(0,102,255,0.1); }
    .stat-card h3 { font-size: 28px; font-weight: 800; color: #111; margin-bottom: 4px; z-index: 2; position: relative; }
    .stat-card p { font-size: 13px; color: #6b7280; font-weight: 600; margin-bottom: 12px; z-index: 2; position: relative; }
    
    .trend-badge { 
        display: inline-flex; align-items: center; gap: 4px; 
        padding: 4px 10px; border-radius: 50px; font-size: 11px; font-weight: 700; 
        background: #dcfce7; color: #166534; z-index: 2; position: relative;
    }
    .trend-badge.warning { background: #fef3c7; color: #d97706; }
    .trend-badge.info { background: #e0e7ff; color: #4338ca; }

    .stat-bg-icon { position: absolute; right: -10px; bottom: -10px; font-size: 80px; color: #f3f4f6; z-index: 1; transition: 0.3s; }
    .stat-card:hover .stat-bg-icon { transform: scale(1.1) rotate(-10deg); color: #eff6ff; }

    /* Middle Layout */
    .middle-grid { display: grid; grid-template-columns: 2fr 1fr; gap: 20px; margin-bottom: 20px; }
    .chart-box { background: white; padding: 25px; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.03); border: 1px solid #f3f4f6; height: 360px; display: flex; flex-direction: column; }
    .control-box { background: white; padding: 25px; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.03); border: 1px solid #f3f4f6; }
    
    /* Promo Card & Wilayah */
    .bottom-grid { display: grid; grid-template-columns: 2fr 1fr; gap: 20px; margin-bottom: 40px;}
    .promo-card { 
        background: linear-gradient(135deg, #0066FF 0%, #4a00e0 100%); 
        color: white; padding: 30px; border-radius: 16px; position: relative; overflow: hidden;
        box-shadow: 0 10px 30px rgba(0,102,255,0.2);
    }
    .promo-card h3 { font-size: 20px; margin-bottom: 20px; font-weight: 800; display: flex; align-items: center; gap: 10px; }
    .wilayah-card { background: white; padding: 25px; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.03); border: 1px solid #f3f4f6; }
    
    .nav-link-list { list-style: none; padding: 0; margin: 0; }
    .nav-link-list li { border-bottom: 1px dashed #e5e7eb; transition: 0.2s; }
    .nav-link-list li:last-child { border-bottom: none; }
    .nav-link-list a, .wilayah-item { 
        text-decoration: none; color: #4b5563; display: flex; align-items: center; gap: 12px; 
        padding: 14px 10px; font-size: 13px; font-weight: 600; transition: 0.3s; border-radius: 8px;
    }
    .nav-link-list a:hover { color: #0066FF; background: #eff6ff; padding-left: 15px; }
    .wilayah-item { cursor: default; }
    
    .discount-item {
        background: rgba(255,255,255,0.1); backdrop-filter: blur(10px);
        padding: 12px 20px; border-radius: 12px; margin-bottom: 10px;
        display: flex; justify-content: space-between; align-items: center;
        border: 1px solid rgba(255,255,255,0.2); transition: 0.3s;
    }
    .discount-item:hover { background: rgba(255,255,255,0.2); transform: translateX(5px); }
    .discount-percent { background: #FFD700; color: #111; padding: 4px 12px; border-radius: 50px; font-weight: 800; font-size: 12px; }
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="dashboard-header">
    <h1>Dashboard Analitik</h1>
    <p>SIKOSUB SUPERADMIN PANEL</p>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <i class="fa-solid fa-building stat-bg-icon"></i>
        <h3>{{ $totalKost }}</h3>
        <p>Total Seluruh Kost</p>
        <span class="trend-badge"><i class="fa-solid fa-bolt"></i> Realtime</span>
    </div>
    <div class="stat-card">
        <i class="fa-solid fa-person stat-bg-icon"></i>
        <h3>{{ $kostPutra }}</h3>
        <p>Jumlah Kost Putra</p>
        <span class="trend-badge info"><i class="fa-solid fa-chart-line"></i> Kategori</span>
    </div>
    <div class="stat-card">
        <i class="fa-solid fa-person-dress stat-bg-icon"></i>
        <h3>{{ $kostPutri }}</h3>
        <p>Jumlah Kost Putri</p>
        <span class="trend-badge info"><i class="fa-solid fa-chart-line"></i> Kategori</span>
    </div>
    <div class="stat-card">
        <i class="fa-solid fa-people-group stat-bg-icon"></i>
        <h3>{{ $kostCampur }}</h3>
        <p>Jumlah Kost Campur</p>
        <span class="trend-badge info"><i class="fa-solid fa-chart-line"></i> Kategori</span>
    </div>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <i class="fa-solid fa-star stat-bg-icon"></i>
        <h3 style="font-size: 20px; line-height: 1.3;">{{ $kostTerfavorit ? Str::limit($kostTerfavorit->nama_kost, 20) : 'Belum Ada' }}</h3>
        <p>Kost Rating Tertinggi</p>
        <span class="trend-badge warning"><i class="fa-solid fa-star"></i> {{ $kostTerfavorit ? $kostTerfavorit->rating : '0.0' }}</span>
    </div>
    <div class="stat-card">
        <i class="fa-solid fa-map-location-dot stat-bg-icon"></i>
        <h3 style="font-size: 20px; text-transform: uppercase;">{{ $wilayahTerbanyak ? $wilayahTerbanyak->kategori_wilayah : 'Belum Ada' }}</h3>
        <p>Wilayah Terbanyak</p>
        <span class="trend-badge"><i class="fa-solid fa-fire"></i> Populer</span>
    </div>
    <div class="stat-card">
        <i class="fa-solid fa-tags stat-bg-icon"></i>
        <h3>{{ $kostDiskon->count() }}</h3>
        <p>Kost Sedang Diskon</p>
        <span class="trend-badge warning"><i class="fa-solid fa-clock"></i> Bulan Ini</span>
    </div>
    <div class="stat-card">
        <i class="fa-solid fa-location-crosshairs stat-bg-icon"></i>
        <h3>{{ $wilayahTerdaftar->count() }}</h3>
        <p>Total Titik Wilayah</p>
        <span class="trend-badge info"><i class="fa-solid fa-check"></i> Terdaftar</span>
    </div>
</div>

<div class="middle-grid">
    <div class="chart-box">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h4 style="font-size: 16px; font-weight: 800; color: #111;">Distribusi Tipe Kost</h4>
            <span style="font-size: 11px; background: #f3f4f6; padding: 4px 10px; border-radius: 5px; font-weight: 600;">Data Langsung</span>
        </div>
        <div style="flex-grow: 1; position: relative;">
            <canvas id="kostChart"></canvas>
        </div>
    </div>

    <div class="control-box">
        <h4 style="font-size: 16px; font-weight: 800; color: #111; margin-bottom: 15px; display: flex; align-items: center; gap: 8px;">
            <i class="fa-solid fa-sliders" style="color: #0066FF;"></i> Jalan Pintas
        </h4>
        <ul class="nav-link-list">
            <li><a href="{{ route('tambah.kost') }}"><i class="fa-solid fa-square-plus" style="color: #10B981;"></i> TAMBAH KOST BARU</a></li>
            <li><a href="{{ route('daftar.kost') }}"><i class="fa-solid fa-list" style="color: #3b82f6;"></i> LIHAT DAFTAR KOST</a></li>
            <li><a href="{{ route('kost.manage-index') }}"><i class="fa-solid fa-pen-to-square" style="color: #f59e0b;"></i> KELOLA DETAIL KOST</a></li>
            <li><a href="{{ route('landing.manage') }}"><i class="fa-solid fa-display" style="color: #8b5cf6;"></i> KELOLA LANDING PAGE</a></li>
            <li><a href="{{ route('review.manage') }}"><i class="fa-solid fa-star" style="color: #ef4444;"></i> DAFTAR REVIEW KOST</a></li>
        </ul>
    </div>
</div>

<div class="bottom-grid">
    <div class="promo-card">
        <i class="fa-solid fa-percent" style="position: absolute; right: -20px; top: -20px; font-size: 150px; opacity: 0.1; transform: rotate(15deg);"></i>
        <h3><i class="fa-solid fa-bullhorn"></i> Kost yang Sedang Diskon</h3>
        <div style="position: relative; z-index: 2;">
            @forelse($kostDiskon as $item)
                @php
                    $persenDiskon = round((($item->harga_per_bulan - $item->harga_diskon) / $item->harga_per_bulan) * 100);
                @endphp
                <div class="discount-item">
                    <div>
                        <div style="font-weight: 700; font-size: 14px;">{{ Str::limit($item->nama_kost, 35) }}</div>
                        <div style="font-size: 11px; opacity: 0.8; margin-top: 3px;">
                            <span style="text-decoration: line-through;">Rp {{ number_format($item->harga_per_bulan, 0, ',', '.') }}</span>
                            <i class="fa-solid fa-arrow-right" style="margin: 0 5px; font-size: 10px;"></i>
                            <span style="font-weight: 600;">Rp {{ number_format($item->harga_diskon, 0, ',', '.') }}</span>
                        </div>
                    </div>
                    <div class="discount-percent">{{ $persenDiskon }}% OFF</div>
                </div>
            @empty
                <div style="background: rgba(255,255,255,0.1); padding: 20px; border-radius: 12px; text-align: center; border: 1px dashed rgba(255,255,255,0.3);">
                    <i class="fa-solid fa-box-open" style="font-size: 24px; margin-bottom: 10px; opacity: 0.8;"></i>
                    <p style="font-weight: 600; font-size: 13px;">Belum ada kost yang memberikan diskon saat ini.</p>
                </div>
            @endforelse
        </div>
    </div>
    
    <div class="wilayah-card">
        <h4 style="font-size: 16px; font-weight: 800; color: #111; margin-bottom: 15px; display: flex; align-items: center; gap: 8px;">
            <i class="fa-solid fa-map-pin" style="color: #ef4444;"></i> Titik Wilayah
        </h4>
        <ul class="nav-link-list">
            @forelse($wilayahTerdaftar as $wilayah)
                <li>
                    <div class="wilayah-item">
                        <i class="fa-solid fa-location-dot" style="color: #9ca3af;"></i> 
                        {{ $wilayah->detail_wilayah }}
                    </div>
                </li>
            @empty
                <li>
                    <div class="wilayah-item" style="color: #9ca3af;">Belum ada wilayah spesifik terdaftar.</div>
                </li>
            @endforelse
        </ul>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const ctx = document.getElementById('kostChart').getContext('2d');
        
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Kost Putra', 'Kost Putri', 'Kost Campur'],
                datasets: [{
                    label: 'Jumlah Terdaftar',
                    data: [{{ $kostPutra }}, {{ $kostPutri }}, {{ $kostCampur }}],
                    backgroundColor: [
                        'rgba(0, 102, 255, 0.8)',
                        'rgba(236, 72, 153, 0.8)',
                        'rgba(16, 185, 129, 0.8)'
                    ],
                    borderColor: ['#0066FF', '#ec4899', '#10b981'],
                    borderWidth: 1,
                    borderRadius: 8,
                    barThickness: 40
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, grid: { color: '#f3f4f6', drawBorder: false }, ticks: { precision: 0 } },
                    x: { grid: { display: false } }
                }
            }
        });
    });
</script>

@endsection