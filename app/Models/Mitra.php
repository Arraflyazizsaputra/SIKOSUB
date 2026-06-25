<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Mitra extends Authenticatable
{
    use Notifiable;

    // Menegaskan nama tabel agar Laravel tidak bingung
    protected $table = 'mitras';

    // GERBANG KEAMANAN: Mendaftarkan kolom yang diizinkan untuk diisi data
    protected $fillable = [
        'user_id', 
        'nama_pemilik', 
        'nama_bisnis', 
        'no_rekening', 
        'status_verifikasi',
        'email',
        'phone',     // <-- PERBAIKAN: Kolom phone ditambahkan agar nomor HP bisa masuk
        'password', 
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'password' => 'hashed',
    ];
}