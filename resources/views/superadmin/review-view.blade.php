@extends('layouts.admin')

@section('content')
<style>
    .content-wrapper { padding: 40px; max-width: 1000px; margin: 0 auto; font-family: 'Outfit', sans-serif; }
    .page-title { font-size: 28px; color: #111827; font-weight: 900; margin-bottom: 5px; }
    .page-subtitle { font-size: 14px; color: #6b7280; font-weight: 500; margin-bottom: 30px; display: block; }
    
    .btn-back { display: inline-flex; align-items: center; gap: 8px; color: #4f46e5; text-decoration: none; font-size: 14px; font-weight: 700; background: #e0e7ff; padding: 8px 16px; border-radius: 50px; transition: 0.2s; margin-bottom: 25px;}
    .btn-back:hover { background: #c7d2fe; transform: translateX(-5px); }

    .review-card { background: white; padding: 25px; border-radius: 16px; margin-bottom: 20px; border: 1px solid #f3f4f6; box-shadow: 0 4px 15px rgba(0,0,0,0.02); transition: 0.3s;}
    .review-card:hover { border-color: #cbd5e1; box-shadow: 0 10px 25px rgba(0,0,0,0.05); }
    
    .review-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; border-bottom: 1px dashed #e2e8f0; padding-bottom: 15px;}
    .user-info { display: flex; align-items: center; gap: 12px; }
    .avatar { width: 45px; height: 45px; border-radius: 50%; object-fit: cover; border: 2px solid #f1f5f9; }
    .user-name { font-weight: 800; color: #0f172a; font-size: 16px; text-transform: uppercase;}
    .review-date { font-size: 12px; color: #94a3b8; font-weight: 500; }
    
    .rating-badge { background: #fef9c3; color: #b45309; padding: 6px 12px; border-radius: 8px; font-weight: 800; font-size: 14px; display: flex; align-items: center; gap: 5px; }
    .review-text { color: #475569; font-size: 15px; line-height: 1.6; margin: 0; }
    
    .empty-state { text-align: center; padding: 60px 20px; background: white; border-radius: 16px; border: 2px dashed #e2e8f0; }
    .empty-state i { font-size: 48px; color: #cbd5e1; margin-bottom: 15px; }
    .empty-state h3 { font-size: 18px; color: #334155; font-weight: 800; margin-bottom: 5px; }
    .empty-state p { color: #94a3b8; font-size: 14px; }
</style>

<div class="content-wrapper">
    <a href="{{ route('review.manage') }}" class="btn-back"><i class="fa-solid fa-arrow-left-long"></i> Kembali ke Daftar Kost</a>
    
    <h1 class="page-title">Ulasan Pengunjung</h1>
    <span class="page-subtitle">Properti: <strong style="color: #4f46e5;">{{ $kost->nama_kost }}</strong> | Total: {{ count($reviews) }} Ulasan</span>

    <div style="margin-top: 20px;">
        @forelse($reviews as $review)
        <div class="review-card">
            <div class="review-header">
                <div class="user-info">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($review->user->name ?? 'User') }}&background=f1f5f9&color=475569" class="avatar" alt="User">
                    <div>
                        <div class="user-name">{{ $review->user->name ?? 'Pengguna SIKOSUB' }}</div>
                        <div class="review-date">{{ \Carbon\Carbon::parse($review->created_at)->translatedFormat('d F Y, H:i') }}</div>
                    </div>
                </div>
                <div class="rating-badge">
                    <i class="fa-solid fa-star" style="color: #eab308;"></i> {{ number_format($review->rating, 1) }} / 5.0
                </div>
            </div>
            <p class="review-text">"{{ $review->komentar }}"</p>
        </div>
        @empty
        <div class="empty-state">
            <i class="fa-regular fa-comments"></i>
            <h3>Belum Ada Ulasan</h3>
            <p>Properti kost ini belum menerima ulasan atau rating dari pengunjung.</p>
        </div>
        @endforelse
    </div>
</div>
@endsection