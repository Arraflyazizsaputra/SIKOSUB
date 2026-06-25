<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bookmark extends Model
{
    use HasFactory;

    /**
     * Nama tabel di database. 
     */
    protected $table = 'bookmarks';

    /**
     * KUNCI PERBAIKAN: 
     * Menggunakan guarded kosong artinya kita mengizinkan SEMUA kolom 
     * untuk diisi (Sangat ampuh mencegah error Mass Assignment).
     */
    protected $guarded = [];

    /**
     * Relasi ke model User.
     * Bookmark ini milik siapa?
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relasi ke model Kost.
     * Bookmark ini merujuk ke kost yang mana?
     */
    public function kost()
    {
        return $this->belongsTo(Kost::class, 'kost_id');
    }
}