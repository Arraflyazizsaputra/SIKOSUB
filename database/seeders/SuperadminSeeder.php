<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class SuperadminSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'name'     => 'Admin SIKOSUB',
            'email'    => 'sikosub1@gmail.com',
            'password' => Hash::make('sikosub123'),
            'role'     => 'super_admin'
        ]);
    }
}