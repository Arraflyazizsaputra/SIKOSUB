<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Mitra;
use App\Models\SuperAdmin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // ============================================================
    // HELPER: Redirect jika sudah login ke dashboard yang sesuai
    // ============================================================

    private function redirectIfAuthenticated(string $guard): ?\Illuminate\Http\RedirectResponse
    {
        if (Auth::guard($guard)->check()) {
            return match ($guard) {
                'superadmin' => redirect()->route('superadmin.dashboard'),
                'mitra'      => redirect()->route('mitra.dashboard'),
                'web'        => redirect()->route('home'), // Default pencari kost
                default      => redirect()->route('home'),
            };
        }
        return null;
    }

    // ============================================================
    // 1. PENCARI KOST (GUARD: web)
    // ============================================================

    public function showLogin()
    {
        if ($redirect = $this->redirectIfAuthenticated('web')) {
            return $redirect;
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        // Gunakan guard 'web' secara spesifik
        if (Auth::guard('web')->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('home');
        }

        return back()->withErrors([
            'email' => 'Email atau Password salah.',
        ])->onlyInput('email');
    }

    public function showRegister()
    {
        if ($redirect = $this->redirectIfAuthenticated('web')) {
            return $redirect;
        }
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:tbl_user',
            'phone'    => 'nullable|string|max:20',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Simpan menggunakan Model User (yang mengarah ke tbl_user)
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'phone'    => $request->phone,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('login')->with('success', 'Registrasi berhasil! Silakan login dengan akun Anda, ' . $user->name . '.');
    }

    // ============================================================
    // 2. MITRA / PEMILIK KOST (GUARD: mitra)
    // ============================================================

    public function showMitraLogin()
    {
        if ($redirect = $this->redirectIfAuthenticated('mitra')) {
            return $redirect;
        }
        return view('auth.mitra_login');
    }

    public function mitraLogin(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::guard('mitra')->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('mitra.dashboard');
        }

        return back()->withErrors([
            'email' => 'Email atau Password Mitra salah.',
        ])->onlyInput('email');
    }

    public function showMitraRegister()
    {
        if ($redirect = $this->redirectIfAuthenticated('mitra')) {
            return $redirect;
        }
        return view('auth.mitra_register');
    }

    public function mitraRegister(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:mitra', 
            'phone'    => 'required|string|max:20',
            'password' => 'required|string|min:8|confirmed',
        ]);

        Mitra::create([
            // PERBAIKAN: Menarik data dari input 'name' di form registrasi
            'nama_pemilik' => $request->name, 
            'name'         => $request->name, // Ditambahkan sebagai cadangan jika database butuh kolom 'name'
            'email'        => $request->email,
            'phone'        => $request->phone,
            'password'     => Hash::make($request->password),
        ]);

        return redirect()->route('mitra.login')
            ->with('success', 'Registrasi Mitra berhasil! Silakan login dengan akun Anda.');
    }

    public function mitraDashboard()
    {
        return view('mitra.dashboard', ['user' => Auth::guard('mitra')->user()]);
    }

    // ============================================================
    // 3. SUPER ADMIN (GUARD: superadmin)
    // ============================================================

    public function showSuperadminLogin()
    {
        if ($redirect = $this->redirectIfAuthenticated('superadmin')) {
            return $redirect;
        }
        return view('auth.superadmin_login');
    }

    public function superadminLogin(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::guard('superadmin')->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('superadmin.dashboard');
        }

        return back()->withErrors([
            'email' => 'Kredensial Super Admin tidak valid.',
        ])->onlyInput('email');
    }

    public function superadminDashboard()
    {
        return view('superadmin.dashboard', ['user' => Auth::guard('superadmin')->user()]);
    }

    // ============================================================
    // PERBAIKAN: LOGOUT UNIVERSAL (MULTI-GUARD) YANG OPTIMAL
    // ============================================================

    public function logout(Request $request)
    {
        // 1. Deteksi "Guard" mana yang sedang aktif SAAT tombol ditekan
        $isSuperadmin = Auth::guard('superadmin')->check();
        $isMitra      = Auth::guard('mitra')->check();
        $isWeb        = Auth::guard('web')->check();

        // 2. Lakukan proses pengeluaran (logout) pada akun yang terdeteksi
        if ($isSuperadmin) {
            Auth::guard('superadmin')->logout();
        }
        if ($isMitra) {
            Auth::guard('mitra')->logout();
        }
        if ($isWeb) {
            Auth::guard('web')->logout();
        }

        // 3. Bersihkan seluruh sisa memori sesi di browser untuk keamanan mutlak
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // 4. Lakukan pengalihan (Redirect) ke "Pintu Keluar" yang benar
        if ($isSuperadmin) {
            return redirect()->route('superadmin.login')->with('success', 'Anda berhasil keluar dari Panel Superadmin.');
        } elseif ($isMitra) {
            return redirect()->route('mitra.login')->with('success', 'Anda berhasil keluar dari Dasbor Mitra.');
        } else {
            return redirect('/')->with('success', 'Anda telah berhasil keluar.');
        }
    }

    // ============================================================
    // EDIT PROFILE & PASSWORD (DIPERBAIKI DENGAN UPLOAD FOTO)
    // ============================================================

    public function editProfile()
    {
        $user = Auth::guard('web')->user();
        return view('profile.edit', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        // Deteksi apakah yang update profil ini Mitra atau Pencari Kost
        if (Auth::guard('mitra')->check()) {
            $user = Auth::guard('mitra')->user();
            $table = 'mitra'; 
        } else {
            $user = Auth::guard('web')->user();
            $table = 'tbl_user';
        }

        $request->validate([
            'name'   => 'required|string|max:255',
            'email'  => 'required|email|max:255|unique:' . $table . ',email,' . $user->id,
            'phone'  => 'nullable|string|max:20',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Validasi file gambar
        ]);

        $data = $request->only('name', 'email', 'phone');
        
        // PERBAIKAN: Jika yang update adalah mitra, pastikan kolom 'nama_pemilik' juga ikut diperbarui
        if (Auth::guard('mitra')->check()) {
            $data['nama_pemilik'] = $request->name;
        }

        // LOGIKA MENGHAPUS FOTO PROFIL (Jika user klik Hapus Foto)
        if ($request->remove_avatar == '1') {
            if ($user->foto_profil && file_exists(public_path('storage/avatars/' . $user->foto_profil))) {
                unlink(public_path('storage/avatars/' . $user->foto_profil)); // Hapus dari server
            }
            $data['foto_profil'] = null; // Kosongkan di database
        }

        // LOGIKA UPLOAD FOTO PROFIL BARU
        if ($request->hasFile('avatar')) {
            // Hapus foto lama dulu agar server tidak kepenuhan
            if ($user->foto_profil && file_exists(public_path('storage/avatars/' . $user->foto_profil))) {
                unlink(public_path('storage/avatars/' . $user->foto_profil));
            }

            // Ganti nama file agar unik dan simpan ke folder public/storage/avatars
            $file = $request->file('avatar');
            $filename = time() . '_' . uniqid() . '.' . $file->extension();
            $file->move(public_path('storage/avatars'), $filename);
            
            // Masukkan nama file ke array data untuk disimpan ke database
            $data['foto_profil'] = $filename;
        }

        $user->update($data);

        return redirect()->back()->with('success', 'Profil berhasil diperbarui!');
    }

    public function bookmarks()
    {
        return view('profile.bookmarks');
    }

    // FITUR SPESIFIK MITRA
    public function editPassword()
    {
        return view('mitra.profile-password', ['user' => Auth::guard('mitra')->user()]);
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::guard('mitra')->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password saat ini tidak cocok dengan catatan kami.']);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return back()->with('success', 'Password keamanan Anda berhasil diperbarui!');
    }

    public function mitraProfileEdit()
    {
        return view('mitra.profile-edit', ['user' => Auth::guard('mitra')->user()]);
    }

    public function mitraPasswordEdit()
    {
        return view('mitra.profile-password', ['user' => Auth::guard('mitra')->user()]);
    }

    public function mitraDashboardList()
    {
        return view('superadmin.mitra-dashboard'); 
    }

    public function manageUsers()
    {
        return "Halaman Manajemen User Sedang Dikonfigurasi";
    }
}