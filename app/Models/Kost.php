<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kost extends Model
{
    use HasFactory;

    protected $table = 'kosts'; 

    /**
     * KUNCI PERBAIKAN: 
     * Menggunakan guarded kosong mengizinkan semua kolom diisi secara massal
     * (Mencegah error 'Add [column] to fillable property').
     */
    protected $guarded = [];

    /**
     * KUNCI PERBAIKAN: 
     * Nama kolom database adalah 'gallery_images', bukan 'foto_galeri'.
     * Ini memastikan Laravel otomatis mengubah JSON database menjadi array PHP.
     */
    protected $casts = [
        'gallery_images' => 'array', 
    ];

    // ============================================================
    // RELASI DATABASE
    // ============================================================

    public function GameMitra()
    {
        return $this->belongsTo(Mitra::class, 'mitra_id');
    }

    public function bookmarks() 
    {
        return $this->hasMany(Bookmark::class, 'kost_id');
    }

    public function reviews() 
    {
        return $this->hasMany(Review::class, 'kost_id');
    }

    // Accessor untuk mendapatkan rata-rata rating
    public function getAverageRatingAttribute() 
    {
        return $this->reviews()->avg('rating') ?? 0;
    }
}