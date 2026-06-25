<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('kosts', function (Blueprint $table) {
            // Tambahkan kolom kategori_wilayah setelah kolom tertentu (misal: deskripsi)
            $table->string('kategori_wilayah')->nullable()->after('id'); 
        });
    }

    public function down()
    {
        Schema::table('kosts', function (Blueprint $table) {
            $table->dropColumn('kategori_wilayah');
        });
    }
};
