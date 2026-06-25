@extends('layouts.admin')

@section('content')
<style>
    /* CSS sama persis dengan tambah-kost untuk konsistensi */
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
            <h1 class="page-title">Edit Data Kost</h1>
            <span class="page-subtitle">Perbarui informasi kost {{ $kost->nama_kost }}</span>
        </div>
        <a href="{{ route('daftar.kost') }}" class="btn btn-cancel"><i class="fa-solid fa-arrow-left"></i> Batal</a>
    </div>

    <form action="{{ route('kost.update', $kost->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="admin-card">
            <div class="card-header"><i class="fa-solid fa-house" style="color: #0066FF;"></i> Informasi Utama</div>
            <div class="form-grid">
                <div class="form-group">
                    <label>Nama Kost <span style="color:red;">*</span></label>
                    <input type="text" name="nama_kost" class="form-control" value="{{ $kost->nama_kost }}" required>
                </div>
                <div class="form-group">
                    <label>Tipe Kost <span style="color:red;">*</span></label>
                    <select name="tipe_kost" class="form-control" required>
                        <option value="putra" {{ $kost->tipe_kost == 'putra' ? 'selected' : '' }}>Putra</option>
                        <option value="putri" {{ $kost->tipe_kost == 'putri' ? 'selected' : '' }}>Putri</option>
                        <option value="campur" {{ $kost->tipe_kost == 'campur' ? 'selected' : '' }}>Campur</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Harga Per Bulan (Rp) <span style="color:red;">*</span></label>
                    <input type="number" name="harga_per_bulan" class="form-control" value="{{ $kost->harga_per_bulan }}" required>
                </div>
                <div class="form-group">
                    <label>Harga Diskon (Rp)</label>
                    <input type="number" name="harga_diskon" class="form-control" value="{{ $kost->harga_diskon }}">
                </div>
            </div>
            <div class="form-grid" style="grid-template-columns: 1fr 1fr;">
                <div class="form-group">
                    <label>Gambar Utama (Biarkan kosong jika tidak diganti)</label>
                    <input type="file" name="gambar_utama" class="form-control" accept="image/*">
                </div>
                <div class="form-group">
                    <label>Alamat Lengkap <span style="color:red;">*</span></label>
                    <input type="text" name="alamat" class="form-control" value="{{ $kost->alamat }}" required>
                </div>
            </div>
        </div>

        <div class="admin-card">
            <div class="card-header"><i class="fa-solid fa-map-location-dot" style="color: #10b981;"></i> Lokasi & Kontak</div>
            <div class="form-grid">
                <div class="form-group">
                    <label>Kategori Wilayah</label>
                    <select name="kategori_wilayah" class="form-control">
                        <option value="">-- Pilih Wilayah --</option>
                        <option value="pemda" {{ $kost->kategori_wilayah == 'pemda' ? 'selected' : '' }}>Instansi Pemerintah</option>
                        <option value="unsub" {{ $kost->kategori_wilayah == 'unsub' ? 'selected' : '' }}>Instansi Pendidikan</option>
                        <option value="perusahaan" {{ $kost->kategori_wilayah == 'perusahaan' ? 'selected' : '' }}>Instansi Perusahaan</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Nama Detail Wilayah</label>
                    <input type="text" name="detail_wilayah" class="form-control" value="{{ $kost->detail_wilayah }}">
                </div>
                <div class="form-group">
                    <label>Link Google Maps</label>
                    <input type="text" name="maps" class="form-control" value="{{ $kost->maps }}">
                </div>
                <div class="form-group">
                    <label>No WhatsApp Pemilik/Admin</label>
                    <input type="text" name="no_wa" class="form-control" value="{{ $kost->no_wa }}">
                </div>
            </div>
        </div>

        <div class="admin-card">
            <div class="card-header"><i class="fa-solid fa-bed" style="color: #f59e0b;"></i> Kelengkapan & Fasilitas</div>
            <div class="form-grid">
                <div class="form-group">
                    <label>Fasilitas Kamar</label>
                    <input type="text" name="fasilitas_kamar" class="form-control" value="{{ $kost->fasilitas_kamar }}">
                </div>
                <div class="form-group">
                    <label>Fasilitas Kamar Mandi</label>
                    <input type="text" name="fasilitas_km" class="form-control" value="{{ $kost->fasilitas_km }}">
                </div>
                <div class="form-group">
                    <label>Fasilitas Umum</label>
                    <input type="text" name="fasilitas_umum" class="form-control" value="{{ $kost->fasilitas_umum }}">
                </div>
                <div class="form-group">
                    <label>Fasilitas Parkir</label>
                    <input type="text" name="fasilitas_parkir" class="form-control" value="{{ $kost->fasilitas_parkir }}">
                </div>
                <div class="form-group">
                    <label>Spesifikasi Kamar</label>
                    <input type="text" name="spesifikasi_kamar" class="form-control" value="{{ $kost->spesifikasi_kamar }}">
                </div>
                <div class="form-group">
                    <label>Peraturan Kost</label>
                    <input type="text" name="peraturan" class="form-control" value="{{ $kost->peraturan }}">
                </div>
                <div class="form-group">
                    <label>Ketentuan Sewa</label>
                    <input type="text" name="ketentuan" class="form-control" value="{{ $kost->ketentuan }}">
                </div>
                <div class="form-group">
                    <label>Tempat Terdekat</label>
                    <input type="text" name="tempat_terdekat" class="form-control" value="{{ $kost->tempat_terdekat }}">
                </div>
            </div>
            
            <div class="btn-group">
                <button type="submit" class="btn btn-save"><i class="fa-solid fa-floppy-disk"></i> Simpan Perubahan</button>
            </div>
        </div>
    </form>
</div>
@endsection