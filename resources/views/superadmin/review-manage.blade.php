@extends('layouts.admin')

@section('content')
<style>
    .content-wrapper { padding: 40px; max-width: 1200px; margin: 0 auto; font-family: 'Outfit', sans-serif; }
    .page-title { font-size: 28px; color: #111827; font-weight: 900; margin-bottom: 5px; }
    .page-subtitle { font-size: 14px; color: #6b7280; font-weight: 500; margin-bottom: 25px; display: block; }
    
    .btn-back { display: inline-flex; align-items: center; gap: 8px; color: #4f46e5; text-decoration: none; font-size: 14px; font-weight: 700; background: #e0e7ff; padding: 8px 16px; border-radius: 50px; transition: 0.2s; margin-bottom: 25px;}
    .btn-back:hover { background: #c7d2fe; transform: translateX(-5px); }

    .admin-card { background: white; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.03); border: 1px solid #f3f4f6; overflow: hidden; }
    
    .modern-table { width: 100%; border-collapse: collapse; }
    .modern-table thead { background: #f8fafc; }
    .modern-table th { padding: 18px 25px; text-align: left; font-size: 12px; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 1px; border-bottom: 1px solid #e2e8f0; }
    .modern-table td { padding: 20px 25px; border-bottom: 1px solid #f1f5f9; font-size: 14px; color: #334155; vertical-align: middle; }
    .modern-table tbody tr:hover td { background: #f8fafc; }
    
    .user-cell { display: flex; align-items: center; gap: 12px; font-weight: 700; color: #0f172a; text-transform: uppercase; }
    .avatar { width: 35px; height: 35px; border-radius: 50%; object-fit: cover; border: 1px solid #e2e8f0;}
    
    .komentar-text { max-width: 400px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; text-overflow: ellipsis; line-height: 1.5; color: #475569;}
    
    .rating-stars { color: #cbd5e1; font-size: 13px; letter-spacing: 2px;}
    .rating-stars .active { color: #f59e0b; }
    
    .btn-delete { background: #fee2e2; color: #ef4444; border: none; padding: 8px 16px; border-radius: 8px; font-weight: 700; font-size: 13px; cursor: pointer; transition: 0.2s; display: inline-flex; align-items: center; gap: 6px;}
    .btn-delete:hover { background: #ef4444; color: white; transform: translateY(-2px); box-shadow: 0 4px 10px rgba(239, 68, 68, 0.2);}
</style>

<div class="content-wrapper">
    <a href="{{ route('review.manage') }}" class="btn-back"><i class="fa-solid fa-arrow-left-long"></i> Kembali ke Daftar Kost</a>

    <h1 class="page-title">Kelola Ulasan Properti</h1>
    <span class="page-subtitle">Properti: <strong style="color: #4f46e5;">{{ $kost->nama_kost }}</strong></span>
    
    <div class="admin-card">
        <table class="modern-table">
            <thead>
                <tr>
                    <th>Pengguna</th>
                    <th>Ulasan / Komentar</th>
                    <th>Rating</th>
                    <th style="text-align: right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reviews as $review)
                <tr>
                    <td>
                        <div class="user-cell">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($review->user->name ?? 'U') }}&background=f1f5f9&color=475569" class="avatar">
                            {{ $review->user->name ?? 'User Anonim' }}
                        </div>
                    </td>
                    <td>
                        <div class="komentar-text" title="{{ $review->komentar }}">
                            {{ $review->komentar }}
                        </div>
                        <div style="font-size: 11px; color: #94a3b8; margin-top: 5px;">
                            {{ \Carbon\Carbon::parse($review->created_at)->diffForHumans() }}
                        </div>
                    </td>
                    <td>
                        <div class="rating-stars">
                            @for($i=1; $i<=5; $i++)
                                <i class="fa-solid fa-star {{ $i <= $review->rating ? 'active' : '' }}"></i>
                            @endfor
                            <span style="color: #0f172a; font-weight: 800; margin-left: 5px;">{{ $review->rating }}</span>
                        </div>
                    </td>
                    <td style="text-align: right;">
                        <form action="{{ route('review.delete', $review->id) }}" method="POST" id="delete-form-{{ $review->id }}">
                            @csrf @method('DELETE')
                            <button type="button" class="btn-delete" onclick="confirmDelete('{{ $review->id }}')">
                                <i class="fa-regular fa-trash-can"></i> Hapus
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" style="padding: 40px; text-align: center; color: #64748b; font-weight: 600;">
                        Belum ada ulasan yang perlu dikelola.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- SweetAlert2 untuk Konfirmasi Hapus -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@if(session('success'))
<script>
    Swal.fire({ icon: 'success', title: 'Berhasil!', text: '{!! session('success') !!}', timer: 2500, showConfirmButton: false });
</script>
@endif

<script>
    function confirmDelete(id) {
        Swal.fire({
            title: 'Hapus Ulasan Ini?',
            text: "Ulasan yang dihapus tidak bisa dikembalikan, dan rating kost akan otomatis dihitung ulang.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + id).submit();
            }
        })
    }
</script>
@endsection