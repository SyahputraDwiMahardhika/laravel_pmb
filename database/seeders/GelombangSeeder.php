<?php

namespace Database\Seeders;

use App\Models\Gelombang;
use Illuminate\Database\Seeder;

class GelombangSeeder extends Seeder
{
    public function run(): void
    {
        $tahun = date('Y');

        $data = [
            ['nama' => 'Gelombang 1', 'tanggal_mulai' => "{$tahun}-01-01", 'tanggal_selesai' => "{$tahun}-03-31", 'aktif' => true],
            ['nama' => 'Gelombang 2', 'tanggal_mulai' => "{$tahun}-04-01", 'tanggal_selesai' => "{$tahun}-06-30", 'aktif' => true],
            ['nama' => 'Gelombang 3', 'tanggal_mulai' => "{$tahun}-07-01", 'tanggal_selesai' => "{$tahun}-09-30", 'aktif' => false],
        ];

        foreach ($data as $item) {
            Gelombang::firstOrCreate(['nama' => $item['nama']], $item);
        }
    }
}
