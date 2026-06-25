<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kost; 
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    // ============================================================
    // 1. HALAMAN BERANDA (DASHBOARD PENGUNJUNG)
    // ============================================================
    public function index()
    {
        // Ambil data untuk $kostPemda berdasarkan kolom kategori_wilayah
        $kostPemda = Kost::query()->where('kategori_wilayah', 'pemda')
                         ->orderBy('created_at', 'desc')
                         ->take(4)
                         ->get();

        // Ambil data untuk $kostUnsub berdasarkan kolom kategori_wilayah
        // Mengambil 3 data agar pas dengan desain .grid-3 di Blade Anda
        $kostUnsub = Kost::query()->where('kategori_wilayah', 'unsub')
                         ->orderBy('created_at', 'desc')
                         ->take(3) 
                         ->get();

        // Ambil semua data kost untuk Carousel Promo & Instansi Perusahaan
        $kosts = Kost::orderBy('created_at', 'desc')
                     ->take(10)
                     ->get();

        // Ambil data landing page
        $landing = DB::table('landings')->first();

        // Lempar variabel ke file home.blade.php
        return view('home', compact('kosts', 'kostPemda', 'kostUnsub', 'landing'));
    }

    // ============================================================
    // 2. FITUR PENCARIAN (SEARCH)
    // ============================================================
    public function search(Request $request)
    {
        $keyword = $request->input('keyword');
        $kategori = $request->input('kategori');

        return view('kost.search', compact('keyword', 'kategori'));
    }

    public function searchByCategory($nama_kategori)
    {
        $kategori = $nama_kategori;
        $keyword = '';

        return view('kost.search', compact('keyword', 'kategori'));
    }

    // ============================================================
    // 3. DETAIL & GALERI KOST
    // ============================================================
    public function show($id)
    {
        // Mengambil data kost asli dari database berdasarkan ID
        $kost = Kost::findOrFail($id);
        
        return view('kost.detail', compact('kost', 'id'));
    }

    public function gallery($id)
    {
        // Mengambil data kost asli untuk halaman galeri
        $kost = Kost::findOrFail($id);

        return view('kost.gallery', compact('kost', 'id'));
    }

    // ============================================================
    // 4. DAFTAR INSTANSI
    // ============================================================
    public function daftarInstansi($kategori)
    {
        $judul = 'Daftar Instansi';
        $icon = 'fa-building';

        if ($kategori == 'instansi-pemerintah') {
            $judul = 'Instansi Pemerintah';
            $icon = 'fa-building-columns';
        } elseif ($kategori == 'instansi-pendidikan') {
            $judul = 'Instansi Pendidikan';
            $icon = 'fa-graduation-cap';
        } elseif ($kategori == 'instansi-perusahaan') {
            $judul = 'Instansi Perusahaan';
            $icon = 'fa-cube';
        }

        return view('instansi.daftar', compact('kategori', 'judul', 'icon'));
    }

    // ============================================================
    // 5. FITUR FILTER KOST (AJAX)
    // ============================================================
    public function filterKost(Request $request)
    {
        $query = Kost::query(); 

        if ($request->has('fasilitas')) {
            $fasilitas = $request->input('fasilitas'); 
            foreach ($fasilitas as $f) {
                $query->where('fasilitas', 'like', "%$f%");
            }
        }

        $kosts = $query->get();

        return view('partials.kost-list', compact('kosts'))->render();
    }
}