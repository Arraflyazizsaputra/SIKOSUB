<?php

namespace Tests\Feature;

use App\Models\Mitra;
use App\Models\Landing;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class SecurityTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function sistem_mencegah_input_nama_kost_melebihi_batas_karakter()
    {
        $mitra = Mitra::first();
        $this->actingAs($mitra, 'mitra');

        // Simulasi orang iseng mengetik nama kost sepanjang 300 huruf (Melebihi batas database)
        $namaKostPanjang = str_repeat('A', 300); 

        $response = $this->post('/mitra/kost/store', [
            'nama_kost' => $namaKostPanjang,
            'tipe_kost' => 'putra',
            'harga_per_bulan' => 500000,
        ]);

        // Memastikan sistem menolak dan memberikan error validasi pada kolom 'nama_kost'
        $response->assertSessionHasErrors(['nama_kost']);
    }

    /** @test */
    public function sistem_mencegah_serangan_xss_pada_halaman_utama()
    {
        // Inject script hacker berbahaya langsung ke dalam database
        $landing = Landing::first();
        $scriptHacker = '<script>alert("SIKOSUB di-Hack!")</script>';
        
        $landing->misi = $scriptHacker;
        $landing->save();

        // Kunjungi halaman utama publik
        $response = $this->get('/');

        // Karena di home.blade.php kita sudah memakai fungsi {!! nl2br(e($landing->misi)) !!},
        // maka sistem akan mengubah (escape) tag <script> menjadi teks aman &lt;script&gt;
        $response->assertSee('&lt;script&gt;alert(&quot;SIKOSUB di-Hack!&quot;)&lt;/script&gt;', false);
        
        // Memastikan script HTML murni TIDAK ikut ter-render (Anti-XSS Berhasil)
        $response->assertDontSee($scriptHacker, false);
    }
}