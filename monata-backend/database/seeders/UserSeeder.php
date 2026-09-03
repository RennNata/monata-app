<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin Utama Monata',
            'nis_nip' => '1234567890',
            'password' => Hash::make('admin1234'),
            'role' => 'admin'
        ]);
    }
}
