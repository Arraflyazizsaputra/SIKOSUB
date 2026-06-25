<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    
    // Tetap gunakan tbl_user sebagai tabel sistem login utama (Pencari Kost)
    protected $table = 'tbl_user';
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'foto_profil', // <-- KUNCI PERBAIKAN: Menambahkan kolom foto_profil agar diizinkan disimpan
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    // ============================================================
    // RELATIONS
    // ============================================================

    public function bookmarks()
    {
        return $this->hasMany(Bookmark::class, 'user_id');
    }
}