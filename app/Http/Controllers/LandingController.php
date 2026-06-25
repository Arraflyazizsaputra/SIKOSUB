<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Landing; 
use App\Models\Kost;    
use Illuminate\Support\Facades\Storage;

class LandingController extends Controller
{
    // ========================================================
    // 0. HALAMAN UTAMA / HOME (FRONT-END PENGUNJUNG)
    // ========================================================
    public function index()
    {
        // KUNCI: Mengambil data pertama yang ada di database berapapun ID-nya
        $landing = Landing::first() ?? Landing::create(['title' => 'SIKOSUB']);

        $mapFilterData = function($textString, $imageString, $defaultImagePrefix) {
            $texts = array_filter(array_map('trim', explode(',', $textString ?? '')));
            $images = array_map('trim', explode(',', $imageString ?? ''));
            
            $mappedData = [];
            foreach ($texts as $index => $nama) {
                $img = (isset($images[$index]) && !empty($images[$index])) 
                       ? 'images/filters/' . $images[$index] 
                       : 'images/default/' . $defaultImagePrefix . (($index % 3) + 1) . '.jpg';
                
                $mappedData[] = ['nama' => $nama, 'gambar' => $img];
            }
            return $mappedData;
        };

        $filterPemerintah = $mapFilterData($landing->filter_pemerintah, $landing->image_pemerintah, 'pemerintah');
        $filterPendidikan = $mapFilterData($landing->filter_pendidikan, $landing->image_pendidikan, 'pendidikan');
        $filterPerusahaan = $mapFilterData($landing->filter_perusahaan, $landing->image_perusahaan, 'perusahaan');

        // Melimit agar di Beranda tampil maksimal 6 per kategori
        $kostPemda = Kost::where('kategori', 'Instansi Pemerintah')->take(6)->get();
        $kostUnsub = Kost::where('kategori', 'Universitas Subang')->take(6)->get();
        $kosts = Kost::take(6)->get(); 

        return view('home', compact('landing', 'filterPemerintah', 'filterPendidikan', 'filterPerusahaan', 'kostPemda', 'kostUnsub', 'kosts'));
    }

    // ========================================================
    // 1. MANAJEMEN LANDING (BACK-END)
    // ========================================================
    public function manage()
    {
        $landing = Landing::first() ?? Landing::create(['title' => 'SIKOSUB']);
        return view('superadmin.kelola-landing', compact('landing'));
    }

    public function updateInfo(Request $request)
    {
        $landing = Landing::first() ?? Landing::create(['title' => 'SIKOSUB']);
        $landing->update($request->validate([
            'tentang' => 'nullable|string',
            'visi'    => 'nullable|string',
            'misi'    => 'nullable|string',
        ]));
        return redirect()->back()->with('success', 'Profil berhasil diperbarui!');
    }

    public function updateFilter(Request $request, $category)
    {
        $landing = Landing::first() ?? Landing::create(['title' => 'SIKOSUB']);
        $textField = 'filter_' . $category;
        $imageField = 'image_' . $category;

        // 1. FITUR RESET: Jika tombol "Bersihkan Teks" ditekan
        if ($request->has('reset_text') && $request->reset_text == 'true') {
            $landing->$textField = null;
        } 
        // 2. FITUR TAMBAH/APPEND: Jika ada nama instansi baru yang diketik
        elseif ($request->has('name') && !empty(trim($request->name))) {
            $existingTexts = !empty($landing->$textField) ? trim($landing->$textField) : '';
            $newInput = trim($request->name);
            
            if (empty($existingTexts)) {
                $landing->$textField = $newInput;
            } else {
                $landing->$textField = $existingTexts . ', ' . $newInput;
            }
        }

        // 3. FITUR TAMBAH FOTO
        if ($request->hasFile('image')) {
            $existingPhotos = !empty($landing->$imageField) ? explode(',', $landing->$imageField) : [];
            foreach ($request->file('image') as $file) {
                $filename = $category . '_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('images/filters'), $filename);
                $existingPhotos[] = $filename;
            }
            $landing->$imageField = implode(',', $existingPhotos);
        }
        
        $landing->save();
        return redirect()->back()->with('success', 'Data filter berhasil ditambahkan!');
    }

