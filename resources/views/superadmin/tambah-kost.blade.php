@extends('layouts.admin')

@section('content')
<style>
    .content-wrapper { padding: 40px; max-width: 1200px; margin: 0 auto; font-family: 'Outfit', sans-serif; }
    .page-header { margin-bottom: 30px; display: flex; justify-content: space-between; align-items: center; }
    .page-title { font-size: 28px; font-weight: 800; color: #111; margin: 0; }
    .page-subtitle { font-size: 13px; color: #6b7280; font-weight: 600; }
    
    .admin-card { background: white; border-radius: 16px; padding: 30px; margin-bottom: 30px; box-shadow: 0 4px 20px rgba(0,0,0,0.03); border: 1px solid #f3f4f6; }
    .card-header { font-size: 18px; font-weight: 800; color: #111; margin-bottom: 20px; padding-bottom: 15px; border-bottom: 2px solid #f3f4f6; display: flex; align-items: center; gap: 10px; }
    
    .form-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; }
    .form-group { margin-bottom: 15px; }
    .form-group label { display: block; font-size: 13px; font-weight: 700; color: #374151; margin-bottom: 8px; }
    .form-control { width: 100%; padding: 12px 15px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 14px; background: #f9fafb; transition: 0.3s; outline: none; font-family: 'Outfit', sans-serif; }
    .form-control:focus { background: white; border-color: #0066FF; box-shadow: 0 0 0 3px rgba(0,102,255,0.1); }
    
    .btn-group { display: flex; justify-content: flex-end; gap: 15px; margin-top: 20px; }
    .btn { padding: 12px 30px; border-radius: 8px; font-weight: 700; font-size: 14px; cursor: pointer; transition: 0.3s; border: none; display: flex; align-items: center; gap: 8px; }
    .btn-cancel { background: white; color: #4b5563; border: 1px solid #d1d5db; }
    .btn-cancel:hover { background: #f3f4f6; color: #111; }
    .btn-save { background: #0066FF; color: white; box-shadow: 0 4px 10px rgba(0,102,255,0.2); }
    .btn-save:hover { background: #0052cc; transform: translateY(-2px); }
</style>

<div class="content-wrapper">
    <div class="page-header">
        <div>
            <h1 class="page-title">Tambah Kost Baru</h1>
            <span class="page-subtitle">Publikasikan properti kost baru ke halaman pengunjung</span>
        </div>
        <a href="{{ route('daftar.kost') }}" class="btn btn-cancel"><i class="fa-solid fa-arrow-left"></i> Kembali</a>
    </div>

    <form action="{{ route('store.kost') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="mitra_id" value="1">
        
        <div class="admin-card">
            <div class="card-header"><i class="fa-solid fa-house" style="color: #0066FF;"></i> Informasi Utama</div>
            <div class="form-grid">

        
        
        <div class="admin-card">
            <div class="card-header"><i class="fa-solid fa-house" style="color: #0066FF;"></i> Informasi Utama</div>
            <div class="form-grid">
                <div class="form-group">
                    <label>Nama Kost <span style="color:red;">*</span></label>
                    <input type="text" name="nama_kost" class="form-control" placeholder="Contoh: Kost Mawar Indah" required>
                </div>
                <div class="form-group">
                    <label>Tipe Kost <span style="color:red;">*</span></label>
                    <select name="tipe_kost" class="form-control" required>
                        <option value="putra">Putra</option>
                        <option value="putri">Putri</option>
                        <option value="campur">Campur</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Harga Per Bulan (Rp) <span style="color:red;">*</span></label>
                    <input type="number" name="harga_per_bulan" class="form-control" placeholder="Contoh: 500000" required>
                </div>
                <div class="form-group">
                    <label>Harga Diskon (Rp - Opsional)</label>
                    <input type="number" name="harga_diskon" class="form-control" placeholder="Kosongkan jika tidak ada diskon">
                </div>
            </div>
            <div class="form-grid" style="grid-template-columns: 1fr 1fr;">
                <div class="form-group">
                    <label>Gambar Utama <span style="color:red;">*</span></label>
                    <input type="file" name="gambar_utama" class="form-control" accept="image/*" required>
                </div>
                <div class="form-group">
                    <label>Alamat Lengkap <span style="color:red;">*</span></label>
                    <input type="text" name="alamat" class="form-control" placeholder="Jalan, RT/RW, Kelurahan" required>
                </div>
            </div>
        </div>

        <div class="admin-card">
            <div class="card-header"><i class="fa-solid fa-map-location-dot" style="color: #10b981;"></i> Lokasi & Kontak</div>
            <div class="form-grid">
                <div class="form-group">
                    <label>Kategori Wilayah (Untuk Filter)</label>
                    <select name="kategori_wilayah" class="form-control">
                        <option value="">-- Pilih Wilayah Filter --</option>
                        <option value="pemda">Instansi Pemerintah</option>
                        <option value="unsub">Instansi Pendidikan</option>
                        <option value="perusahaan">Instansi Perusahaan</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Nama Detail Wilayah</label>
                    <input type="text" name="detail_wilayah" class="form-control" placeholder="Contoh: Belakang Kampus UNSUB">
                </div>
                <div class="form-group">
                    <label>Link Google Maps</label>
                    <input type="text" name="maps" class="form-control" placeholder="https://maps.google.com/...">
                </div>
                <div class="form-group">
                    <label>No WhatsApp Pemilik/Admin</label>
                    <input type="text" name="no_wa" class="form-control" placeholder="Awali dengan 628xxx">
                </div>
            </div>
        </div>

        <div class="admin-card">
            <div class="card-header"><i class="fa-solid fa-bed" style="color: #f59e0b;"></i> Kelengkapan & Fasilitas</div>
            <div class="form-grid">
                <div class="form-group">
                    <label>Fasilitas Kamar</label>
                    <input type="text" name="fasilitas_kamar" class="form-control" placeholder="Kasur, Lemari, Kipas">
                </div>
                <div class="form-group">
                    <label>Fasilitas Kamar Mandi</label>
                    <input type="text" name="fasilitas_km" class="form-control" placeholder="KM Dalam, Kloset Duduk">
                </div>
                <div class="form-group">
                    <label>Fasilitas Umum</label>
                    <input type="text" name="fasilitas_umum" class="form-control" placeholder="Dapur, Jemuran, WiFi">
                </div>
                <div class="form-group">
                    <label>Fasilitas Parkir</label>
                    <input type="text" name="fasilitas_parkir" class="form-control" placeholder="Parkir Motor Luas">
                </div>
                <div class="form-group">
                    <label>Spesifikasi Kamar</label>
                    <input type="text" name="spesifikasi_kamar" class="form-control" placeholder="Ukuran 3x3 Meter">
                </div>
                <div class="form-group">
                    <label>Peraturan Kost</label>
                    <input type="text" name="peraturan" class="form-control" placeholder="Akses 24 Jam, Bawa Kunci Sendiri">
                </div>
                <div class="form-group">
                    <label>Ketentuan Sewa</label>
                    <input type="text" name="ketentuan" class="form-control" placeholder="Minimal sewa 3 Bulan">
                </div>
                <div class="form-group">
                    <label>Tempat Terdekat</label>
                    <input type="text" name="tempat_terdekat" class="form-control" placeholder="Indomaret, Fotocopy">
                </div>
            </div>
            
            <div class="btn-group">
                <button type="submit" class="btn btn-save"><i class="fa-solid fa-cloud-arrow-up"></i> Simpan & Publikasikan Kost</button>
            </div>
        </div>
    </form>
</div>
@endsection