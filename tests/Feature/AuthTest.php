<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Mitra;
use App\Models\SuperAdmin;
use Illuminate\Foundation\Testing\DatabaseTransactions; 
use Tests\TestCase;

class AuthTest extends TestCase
{
    use DatabaseTransactions; 

    /** @test */
    public function pencari_kost_bisa_mengakses_halaman_utama()
    {
        // Langsung pinjam data User pertama yang ada di database
        $user = User::first(); 
        $this->actingAs($user, 'web');
        
        $response = $this->get('/');
        $response->assertStatus(200);
    }

    /** @test */
    public function mitra_bisa_mengakses_dashboard_mitra()
    {
        // Langsung pinjam data Mitra pertama dari database
        $mitra = Mitra::first(); 
        $this->actingAs($mitra, 'mitra');
        
        $response = $this->get('/mitra/dashboard');
        // Asumsi dashboard Mitra normal adalah 200 OK
        $response->assertStatus(200); 
    }

    /** @test */
    public function superadmin_tidak_bisa_akses_halaman_mitra()
    {
        // Pinjam data SuperAdmin pertama
        $admin = SuperAdmin::first();
        $this->actingAs($admin, 'superadmin');
        
        $response = $this->get('/mitra/dashboard');
        // Sistem menolak admin masuk ke dashboard mitra (redirect 302)
        $response->assertStatus(302); 
    }

    /** @test */
    public function logout_universal_bekerja_dengan_benar()
    {
        $user = User::first();
        $this->actingAs($user, 'web');
        
        $response = $this->post('/logout');
        $response->assertStatus(302); // Redirect sukses logout
        $this->assertGuest('web');
    }
}