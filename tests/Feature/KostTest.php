<?php

namespace Tests\Feature;

use App\Models\Mitra;
// Wajib memanggil fitur pembuat file palsu dari Laravel
use Illuminate\Http\UploadedFile; 
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class KostTest extends TestCase
{
    use DatabaseTransactions; 

    /** @test */
    public function mitra_dapat_menambah_kost_baru()
    {
        $mitra = Mitra::first();
        $this->actingAs($mitra, 'mitra');

        $response = $this->post('/mitra/kost/store', [
            'nama_kost' => 'Kost Test Pengujian',
            'tipe_kost' => 'putra',
            'harga_per_bulan' => 500000,
            'alamat' => 'Jl. Uji Coba',
            'no_wa' => '08123456789',
            'kategori_wilayah' => 'Soklat',
            'jumlah_kamar' => 1,
            'sisa_kamar' => 1,
            
            // --- INI SOLUSINYA ---
            // Kita buatkan simulasi upload foto palsu berformat JPG
            'gambar_utama' => UploadedFile::fake()->image('foto_kost.jpg'),
            // ---------------------
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertStatus(302); 
        $this->assertDatabaseHas('kosts', ['nama_kost' => 'Kost Test Pengujian']); 
    }

    /** @test */
    public function sistem_menolak_akses_tamu_ke_tambah_kost()
    {
        $response = $this->get('/mitra/kost/create');
        $response->assertRedirect('/login'); 
    }
}