@extends('layouts.admin')

@section('content')
<style>
    .content-wrapper { padding: 40px; max-width: 1200px; margin: 0 auto; font-family: 'Outfit', sans-serif; }
    .page-header { margin-bottom: 30px; display: flex; justify-content: space-between; align-items: flex-end; }
    .page-title { font-size: 28px; font-weight: 800; color: #111; margin: 0 0 5px 0; }
    .page-subtitle { font-size: 13px; color: #6b7280; font-weight: 600; text-transform: uppercase; letter-spacing: 1px;}
    
    .kost-card { 
        background: white; border: 1px solid #f3f4f6; border-radius: 16px; padding: 20px; 
        margin-bottom: 20px; display: flex; align-items: stretch; 
        box-shadow: 0 4px 15px rgba(0,0,0,0.02); transition: all 0.3s ease; 
    }
    .kost-card:hover { transform: translateY(-5px); box-shadow: 0 10px 30px rgba(0,102,255,0.08); border-color: #dbeafe; }
    
    .kost-img-wrapper { flex-shrink: 0; margin-right: 25px; display: flex; align-items: center; }
    .kost-img { width: 240px; height: 160px; border-radius: 12px; object-fit: cover; box-shadow: 0 4px 10px rgba(0,0,0,0.05); }
    
    .kost-details { flex-grow: 1; position: relative; display: flex; flex-direction: column; justify-content: center; padding-right: 20px; }
    
    .badge-container { position: absolute; top: -5px; right: 0; display: flex; }
    .badge-tipe { background: #fef08a; color: #854d0e; padding: 5px 15px; font-size: 11px; font-weight: 800; border-radius: 6px 0 0 6px; text-transform: uppercase; }
    .badge-rating { background: #ef4444; color: white; padding: 5px 12px; font-size: 11px; font-weight: 800; border-radius: 0 6px 6px 0; }
    
    .kost-title { font-size: 18px; font-weight: 800; color: #111; margin-bottom: 8px; text-transform: uppercase; }
    .kost-price { font-size: 22px; font-weight: 800; color: #0066FF; margin-bottom: 4px; }
    
    .discount-row { display: flex; align-items: center; gap: 10px; margin-bottom: 12px; }
    .txt-diskon { font-size: 11px; background: #fee2e2; color: #ef4444; padding: 2px 8px; border-radius: 4px; font-weight: 700; }
    .txt-coret { font-size: 11px; color: #9ca3af; text-decoration: line-through; font-weight: 600; }
    
    .kost-address { font-size: 13px; color: #6b7280; line-height: 1.5; margin-bottom: 15px; }
    .kost-address strong { color: #374151; font-weight: 700; display: block; margin-bottom: 2px; }
    
    .action-icons { display: flex; gap: 10px; }
    .icon-circle { width: 32px; height: 32px; background: #f3f4f6; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #4b5563; text-decoration: none; transition: 0.2s; }
    .icon-circle:hover.icon-wa { background: #dcfce7; color: #16a34a; }
    .icon-circle:hover.icon-map { background: #dbeafe; color: #2563eb; }
    
    .actions-col { width: 180px; border-left: 1px dashed #e5e7eb; display: flex; flex-direction: column; align-items: center; justify-content: center; padding-left: 25px; flex-shrink: 0; }
    .btn-action { width: 100%; padding: 10px 15px; border-radius: 8px; font-weight: 700; font-size: 13px; cursor: pointer; text-align: center; transition: 0.2s; text-decoration: none; display: flex; align-items: center; justify-content: center; gap: 8px; margin-bottom: 10px; border: none;}
    .btn-edit { background: #eff6ff; color: #0066FF; }
    .btn-edit:hover { background: #0066FF; color: white; }
    .btn-delete { background: #fee2e2; color: #ef4444; }
    .btn-delete:hover { background: #ef4444; color: white; }
    
    /* Alert Success */
    .alert-success { background: #ecfdf5; border-left: 4px solid #10b981; color: #065f46; padding: 15px 20px; border-radius: 8px; margin-bottom: 30px; font-weight: 600; display: flex; align-items: center; gap: 10px; }
</style>

<div class="content-wrapper">
    @if(session('success'))
        <div class="alert-success">
            <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
        </div>
    @endif

    <div class="page-header">
        <div>
            <h1 class="page-title">Daftar Kost</h1>
            <span class="page-subtitle">Manajemen seluruh properti kost yang terdaftar</span>
        </div>
        <a href="{{ route('tambah.kost') }}" class="btn-action" style="background: #0066FF; color: white; width: auto; margin: 0; padding: 12px 25px;"><i class="fa-solid fa-plus"></i> Tambah Kost</a>
    </div>

    @forelse($kosts as $kost)
    <div class="kost-card">
        <div class="kost-img-wrapper">
            <img src="{{ asset('images/kost/'.$kost->gambar_utama) }}" class="kost-img" alt="Kost">
        </div>
        
        <div class="kost-details">
            <div class="badge-container">
                <span class="badge-tipe">{{ $kost->tipe_kost }}</span>
                <span class="badge-rating"><i class="fa-solid fa-star" style="font-size:10px;"></i> {{ number_format($kost->rating ?? 5.0, 1) }}</span>
            </div>
            
            <div class="kost-title">{{ $kost->nama_kost }}</div>
            
            <div class="kost-price">
                Rp {{ number_format($kost->harga_diskon > 0 ? $kost->harga_diskon : $kost->harga_per_bulan, 0, ',', '.') }} <span style="font-size: 13px; color: #6b7280;">/ bln</span>
            </div>
            
            @if($kost->harga_diskon > 0)
            <div class="discount-row">
                <span class="txt-diskon">Hemat Rp {{ number_format($kost->harga_per_bulan - $kost->harga_diskon, 0, ',', '.') }}</span>
                <span class="txt-coret">Rp {{ number_format($kost->harga_per_bulan, 0, ',', '.') }}</span>
            </div>
            @else
            <div class="discount-row" style="visibility: hidden;"><span class="txt-diskon">Space</span></div>
            @endif
            
            <div class="kost-address">
                <strong><i class="fa-solid fa-map-pin" style="color: #ef4444; margin-right: 5px;"></i> Alamat Lokasi</strong>
                {{ Str::limit($kost->alamat, 60) }}
            </div>
            
            <div class="action-icons">
                {{-- PERBAIKAN: Pesan WA diubah khusus untuk kapasitas Superadmin yang mengelola data properti --}}
                <a href="https://wa.me/{{ $kost->no_wa }}?text={{ urlencode('Halo Pemilik/Pengelola *' . $kost->nama_kost . '*, saya dari tim Admin SIKOSUB. Saya ingin berkomunikasi terkait data properti kost Anda yang terdaftar di sistem kami.') }}" target="_blank" class="icon-circle icon-wa" title="Hubungi Pemilik Terkait Data Kost">
                    <i class="fa-brands fa-whatsapp"></i>
                </a>
                <a href="{{ $kost->maps }}" target="_blank" class="icon-circle icon-map" title="Lihat di Maps">
                    <i class="fa-solid fa-location-arrow"></i>
                </a>
            </div>
        </div>

        <div class="actions-col">
            <a href="{{ route('kost.edit', $kost->id) }}" class="btn-action btn-edit"><i class="fa-solid fa-pen"></i> Edit Kost</a>
            
            <form action="{{ route('kost.destroy', $kost->id) }}" method="POST" style="width:100%;">
                @csrf @method('DELETE')
                <button type="submit" class="btn-action btn-delete" onclick="return confirm('Apakah Anda yakin ingin menghapus kost ini secara permanen?')"><i class="fa-solid fa-trash"></i> Hapus</button>
            </form>
        </div>
    </div>
    @empty
        <div style="text-align: center; padding: 60px; background: white; border-radius: 16px; border: 1px dashed #d1d5db;">
            <i class="fa-solid fa-house-circle-xmark" style="font-size: 40px; color: #9ca3af; margin-bottom: 15px;"></i>
            <h3 style="font-weight: 800; color: #374151;">Belum Ada Kost</h3>
            <p style="color: #6b7280; font-size: 14px;">Anda belum menambahkan data kost satupun ke dalam sistem.</p>
        </div>
    @endforelse
</div>
@endsection