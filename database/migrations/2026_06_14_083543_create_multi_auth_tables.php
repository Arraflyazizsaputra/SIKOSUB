<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Tabel untuk Pencari Kost (Menggunakan nama 'users')
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });

        // 2. Tabel untuk Pemilik Kost / Mitra (Tabel 'mitras')
        Schema::create('mitras', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });

        // 3. Tabel untuk Super Admin (Tabel 'super_admins')
        Schema::create('super_admins', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });

        // 4. TAMBAHKAN DI SINI: Tabel Landings dengan tipe data TEXT
        Schema::create('landings', function (Blueprint $table) {
            $table->id();
            
            // Menggunakan tipe text agar muat banyak karakter teks input nama instansi
            $table->text('filter_pendidikan')->nullable();
            $table->text('filter_pemerintah')->nullable();
            $table->text('filter_perusahaan')->nullable();
            
            // Menggunakan tipe text agar muat banyak nama file foto yang diunggah sekaligus
            $table->text('image_pendidikan')->nullable();
            $table->text('image_pemerintah')->nullable();
            $table->text('image_perusahaan')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Hapus tabel landings jika migrasi di-rollback
        Schema::dropIfExists('landings');
        Schema::dropIfExists('super_admins');
        Schema::dropIfExists('mitras');
        Schema::dropIfExists('users');
    }
};