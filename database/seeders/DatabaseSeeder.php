<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Jalankan SuperAdminSeeder untuk membuat akun super admin default
        $this->call([
            SuperAdminSeeder::class,
        ]);
    }
}
