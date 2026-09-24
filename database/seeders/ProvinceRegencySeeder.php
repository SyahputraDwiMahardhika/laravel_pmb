<?php

namespace Database\Seeders;

use App\Imports\ProvinceRegencyImport;
use App\Models\Province;
use App\Models\Regency;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

/**
 * Mengimpor data provinsi & kabupaten/kota dari file Excel ASLI yang
 * diberikan pengguna, sudah disertakan langsung di:
 *   database/data/daftar-kabupaten-kota-di-indonesia-excel.xlsx
 *
 * Format & cara parsing file ini dijelaskan lengkap di
 * App\Imports\ProvinceRegencyImport (file 1 kolom, narasi bernomor per
 * provinsi — bukan tabel kode/nama).
 *
 * FALLBACK: jika file Excel tidak ditemukan di path di atas (mis. Anda
 * memindahkan/menghapusnya), seeder memakai 6 baris data contoh minimal
 * agar dropdown provinsi/kabupaten tetap berfungsi untuk demo cepat.
 */
class ProvinceRegencySeeder extends Seeder
{
    public function run(): void
    {
        $excelPath = database_path('data/daftar-kabupaten-kota-di-indonesia-excel.xlsx');

        if (file_exists($excelPath)) {
            $import = new ProvinceRegencyImport();
            Excel::import($import, $excelPath);

            if (! empty($import->skipped)) {
                Log::warning('ProvinceRegencySeeder: baris tidak dikenali polanya: ' . implode(' | ', $import->skipped));
            }

            $jumlahProvinsi = Province::count();
            $jumlahRegency = Regency::count();
            $this->command->info("Data berhasil diimpor dari Excel: {$jumlahProvinsi} provinsi, {$jumlahRegency} kabupaten/kota.");
            return;
        }

        $this->command->warn('File Excel tidak ditemukan di ' . $excelPath . '. Menggunakan data contoh minimal (fallback).');
        $this->seedFallback();
    }

    private function seedFallback(): void
    {
        $sample = [
            ['prov_code' => 'PROV-01', 'prov_name' => 'DKI Jakarta', 'reg_code' => 'PROV-01-001', 'reg_name' => 'Kota Jakarta Selatan'],
            ['prov_code' => 'PROV-01', 'prov_name' => 'DKI Jakarta', 'reg_code' => 'PROV-01-002', 'reg_name' => 'Kota Jakarta Timur'],
            ['prov_code' => 'PROV-02', 'prov_name' => 'Jawa Barat', 'reg_code' => 'PROV-02-001', 'reg_name' => 'Kabupaten Bogor'],
            ['prov_code' => 'PROV-02', 'prov_name' => 'Jawa Barat', 'reg_code' => 'PROV-02-002', 'reg_name' => 'Kota Bandung'],
            ['prov_code' => 'PROV-03', 'prov_name' => 'Jawa Tengah', 'reg_code' => 'PROV-03-001', 'reg_name' => 'Kota Semarang'],
            ['prov_code' => 'PROV-04', 'prov_name' => 'Jawa Timur', 'reg_code' => 'PROV-04-001', 'reg_name' => 'Kota Surabaya'],
        ];

        foreach ($sample as $row) {
            $province = Province::firstOrCreate(
                ['code' => $row['prov_code']],
                ['name' => $row['prov_name']]
            );

            Regency::firstOrCreate(
                ['code' => $row['reg_code']],
                ['province_id' => $province->id, 'name' => $row['reg_name']]
            );
        }
    }
}
