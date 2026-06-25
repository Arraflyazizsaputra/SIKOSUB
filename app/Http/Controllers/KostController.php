<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kost;
use App\Models\Bookmark;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class KostController extends Controller
{
    // ======================================================================
    // LOGIKA PENAMBAHAN KUNJUNGAN
    // ======================================================================
    public function show($id)
    {
        $kost = Kost::findOrFail($id);
        
        try {
            $kost->increment('visit_count');
        } catch (\Exception $e) {
            // Abaikan error jika kolom belum ada di database
        }

        return view('kost.detail', compact('kost'));
    }

    public function gallery($id)
    {
        $kost = Kost::findOrFail($id);
        return view('kost.gallery', compact('kost'));
    }

    // ======================================================================
    // FITUR ULASAN & RATING (PENGUNJUNG & ADMIN)
    // ======================================================================
    
    // Fungsi Menyimpan Ulasan (Pencari Kost)
    public function storeReviewPublic(Request $request, $id)
    {
        if (!Auth::guard('web')->check()) {
            return redirect()->back()->with('error', 'Silakan login sebagai Pencari Kost untuk memberikan ulasan.');
        }

        $request->validate([
            'komentar' => 'required|string',
            'rating' => 'required|integer|min:1|max:5',
        ]);

        // Simpan Review ke Database
        \App\Models\Review::create([
            'user_id' => Auth::guard('web')->id(),
            'kost_id' => $id,
            'rating' => $request->rating,
            'komentar' => $request->komentar,
        ]);

        // Otomatis Hitung Ulang Rata-Rata Rating Kost
        $avgRating = \App\Models\Review::where('kost_id', $id)->avg('rating');
        \App\Models\Kost::where('id', $id)->update(['rating' => $avgRating]);

        return redirect()->back()->with('success', 'Ulasan Anda berhasil dikirim! Terima kasih.');
    }

    // Fungsi Menghapus Ulasan (Superadmin)
    public function deleteReview($id)
    {
        $review = \App\Models\Review::findOrFail($id);
        $kostId = $review->kost_id;
        
        $review->delete();

        // Otomatis Hitung Ulang Rata-Rata Rating Kost Setelah Dihapus
        $avgRating = \App\Models\Review::where('kost_id', $kostId)->avg('rating');
        // Jika ulasan habis, kembalikan ke default 5.0
        \App\Models\Kost::where('id', $kostId)->update(['rating' => $avgRating ?? 5.0]);

        return redirect()->back()->with('success', 'Ulasan berhasil dihapus.');
    }

    // ======================================================================
    // PENCARIAN & FILTER
    // ======================================================================
    public function search(Request $request)
    {
        $query = Kost::query();

        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where(function($q) use ($keyword) {
                $q->where('nama_kost', 'like', '%' . $keyword . '%')
                  ->orWhere('alamat', 'like', '%' . $keyword . '%')
                  ->orWhere('kategori_wilayah', 'like', '%' . $keyword . '%')
                  ->orWhere('detail_wilayah', 'like', '%' . $keyword . '%');
            });
        }

        if ($request->filled('kategori') && $request->kategori != 'semua') {
            $query->where('tipe_kost', $request->kategori);
        }

        $kosts = $query->paginate(10);
        return view('kost.search', compact('kosts'));
    }

    public function filter(Request $request)
    {
        $query = Kost::query();

        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where(function($q) use ($keyword) {
                $q->where('nama_kost', 'like', '%' . $keyword . '%')
                  ->orWhere('alamat', 'like', '%' . $keyword . '%')
                  ->orWhere('kategori_wilayah', 'like', '%' . $keyword . '%')
                  ->orWhere('detail_wilayah', 'like', '%' . $keyword . '%');
            });
        }

        if ($request->filled('kategori') && $request->kategori != 'semua') {
            $query->where('tipe_kost', $request->kategori);
        }

        if ($request->filled('jarak')) {
            $query->where(function($q) use ($request) {
                $q->where('jarak_km', '<=', $request->jarak)
                  ->orWhereNull('jarak_km'); 
            });
        }

        if ($request->filled('harga')) {
            $query->where('harga_per_bulan', '<=', $request->harga);
        }

        if ($request->filled('fasilitas')) {
            $fasilitasList = is_array($request->fasilitas) ? $request->fasilitas : [$request->fasilitas];
            
            foreach ($fasilitasList as $fasilitas) {
                $query->where(function($q) use ($fasilitas) {
                    $q->where('fasilitas_kamar', 'like', '%' . $fasilitas . '%')
                      ->orWhere('fasilitas_km', 'like', '%' . $fasilitas . '%')
                      ->orWhere('fasilitas_umum', 'like', '%' . $fasilitas . '%')
                      ->orWhere('spesifikasi_kamar', 'like', '%' . $fasilitas . '%');
                });
            }
        }

        $kosts = $query->paginate(5, ['*'], 'page', $request->page ?: 1);
        
        return view('partials.kost-list', compact('kosts'))->render();
    }

    public function toggleBookmark(Request $request)
    {
        if (!Auth::guard('web')->check()) {
            return response()->json([
                'status' => 'error', 
                'message' => 'Peringatan: Fitur Favorit hanya bisa digunakan oleh akun Pencari Kost. Anda saat ini login sebagai Admin/Mitra.'
            ], 403);
        }

        $userId = Auth::guard('web')->id();
        $kostId = $request->input('kost_id');

        $cekUser = \App\Models\User::find($userId);
        if (!$cekUser) {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return response()->json([
                'status' => 'error', 
                'message' => 'Sesi Anda tidak valid (Akun mungkin telah dihapus). Silakan muat ulang halaman dan login kembali.'
            ], 401);
        }

        if (!$kostId) {
            return response()->json(['status' => 'error', 'message' => 'ID Kost tidak ditemukan.'], 400);
        }

        try {
            $bookmark = \App\Models\Bookmark::where('user_id', $userId)
                                            ->where('kost_id', $kostId)
                                            ->first();

            if ($bookmark) {
                $bookmark->delete(); 
                return response()->json(['status' => 'removed', 'message' => 'Dihapus dari Bookmark']);
            } else {
                \App\Models\Bookmark::create([
                    'user_id' => $userId,
                    'kost_id' => $kostId
                ]);
                return response()->json(['status' => 'added', 'message' => 'Ditambahkan ke Bookmark']);
            }
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    // ======================================================================
    // MANAJEMEN KOST (SUPERADMIN)
    // ======================================================================
    public function create() 
    {
        return view('superadmin.tambah-kost');
    }

    public function store(Request $request) 
    {
        $request->validate([
            'nama_kost' => 'required|string|max:255',
            'harga_per_bulan' => 'required|numeric',
            'tipe_kost' => 'required|in:putra,putri,campur', 
            'gambar_utama' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $imageName = null;
        if ($request->hasFile('gambar_utama')) {
            $imageName = time().'.'.$request->gambar_utama->extension();  
            $request->gambar_utama->move(public_path('images/kost'), $imageName);
        }

        Kost::create([
            'nama_kost' => $request->nama_kost,
            'harga_per_bulan' => $request->harga_per_bulan,
            'tipe_kost' => $request->tipe_kost,
            'alamat' => $request->alamat,
            'gambar_utama' => $imageName,
            'harga_diskon' => $request->harga_diskon ?? 0,
            'rating' => $request->rating ?? 5.0,
            'no_wa' => $request->no_wa,
            'maps' => $request->maps,
            'kategori_wilayah' => $request->kategori_wilayah,
            'detail_wilayah' => $request->detail_wilayah,
            'disewakan_oleh' => $request->disewakan_oleh,
            'info_kamar' => $request->info_kamar,
            'fasilitas_umum' => $request->fasilitas_umum,
            'spesifikasi_kamar' => $request->spesifikasi_kamar,
            'fasilitas_kamar' => $request->fasilitas_kamar,
            'fasilitas_km' => $request->fasilitas_km,
            'pemilik' => $request->pemilik,
            'fasilitas_parkir' => $request->fasilitas_parkir,
            'peraturan' => $request->peraturan,
            'ketentuan' => $request->ketentuan,
            'tempat_terdekat' => $request->tempat_terdekat,
            'review_admin' => $request->review_admin,
            'kontak_pemilik' => $request->kontak_pemilik,
        ]);

        return redirect()->route('daftar.kost')->with('success', 'Kost berhasil ditambahkan!');
    }

    public function index() 
    {
        $kosts = Kost::all();
        return view('superadmin.daftar-kost', compact('kosts'));
    }

    public function destroy($id) 
    {
        $kost = Kost::findOrFail($id);
        if ($kost->gambar_utama && file_exists(public_path('images/kost/'.$kost->gambar_utama))) {
            unlink(public_path('images/kost/'.$kost->gambar_utama));
        }
        $kost->delete();
        return redirect()->route('daftar.kost')->with('success', 'Kost berhasil dihapus!');
    }

    public function edit($id)
    {
        $kost = Kost::findOrFail($id);
        return view('superadmin.edit-kost', compact('kost'));
    }

    public function update(Request $request, $id)
    {
        $kost = Kost::findOrFail($id);
        if ($request->hasFile('gambar_utama')) {
            if ($kost->gambar_utama && file_exists(public_path('images/kost/'.$kost->gambar_utama))) {
                unlink(public_path('images/kost/'.$kost->gambar_utama));
            }
            $imageName = time().'.'.$request->gambar_utama->extension();
            $request->gambar_utama->move(public_path('images/kost'), $imageName);
            $kost->gambar_utama = $imageName;
        }

        $kost->update($request->except(['gambar_utama']));
        return redirect()->route('daftar.kost')->with('success', 'Data kost diperbarui!');
    }

    public function manageDetailIndex()
    {
        $kosts = Kost::all();
        return view('superadmin.kelola-detail-index', compact('kosts')); 
    }

    public function manageDetailEdit($id)
    {
        $kost = Kost::findOrFail($id);
        return view('superadmin.kelola-detail-edit', compact('kost'));
    }

    public function manageDetailUpdate(Request $request, $id)
    {
        $kost = Kost::findOrFail($id);

        $data = $request->except(['_token', '_method', 'gallery_1', 'gallery_2', 'gallery_3', 'gallery_4', 'gallery_new']);

        $galleries = [];
        if (!empty($kost->gallery_images)) {
            $galleries = is_string($kost->gallery_images) ? json_decode($kost->gallery_images, true) : $kost->gallery_images;
        }
        if (!is_array($galleries)) {
            $galleries = [];
        }

        for ($i = 1; $i <= 4; $i++) {
            $field = "gallery_$i";
            if ($request->hasFile($field)) {
                $file = $request->file($field);
                $name = time() . '_galeri' . $i . '_' . uniqid() . '.' . $file->extension();
                $file->move(public_path('images/kost'), $name);
                $galleries[$i-1] = $name;
            }
        }
        
        if ($request->hasFile('gallery_new')) {
            foreach($request->file('gallery_new') as $file) {
                $name = time() . '_galeri_extra_' . uniqid() . '.' . $file->extension();
                $file->move(public_path('images/kost'), $name);
                $galleries[] = $name;
            }
        }

        $galleries = array_values($galleries);
        $data['gallery_images'] = json_encode($galleries);

        if (isset($data['jarak_km'])) {
            $data['jarak_km'] = (double) filter_var($data['jarak_km'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        }

        $kost->update($data);

        return redirect()->route('kost.manage-index')->with('success', 'Detail kost dan Galeri berhasil diperbarui!');
    }

    public function manageLanding()
    {
        $landing = DB::table('landings')->where('id', 1)->first();
        return view('superadmin.kelola-landing', compact('landing'));
    }

    public function manageReviewIndex()
    {
        $kosts = Kost::all();
        return view('superadmin.kelola-review', compact('kosts'));
    }

    public function viewReviews($id) {
        $kost = Kost::findOrFail($id);
        $reviews = $kost->reviews()->with('user')->orderBy('created_at', 'desc')->get() ?? collect([]);
        return view('superadmin.review-view', compact('kost', 'reviews'));
    }

    public function manageReviews($id) {
        $kost = Kost::findOrFail($id);
        $reviews = $kost->reviews()->with('user')->orderBy('created_at', 'desc')->get() ?? collect([]);
        return view('superadmin.review-manage', compact('kost', 'reviews'));
    }

    public function bookmarks()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $bookmarks = Bookmark::with('kost')
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('profile.bookmarks', compact('bookmarks'));
    }

    // ======================================================================
    // MANAJEMEN KOST (MITRA)
    // ======================================================================
    public function indexMitra()
    {
        $kosts = Kost::all(); 
        return view('mitra.kost.index', compact('kosts'));
    }

    public function createMitra()
    {
        return view('mitra.kost.create');
    }

    public function storeMitra(Request $request)
    {
        $request->validate([
            'nama_kost' => 'required|string|max:255',
            'harga_per_bulan' => 'required|numeric',
            'tipe_kost' => 'required|in:putra,putri,campur', 
            'gambar_utama' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $mitraId = auth()->guard('mitra')->id() ?? 1;

        $imageName = null;
        if ($request->hasFile('gambar_utama')) {
            $imageName = time().'.'.$request->gambar_utama->extension();  
            $request->gambar_utama->move(public_path('images/kost'), $imageName);
        }

        $kost = new Kost();
        $kost->mitra_id = $mitraId;
        $kost->nama_kost = $request->nama_kost;
        $kost->harga_per_bulan = $request->harga_per_bulan;
        $kost->tipe_kost = $request->tipe_kost;
        $kost->alamat = $request->alamat;
        $kost->alamat_lengkap = $request->alamat; 
        $kost->gambar_utama = $imageName;
        $kost->harga_diskon = $request->harga_diskon ?? 0;
        $kost->rating = 5.0;
        $kost->no_wa = $request->no_wa;
        $kost->maps = $request->maps;
        $kost->kategori_wilayah = $request->kategori_wilayah;
        $kost->detail_wilayah = $request->detail_wilayah;
        $kost->spesifikasi_kamar = $request->spesifikasi_kamar;
        $kost->fasilitas_kamar = $request->fasilitas_kamar;
        $kost->fasilitas_km = $request->fasilitas_km;
        $kost->fasilitas_umum = $request->fasilitas_umum;
        $kost->fasilitas_parkir = $request->fasilitas_parkir;
        $kost->peraturan = $request->peraturan;
        $kost->ketentuan = $request->ketentuan;
        $kost->tempat_terdekat = $request->tempat_terdekat;
        $kost->jumlah_kamar = $request->jumlah_kamar ?? 1;
        $kost->sisa_kamar = $request->sisa_kamar ?? 1;

        $kost->save(); 

        return redirect()->route('mitra.kost.index')->with('success', 'Properti kost baru berhasil dipublikasikan!');
    }

    public function destroyMitra($id)
    {
        $kost = Kost::findOrFail($id);
        if ($kost->gambar_utama && file_exists(public_path('images/kost/'.$kost->gambar_utama))) {
            unlink(public_path('images/kost/'.$kost->gambar_utama));
        }
        $kost->delete();
        return redirect()->route('mitra.kost.index')->with('success', 'Properti kost berhasil dihapus permanen.');
    }

    public function updateGalleryMitra(Request $request, $id)
    {
        $kost = Kost::findOrFail($id);
        $request->validate([
            'gallery_images.*' => 'image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $images = [];
        if (!empty($kost->gallery_images) && $kost->gallery_images != 'null') {
            $images = json_decode($kost->gallery_images, true);
        }

        if ($request->hasFile('gallery_images')) {
            foreach($request->file('gallery_images') as $file) {
                $name = time() . '_' . uniqid() . '.' . $file->extension();
                $file->move(public_path('images/kost'), $name);
                $images[] = $name;
            }
            $kost->update(['gallery_images' => json_encode($images)]);
            return redirect()->route('mitra.kost.index')->with('success', 'Galeri foto berhasil ditambahkan!');
        }

        return redirect()->route('mitra.kost.index')->with('error', 'Tidak ada foto yang dipilih.');
    }

    public function editMitra($id)
    {
        $kost = Kost::findOrFail($id);
        return view('mitra.kost.edit', compact('kost'));
    }

    public function updateMitra(Request $request, $id)
    {
        $kost = Kost::findOrFail($id);
        $request->validate([
            'nama_kost' => 'required|string|max:255',
            'harga_per_bulan' => 'required|numeric',
            'tipe_kost' => 'required|in:putra,putri,campur', 
            'gambar_utama' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = $request->except(['gambar_utama', '_token', '_method']);

        if ($request->hasFile('gambar_utama')) {
            if ($kost->gambar_utama && file_exists(public_path('images/kost/'.$kost->gambar_utama))) {
                unlink(public_path('images/kost/'.$kost->gambar_utama));
            }
            $imageName = time().'.'.$request->gambar_utama->extension();  
            $request->gambar_utama->move(public_path('images/kost'), $imageName);
            $data['gambar_utama'] = $imageName;
        }

        $kost->update($data);
        return redirect()->route('mitra.kost.index')->with('success', 'Data properti berhasil diperbarui!');
    }

    // ============================================================
    // MENYIMPAN RATING & ULASAN DARI PENGUNJUNG/PENCARI KOST
    // ============================================================
    public function storePublicReview(Request $request, $id)
    {
        // 1. Validasi input dari form
        $request->validate([
            'rating'   => 'required|integer|min:1|max:5',
            'komentar' => 'nullable|string'
        ]);

        // 2. Pastikan yang memberi rating adalah Pencari Kost yang sudah login
        if (!Auth::guard('web')->check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu untuk memberikan ulasan.');
        }

        // 3. Simpan data review ke tabel reviews
        \Illuminate\Support\Facades\DB::table('reviews')->insert([
            'user_id'    => Auth::guard('web')->id(),
            'kost_id'    => $id,
            'rating'     => $request->rating,
            'komentar'   => $request->komentar,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // 4. Kalkulasi ulang rata-rata rating (bintang) untuk kost ini
        $rataRata = \Illuminate\Support\Facades\DB::table('reviews')
                        ->where('kost_id', $id)
                        ->avg('rating');
        
        // 5. Update angka rata-rata rating di tabel kosts agar sinkron
        \Illuminate\Support\Facades\DB::table('kosts')
            ->where('id', $id)
            ->update(['rating' => round($rataRata, 1)]);

        // 6. Kembalikan pengguna ke halaman sebelumnya dengan pesan sukses
        return redirect()->back()->with('success', 'Terima kasih! Rating dan ulasan Anda berhasil dikirim.');
    }
}