<?php

namespace Database\Seeders;

use App\Models\ProgramStudi;
use Illuminate\Database\Seeder;

class ProgramStudiSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['kode' => 'TI', 'nama' => 'Teknik Informatika', 'jenjang' => 'S1'],
            ['kode' => 'SI', 'nama' => 'Sistem Informasi', 'jenjang' => 'S1'],
            ['kode' => 'MI', 'nama' => 'Manajemen Informatika', 'jenjang' => 'D3'],
            ['kode' => 'RPL', 'nama' => 'Rekayasa Perangkat Lunak', 'jenjang' => 'D4'],
        ];

        foreach ($data as $item) {
            ProgramStudi::firstOrCreate(['kode' => $item['kode']], $item);
        }
    }
}
