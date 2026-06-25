@extends('layouts.admin')

@section('content')
<style>
    .content-wrapper { padding: 40px; max-width: 1200px; margin: 0 auto; font-family: 'Outfit', sans-serif; }
    .page-header { margin-bottom: 30px; }
    .page-title { font-size: 28px; font-weight: 800; color: #111; margin: 0 0 5px 0; }
    .page-subtitle { font-size: 13px; color: #6b7280; font-weight: 600; text-transform: uppercase; letter-spacing: 1px;}
    
    .admin-card { background: white; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.03); border: 1px solid #f3f4f6; overflow: hidden; }
    
    .modern-table { width: 100%; border-collapse: collapse; }
    .modern-table thead { background: #f9fafb; }
    .modern-table th { padding: 15px 25px; text-align: left; font-size: 12px; font-weight: 800; color: #6b7280; text-transform: uppercase; letter-spacing: 1px; border-bottom: 1px solid #e5e7eb; }
    .modern-table td { padding: 18px 25px; border-bottom: 1px solid #f3f4f6; font-size: 14px; color: #111; transition: 0.2s; vertical-align: middle; }
    .modern-table tbody tr:hover td { background: #eff6ff; }
    .modern-table tbody tr:last-child td { border-bottom: none; }
    
    .kost-name-td { font-weight: 700; font-size: 15px; display: flex; align-items: center; gap: 15px; }
    .kost-icon { width: 40px; height: 40px; background: #e0e7ff; color: #4f46e5; border-radius: 10px; display: flex; justify-content: center; align-items: center; font-size: 16px; }
    
    .btn-manage { background: #0066FF; color: white; padding: 8px 20px; border-radius: 8px; text-decoration: none; font-size: 13px; font-weight: 700; display: inline-flex; align-items: center; gap: 8px; transition: 0.2s; box-shadow: 0 2px 5px rgba(0,102,255,0.2); }
    .btn-manage:hover { background: #0052cc; transform: translateY(-2px); }
</style>

<div class="content-wrapper">
    <div class="page-header">
        <h1 class="page-title">Kelola Detail Kost</h1>
        <span class="page-subtitle">Pilih properti untuk menambahkan galeri foto atau detail spesifik lainnya</span>
    </div>

    <div class="admin-card">
        <table class="modern-table">
            <thead>
                <tr>
                    <th>Informasi Properti Kost</th>
                    <th style="width: 200px; text-align: right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($kosts as $item)
                <tr>
                    <td>
                        <div class="kost-name-td">
                            <div class="kost-icon"><i class="fa-solid fa-house"></i></div>
                            <div>
                                {{ $item->nama_kost }}
                                <div style="font-size: 12px; color: #6b7280; font-weight: 500; margin-top: 2px;">Terdaftar di sistem SIKOSUB</div>
                            </div>
                        </div>
                    </td>
                    <td style="text-align: right;">
                        <a href="{{ route('kost.manage-edit', $item->id) }}" class="btn-manage">
                            <i class="fa-solid fa-folder-open"></i> Kelola Detail
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="2" style="text-align: center; padding: 40px; color: #6b7280; font-weight: 600;">
                        Belum ada kost terdaftar. Silakan tambahkan kost terlebih dahulu.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection