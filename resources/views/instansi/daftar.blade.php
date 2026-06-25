@extends('layouts.app')

@section('content')

{{-- ====================================================================
     SOLUSI PAMUNGKAS: AUTO-FETCH DATA MANDIRI
     File ini akan langsung menarik data dari database sendiri tanpa 
     bergantung pada Controller mana yang memanggilnya.
==================================================================== --}}
@php
    // 1. Deteksi otomatis kategori dari URL
    $reqKategori = request()->query('kategori') ?? request()->segment(2) ?? '';
    
    $judul = 'Instansi';
    $icon = 'fa-building';
    $textField = '';
    $imageField = '';
    $fallback = '';

    // 2. Setelan berdasarkan kategori
    if (str_contains($reqKategori, 'pendidikan')) {
        $judul = 'Instansi Pendidikan';
        $icon = 'fa-graduation-cap';
        $textField = 'filter_pendidikan';
        $imageField = 'image_pendidikan';
        $fallback = 'https://images.unsplash.com/photo-1523050854058-8df90110c9f1?auto=format&fit=crop&w=400&q=80';
    } elseif (str_contains($reqKategori, 'pemerintah') || str_contains($reqKategori, 'pemda')) {
        $judul = 'Instansi Pemerintah';
        $icon = 'fa-building-columns';
        $textField = 'filter_pemerintah';
        $imageField = 'image_pemerintah';
        $fallback = 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?auto=format&fit=crop&w=400&q=80';
    } elseif (str_contains($reqKategori, 'perusahaan')) {
        $judul = 'Instansi Perusahaan';
        $icon = 'fa-city';
        $textField = 'filter_perusahaan';
        $imageField = 'image_perusahaan';
        $fallback = 'https://images.unsplash.com/photo-1542744173-8e7e53415bb0?auto=format&fit=crop&w=400&q=80';
    }

    // 3. Tarik data dari Database
    $landing = \App\Models\Landing::first();
    $data_instansi = [];
    
    if ($landing && $textField != '') {
        $names = array_filter(array_map('trim', explode(',', $landing->$textField ?? '')));
        $images = array_map('trim', explode(',', $landing->$imageField ?? ''));
        
        foreach ($names as $idx => $name) {
            $imgName = $images[$idx] ?? '';
            $imgUrl = (!empty($imgName) && file_exists(public_path('images/filters/' . $imgName))) 
                      ? asset('images/filters/' . $imgName) 
                      : $fallback;
            
            $data_instansi[] = ['nama' => $name, 'gambar' => $imgUrl];
        }
    }
@endphp

<style>
    body { background-color: #f8f9fa; font-family: 'Outfit', sans-serif; }
    .instansi-container { max-width: 1200px; margin: 40px auto; padding: 0 20px; }
    .breadcrumb { font-size: 0.9rem; color: #6b7280; margin-bottom: 30px; display: flex; align-items: center; gap: 8px; }
    .breadcrumb a { text-decoration: none; color: inherit; font-weight: 600; }
    .breadcrumb a:hover { color: #3b82f6; }
    .breadcrumb span { color: #000; font-weight: 700; }
    .page-header { display: flex; align-items: center; gap: 15px; margin-bottom: 40px; padding-bottom: 20px; border-bottom: 2px solid #e5e7eb; }
    .page-header i { font-size: 2.5rem; color: #3b82f6; }
    .page-header h1 { font-size: 2rem; font-weight: 900; color: #000; margin: 0; text-transform: uppercase; }
    .grid-container { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 25px; }
    
    /* === STYLING KARTU === */
    .property-card { height: 250px; border-radius: 12px; overflow: hidden; position: relative; background-size: cover; background-position: center; box-shadow: 0 4px 10px rgba(0,0,0,0.05); transition: transform 0.3s; }
    .property-card:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.1); }
    .property-card .overlay { position: absolute; inset: 0; background: linear-gradient(to bottom, rgba(0,0,0,0.4) 0%, rgba(0,0,0,0.7) 100%); }
    .card-content-wrapper { position: relative; z-index: 2; height: 100%; display: flex; flex-direction: column; justify-content: space-between; padding: 15px; }
    .badge-name { background: transparent; color: white; padding: 0; font-size: 1.1rem; font-weight: 800; text-transform: uppercase; }
    .badge-name p { margin: 2px 0 0 0; font-size: 0.85rem; font-weight: 500; color: #d1d5db; }
    .bottom-actions { display: flex; justify-content: flex-end; }
    .btn-lihat-small { background: white; color: #000; padding: 8px 25px; border-radius: 25px; font-size: 0.85rem; font-weight: 700; text-decoration: none; transition: 0.2s; }
    .btn-lihat-small:hover { background: #3b82f6; color: white; }
</style>

<div class="instansi-container">
    
    <div class="breadcrumb">
        <a href="{{ route('home') }}">HOME</a> 
        <i class="fa-solid fa-chevron-right" style="font-size: 0.7rem;"></i> 
        <span>DAFTAR {{ strtoupper($judul) }}</span>
    </div>

    <div class="page-header">
        <i class="fa-solid {{ $icon }}"></i>
        <h1>{{ $judul }} DI SUBANG</h1>
    </div>

    <div class="grid-container">
        
        {{-- Looping memanggil variabel auto-fetch yang sudah kita buat di atas --}}
        @forelse($data_instansi as $item)
            <div class="property-card" style="background-image: url('{{ $item['gambar'] }}');">
                <div class="overlay"></div>
                <div class="card-content-wrapper">
                    <div class="badge-name">
                        {{ $item['nama'] }}
                        <p>SUBANG</p>
                    </div>
                    <div class="bottom-actions">
                        <a href="{{ route('kost.search', ['keyword' => $item['nama']]) }}" class="btn-lihat-small">Lihat Kost Terdekat</a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center p-4 text-muted" style="grid-column: 1 / -1; font-size: 1.1rem; font-weight: 500;">
                Belum ada data {{ strtolower($judul) }} yang ditambahkan.
            </div>
        @endforelse

    </div>

</div>
@endsection