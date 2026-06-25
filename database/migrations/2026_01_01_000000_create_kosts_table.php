<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kosts', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kost')->nullable();
            $table->string('tipe_kost')->nullable();
            $table->integer('harga_per_bulan')->nullable();
            $table->integer('harga_diskon')->default(0);
            $table->text('alamat')->nullable();
            $table->string('no_wa')->nullable();
            $table->text('maps')->nullable();
            $table->string('gambar_utama')->nullable();
            $table->float('rating')->default(5.0);
            $table->timestamps();
            
            // Catatan: Kolom wilayah, kategori_wilayah, visit_count, dll 
            // sengaja tidak dimasukkan ke sini karena sudah diurus oleh 
            // file migrasi Anda yang lain.
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kosts');
    }
};