    // ========================================================
    // 2. FITUR HAPUS FOTO
    // ========================================================
    public function deletePhoto($category, $photoIndex) 
    {
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
                
                $photos[$photoIndex] = ''; 
                $landing->$column = implode(',', $photos);
                $landing->save();
            }
        }
        return redirect()->back()->with('success', 'Foto berhasil dihapus satuan.');
    }

    public function clearAllPhotos($category) 
    {
        $landing = Landing::first();
        $column = 'image_' . $category;
        
        if ($landing && !empty($landing->$column)) {
            $photos = explode(',', $landing->$column);
            foreach ($photos as $photo) {
                @unlink(public_path('images/filters/' . trim($photo)));
            }
            $landing->$column = null;
            $landing->save();
        }
        return redirect()->back()->with('success', 'Semua foto kategori ' . $category . ' telah dihapus.');
    }

    // ========================================================
    // 3. FITUR BANNER
    // ========================================================
    public function uploadBanner(Request $request, $index) 
    {
        $landing = Landing::first() ?? Landing::create(['title' => 'SIKOSUB']);
        $banners = json_decode($landing->banner_image ?? '[]', true);

        if ($request->hasFile('banner_file')) {
            if (isset($banners[$index])) @unlink(public_path('images/banners/' . $banners[$index]));
            
            $name = time() . '_banner_' . $index . '.' . $request->banner_file->extension();
            $request->banner_file->move(public_path('images/banners'), $name);
            $banners[$index] = $name;
            
            $landing->banner_image = json_encode($banners);
            $landing->save();
        }
        return back()->with('success', 'Banner berhasil diunggah.');
    }

    public function deleteBanner($index) 
    {
        $landing = Landing::first();
        if ($landing) {
            $banners = json_decode($landing->banner_image ?? '[]', true);
            if (isset($banners[$index])) {
                @unlink(public_path('images/banners/' . $banners[$index]));
                unset($banners[$index]);
                $landing->banner_image = json_encode($banners);
                $landing->save();
            }
        }
        return back()->with('success', 'Banner berhasil dihapus.');
    }

    // ========================================================
    // 4. HALAMAN DAFTAR INSTANSI (LIHAT LEBIH BANYAK)
    // ========================================================
    public function daftarInstansi(Request $request, $kategoriParam = null)
    {
        // Tangkap kategori dari URL/Routing
        $kategori = $kategoriParam ?: $request->segment(2) ?: $request->query('kategori');
        
        if ($kategori == 'pendidikan') $kategori = 'instansi-pendidikan';
        if ($kategori == 'pemerintah') $kategori = 'instansi-pemerintah';
        if ($kategori == 'perusahaan') $kategori = 'instansi-perusahaan';

        // KUNCI UTAMA: Ambil data dari row pertama (Sama persis dengan Beranda)
        $landing = Landing::first() ?? Landing::create(['title' => 'SIKOSUB']);

        $map = function($textField, $imageField, $fallbackUrl) use ($landing) {
            if (!$landing) return [];
            
            $textData = $landing->getAttribute($textField);
            $imageData = $landing->getAttribute($imageField);

            $names = array_filter(array_map('trim', explode(',', $textData ?? '')));
            $images = array_map('trim', explode(',', $imageData ?? ''));
            
            $data = [];
            foreach ($names as $idx => $name) {
                $imageName = $images[$idx] ?? '';
                if (!empty($imageName) && file_exists(public_path('images/filters/' . $imageName))) {
                    $imgUrl = asset('images/filters/' . $imageName);
                } else {
                    $imgUrl = $fallbackUrl;
                }
                $data[] = ['nama' => $name, 'gambar' => $imgUrl];
            }
            return $data;
        };

        $list_data = [];
        $judul = 'Instansi';
        $icon = 'fa-building';

        if ($kategori == 'instansi-pendidikan') {
            $list_data = $map('filter_pendidikan', 'image_pendidikan', 'https://images.unsplash.com/photo-1523050854058-8df90110c9f1?auto=format&fit=crop&w=400&q=80');
            $judul = 'Instansi Pendidikan';
            $icon = 'fa-graduation-cap';
        } elseif ($kategori == 'instansi-pemerintah') {
            $list_data = $map('filter_pemerintah', 'image_pemerintah', 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?auto=format&fit=crop&w=400&q=80');
            $judul = 'Instansi Pemerintah';
            $icon = 'fa-building-columns';
        } elseif ($kategori == 'instansi-perusahaan') {
            $list_data = $map('filter_perusahaan', 'image_perusahaan', 'https://images.unsplash.com/photo-1542744173-8e7e53415bb0?auto=format&fit=crop&w=400&q=80');
            $judul = 'Instansi Perusahaan';
            $icon = 'fa-city';
        }

        return view('instansi.daftar', compact('kategori', 'judul', 'icon', 'list_data'));
    }
}