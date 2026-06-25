<?php

namespace App\Http\Controllers;

use App\Models\Kost;    // PENTING: Jangan lupa import model di atas class
use App\Models\Landing; // Menambahkan import model Landing untuk optimasi algoritma admin
use Illuminate\Http\Request;

class SuperAdminController extends Controller
{
    // ========================================================
    // 0. MENAMPILKAN DASHBOARD SUPERADMIN
    // ========================================================
    public function dashboard()
    {
        // Mengambil hitungan data dari database untuk statistik dashboard
        $totalKost = Kost::count();
        $kostPutra = Kost::where('tipe_kost', 'Putra')->count();
        $kostPutri = Kost::where('tipe_kost', 'Putri')->count();
        $kostCampur = Kost::where('tipe_kost', 'Campur')->count();

        $kostRatingTertinggi = Kost::orderBy('rating', 'desc')->first(); 
        $totalWilayah = Kost::distinct('wilayah')->count('wilayah');

        // Mengirim data ke file view (Blade)
        return view('superadmin.dashboard', compact(
            'totalKost', 'kostPutra', 'kostPutri', 'kostCampur', 'kostRatingTertinggi', 'totalWilayah'
        ));
    }

    // ========================================================
    // 1. OPTIMASI ALGORITMA: HAPUS FOTO FILTER SATUAN (Backend)
    // ========================================================
    public function deletePhoto($category, $photoIndex) {
        $landing = Landing::first(); 
        $column = 'image_' . $category; 
        
        if ($landing && !empty($landing->$column)) {
            $photos = explode(',', $landing->$column);
            
            if (isset($photos[$photoIndex])) {
                $photoName = trim($photos[$photoIndex]);
                
                $filePath = public_path('images/filters/' . $photoName);
                if (file_exists($filePath) && !empty($photoName)) {
                     @unlink($filePath);
                }
                
                // KUNCI PERBAIKAN: Jangan gunakan unset(), cukup kosongkan valuenya
                // Ini membuat index tetap ada, sehingga data nama instansi tidak bergeser
                $photos[$photoIndex] = ''; 
                
                // Gabungkan kembali tanpa array_values()
                $landing->$column = implode(',', $photos);
                $landing->save();
            }
        }
        
        return redirect()->back()->with('success', 'Foto berhasil dihapus satuan dan kembali ke foto default!');
    }
}