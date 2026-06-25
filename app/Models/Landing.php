<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Landing extends Model
{
    use HasFactory;

    // Menentukan nama tabel secara eksplisit
    protected $table = 'landings';

    /**
     * Kolom-kolom yang diizinkan untuk pengisian massal (Mass Assignment).
     * Ini wajib diisi agar fungsi 'firstOrCreate' dan 'update' di Controller dapat berjalan dengan aman.
     */
    protected $fillable = [
        'title',
        'tentang',
        'visi',
        'misi',
        'banner_image',
        'filter_pendidikan',
        'image_pendidikan',
        'filter_pemerintah',
        'image_pemerintah',
        'filter_perusahaan',
        'image_perusahaan'
    ];

    
}