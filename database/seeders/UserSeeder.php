<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

// Membuat akun demo untuk uji kompetensi: 1 admin, 1 calon mahasiswa.
class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@pmb.test'],
            [
                'name' => 'Administrator PMB',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        User::firstOrCreate(
            ['email' => 'mahasiswa@pmb.test'],
            [
                'name' => 'Calon Mahasiswa Demo',
                'password' => Hash::make('password'),
                'role' => 'mahasiswa',
            ]
        );
    }
}
