@extends('layouts.app')

@section('content')
<style>
    /* =========================================
       MODERN PROFILE LAYOUT
       ========================================= */
    :root {
        --primary: #3b82f6;
        --primary-hover: #2563eb;
        --danger: #ef4444;
        --success: #22c55e;
        --text-main: #111827;
        --text-muted: #6b7280;
        --bg-body: #f9fafb;
        --border-color: #e5e7eb;
    }

    /* Mengaktifkan smooth scrolling secara global */
    html { scroll-behavior: smooth; }

    /* Mengatur layout keseluruhan halaman profile */
    .profile-layout {
        display: flex;
        min-height: calc(100vh - 70px);
        background: var(--bg-body);
        border-top: 1px solid var(--border-color);
        font-family: 'Outfit', sans-serif;
    }

    /* Sidebar Biru Paling Kiri */
    .blue-sidebar {
        width: 80px;
        background-color: var(--primary);
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 30px 0;
        gap: 35px;
        box-shadow: 4px 0 15px rgba(59, 130, 246, 0.2);
        z-index: 10;
    }

    .blue-sidebar a, .blue-sidebar i {
        color: white;
        font-size: 22px;
        text-decoration: none;
        cursor: pointer;
        transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .blue-sidebar i:hover { transform: scale(1.2); }

    .blue-sidebar .icon-top {
        color: var(--primary);
        background: #fff;
        border-radius: 50%;
        font-size: 32px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }

    .blue-sidebar .icon-bottom {
        color: var(--primary);
        background: #fff;
        border-radius: 50%;
        font-size: 28px;
        padding: 8px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }

    /* Sidebar Putih (Menu Setting) */
    .white-sidebar {
        width: 280px;
        border-right: 1px solid var(--border-color);
        padding: 30px 25px;
        background: #fff;
        z-index: 5;
    }

    .white-sidebar .menu-title {
        font-size: 1.2rem;
        font-weight: 800;
        margin-bottom: 35px;
        display: flex;
        align-items: center;
        gap: 15px;
        cursor: pointer;
        color: var(--text-main);
    }

    .white-sidebar .menu-item {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 20px;
        font-weight: 600;
        color: var(--text-muted);
        cursor: pointer;
        font-size: 1.05rem;
        transition: all 0.2s;
        padding: 10px 15px;
        border-radius: 10px;
        text-decoration: none;
    }

    .white-sidebar .menu-item:hover { color: var(--primary); background: #eff6ff; transform: translateX(5px); }
    .white-sidebar .menu-item.active { color: var(--primary); font-weight: 800; background: #eff6ff; }

    /* Area Konten Utama Form */
    .main-content {
        flex: 1;
        padding: 40px clamp(20px, 5vw, 60px);
        background: #fff;
    }

    .profile-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 40px;
        max-width: 700px;
        border-bottom: 1px solid var(--border-color);
        padding-bottom: 20px;
    }

    .profile-header h1 {
        font-size: clamp(24px, 3vw, 32px);
        font-weight: 900;
        color: var(--text-main);
        margin: 0;
    }

    .profile-avatar {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        object-fit: cover;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        border: 3px solid white;
        transition: 0.3s;
    }
    .profile-avatar:hover { transform: scale(1.05); }

    /* Custom Alert */
    .alert-box { max-width: 700px; padding: 15px 20px; border-radius: 10px; margin-bottom: 25px; font-weight: 700; display: flex; align-items: center; gap: 10px; font-size: 0.95rem; animation: slideDown 0.4s ease; }
    .alert-success { background: #dcfce7; color: #166534; border-left: 5px solid var(--success); }
    @keyframes slideDown { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: translateY(0); } }

    .form-group { margin-bottom: 25px; max-width: 600px; }
    .form-group label { display: block; font-weight: 800; margin-bottom: 10px; color: var(--text-main); font-size: 0.95rem;}
    
    .form-group input {
        width: 100%;
        padding: 14px 18px;
        border: 2px solid var(--border-color);
        border-radius: 10px;
        font-size: 15px;
        outline: none;
        color: var(--text-main);
        transition: all 0.3s;
        font-family: 'Outfit', sans-serif;
        background: #fcfcfc;
    }
    .form-group input:focus { border-color: var(--primary); background: white; box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1); }

    /* Wrapper untuk icon centang hijau di dalam input email */
    .input-icon-right { position: relative; width: 100%; }
    .input-icon-right i {
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--success);
        font-size: 20px;
    }

    /* Kotak Upload dan Hapus Profil */
    .photo-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        margin-top: 35px;
        max-width: 600px;
    }

    .photo-box {
        flex: 1;
        min-width: 200px;
        border: 2px dashed var(--border-color);
        background: #fcfcfc;
        border-radius: 12px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 30px 20px;
        cursor: pointer;
        color: var(--text-muted);
        text-align: center;
        min-height: 200px;
        transition: all 0.3s ease;
        position: relative;
    }

    .photo-box:hover { background: #f3f4f6; border-color: var(--primary); color: var(--primary); transform: translateY(-3px); }
    .photo-box.delete-box:hover { border-color: var(--danger); color: var(--danger); }
    
    .photo-box i.icon-img { font-size: 45px; margin-bottom: 15px; font-weight: 300; transition: 0.3s;}
    .photo-box.delete-box:hover i.icon-img { color: var(--danger); }
    
    .btn-add-circle {
        display: inline-flex;
        justify-content: center;
        align-items: center;
        width: 35px;
        height: 35px;
        background: white;
        border: 1px solid var(--border-color);
        border-radius: 50%;
        margin-top: 15px;
        transition: 0.3s;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    }
    .photo-box:hover .btn-add-circle { border-color: var(--primary); background: var(--primary); color: white; }
    .photo-box:hover .btn-add-circle i { color: white !important; }

    /* Action Buttons (Save & Cancel) */
    .form-actions {
        max-width: 600px;
        display: flex;
        justify-content: flex-end;
        gap: 15px;
        margin-top: 40px;
        padding-top: 30px;
        border-top: 1px solid var(--border-color);
        padding-bottom: 50px;
    }

    .btn-cancel {
        padding: 12px 35px;
        border: 2px solid var(--primary);
        color: var(--primary);
        background: transparent;
        border-radius: 10px;
        font-weight: 700;
        cursor: pointer;
        font-size: 15px;
        transition: all 0.3s;
    }
    .btn-cancel:hover { background: #eff6ff; transform: translateY(-2px); }

    .btn-save {
        padding: 12px 40px;
        border: none;
        background: var(--primary);
        color: white;
        border-radius: 10px;
        font-weight: 700;
        cursor: pointer;
        font-size: 15px;
        transition: all 0.3s;
        box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3);
    }
    .btn-save:hover { background: var(--primary-hover); transform: translateY(-2px); box-shadow: 0 6px 20px rgba(59, 130, 246, 0.4); }

    /* Responsif */
    @media (max-width: 900px) {
        .profile-layout { flex-direction: column; }
        .blue-sidebar { width: 100%; flex-direction: row; justify-content: space-around; padding: 15px 0; height: auto;}
        .white-sidebar { width: 100%; border-right: none; border-bottom: 1px solid var(--border-color); display: flex; justify-content: center; gap: 20px; padding: 15px; flex-wrap: wrap;}
        .white-sidebar .menu-title { width: 100%; justify-content: center; margin-bottom: 10px; }
        .white-sidebar .menu-item { margin-bottom: 0; }
        .main-content { padding: 30px 20px; }
        .photo-actions { flex-direction: column; }
    }
</style>

<div class="profile-layout">
    
    <aside class="blue-sidebar">
        <a href="{{ route('profile.edit') }}" title="Profil Saya" style="text-decoration: none;">
            <i class="fa-solid fa-circle-user icon-top"></i>
        </a>
        
        <a href="{{ route('home') }}" title="Beranda" style="text-decoration: none;">
            <i class="fa-solid fa-house"></i>
        </a>
        
        <a href="{{ route('profile.bookmarks') }}" title="Bookmark Kost" style="text-decoration: none;">
            <i class="fa-solid fa-bookmark"></i>
        </a>
        
        <div style="flex-grow: 1;"></div>
        
        <a href="{{ route('profile.edit') }}" title="Pengaturan Akun" style="text-decoration: none;">
            <i class="fa-solid fa-gear icon-bottom"></i>
        </a>
    </aside>

    <aside class="white-sidebar">
        <div class="menu-title">
            <i class="fa-solid fa-chevron-left"></i> Settings
        </div>
        <a href="#" class="menu-item active">
            <i class="fa-solid fa-pen"></i> Edit Profile
        </a>
        <a href="{{ route('profile.bookmarks') }}" class="menu-item">
            <i class="fa-regular fa-bookmark"></i> Bookmark Kost
        </a>
    </aside>

    <main class="main-content">
        
        @if(session('success'))
            <div class="alert-box alert-success">
                <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
            </div>
        @endif

        <div class="profile-header">
            <h1>Edit Profile</h1>
            
            @php
                // Cek apakah user punya foto di database, jika tidak gunakan UI-Avatars
                $avatarUrl = Auth::user()->foto_profil ? asset('storage/avatars/' . Auth::user()->foto_profil) : 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name).'&background=3b82f6&color=fff';
            @endphp
            <img src="{{ $avatarUrl }}" alt="Avatar" class="profile-avatar" id="header-avatar-preview">
        </div>

        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="form-group">
                <label>Nama Lengkap</label>
                <input type="text" name="name" value="{{ old('name', Auth::user()->name) }}" required>
            </div>

            <div class="form-group">
                <label>Email Address</label>
                <div class="input-icon-right">
                    <input type="email" name="email" value="{{ old('email', Auth::user()->email) }}" required>
                    <i class="fa-solid fa-square-check" title="Email Terverifikasi"></i>
                </div>
            </div>

            <div class="form-group">
                <label>Contact Number (WhatsApp)</label>
                <input type="text" name="phone" value="{{ old('phone', Auth::user()->phone ?? '') }}" placeholder="Contoh: 08123456789">
            </div>

            <div class="form-group">
                <label>Kota Asal</label>
                <input type="text" name="kota" value="{{ old('kota', Auth::user()->kota ?? '') }}" placeholder="Contoh: Subang">
            </div>

            <div class="photo-actions">
                
                <label for="avatar_upload" class="photo-box">
                    <i class="fa-regular fa-image icon-img"></i>
                    <span style="font-size: 0.9rem; font-weight: 700; line-height: 1.5;">Upload Gambar<br>Profil Baru</span>
                    <div class="btn-add-circle"><i class="fa-solid fa-plus" style="color: #9ca3af;"></i></div>
                    
                    <input type="file" name="avatar" id="avatar_upload" accept="image/*" style="display: none;" onchange="previewAvatar(this)">
                </label>
                
                <div class="photo-box delete-box" onclick="removeAvatar()">
                    <i class="fa-regular fa-trash-can icon-img"></i>
                    <span style="font-size: 0.9rem; font-weight: 700; margin-top: 10px;">HAPUS FOTO PROFIL</span>
                    
                    <input type="hidden" name="remove_avatar" id="remove_avatar_flag" value="0">
                </div>
                
            </div>

            <div class="form-actions">
                <a href="{{ route('home') }}" style="text-decoration:none;">
                    <button type="button" class="btn-cancel">Batal</button>
                </a>
                <button type="submit" class="btn-save"><i class="fa-solid fa-floppy-disk" style="margin-right: 8px;"></i> Simpan Perubahan</button>
            </div>
        </form>

    </main>
</div>

<script>
    // URL fallback jika foto dihapus (Menggunakan inisial nama)
    const defaultAvatar = 'https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=3b82f6&color=fff';

    function previewAvatar(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            
            reader.onload = function(e) {
                // Tampilkan gambar yang baru diupload langsung di layar
                document.getElementById('header-avatar-preview').src = e.target.result;
                
                // Pastikan flag hapus di-reset menjadi 0 karena user sedang mengupload
                document.getElementById('remove_avatar_flag').value = '0';
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }

    function removeAvatar() {
        if(confirm('Apakah Anda yakin ingin menghapus foto profil? (Foto akan dikembalikan ke inisial nama)')) {
            // Ubah gambar di layar menjadi gambar default inisial
            document.getElementById('header-avatar-preview').src = defaultAvatar;
            
            // Kosongkan file input agar tidak ada gambar yang ikut terkirim
            document.getElementById('avatar_upload').value = ''; 
            
            // Ubah flag menjadi 1 agar backend tahu user ingin menghapus foto lamanya
            document.getElementById('remove_avatar_flag').value = '1';
        }
    }
</script>
@endsection