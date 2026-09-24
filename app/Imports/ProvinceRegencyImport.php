<?php

namespace App\Imports;

use App\Models\Province;
use App\Models\Regency;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

/**
 * Importer untuk file Excel ASLI yang diberikan pengguna:
 * "daftar-kabupaten-kota-di-indonesia-excel.xlsx"
 *
 * FORMAT FILE INI BUKAN TABEL KOLOM, melainkan daftar teks 1 kolom (Sheet1,
 * kolom A) berbentuk narasi bernomor, contoh isinya persis seperti ini:
 *
 *   "1. Daftar Nama Kabupaten dan Kota di Provinsi NAD Aceh :"
 *   (baris kosong)
 *   "1. Kabupaten Aceh Barat"
 *   "2. Kabupaten Aceh Barat Daya"
 *   ...
 *   "19. Kota Banda Aceh"
 *   (baris kosong)
 *   "2. Daftar Nama Kabupaten dan Kota di Provinsi Sumatera Utara (SUMUT):"
 *   ...dst untuk 34 provinsi.
 *
 * Importer ini mem-parsing pola tersebut baris per baris:
 * - Baris yang match "Daftar Nama Kabupaten dan Kota di Provinsi <NAMA>"
 *   dianggap sebagai judul/header provinsi baru.
 * - Baris "<angka>. Kabupaten/Kota <nama>" di bawah header dianggap sebagai
 *   kabupaten/kota milik provinsi tersebut.
 *
 * CATATAN PENTING soal kolom "code": file sumber TIDAK menyediakan kode resmi
 * BPS/Kemendagri untuk provinsi maupun kabupaten/kota, hanya nama. Kolom
 * `code` pada tabel provinces/regencies karena itu diisi kode INTERNAL
 * buatan (PROV-01, PROV-01-001, dst) hanya sebagai primary key alternatif
 * yang unik — BUKAN kode resmi pemerintah. Ini didokumentasikan juga di
 * README agar tidak disalahartikan sebagai data resmi yang dikarang.
 */
class ProvinceRegencyImport implements ToCollection
{
    /** Baris yang tidak berhasil dikenali polanya (untuk pelaporan). */
    public array $skipped = [];

    private ?Province $currentProvince = null;
    private int $provinceCounter = 0;
    private int $regencyCounter = 0;

    public function collection(Collection $rows): void
    {
        foreach ($rows as $index => $row) {
            $text = trim((string) ($row[0] ?? ''));

            if ($text === '') {
                continue; // baris kosong pemisah antar-provinsi
            }

            // Lewati judul dokumen & baris sumber di paling atas/bawah file.
            if (str_contains(strtolower($text), 'daftar provinsi, kabupaten')
                || str_contains(strtolower($text), 'sumber oleh')) {
                continue;
            }

            // Header provinsi baru, contoh:
            // "2. Daftar Nama Kabupaten dan Kota di Provinsi Sumatera Utara (SUMUT):"
            if (preg_match('/Daftar\s+Nama\s+Kabupaten\s+dan\s+Kota\s+di\s+Provinsi\s+(.+?)\s*:?\s*$/i', $text, $m)) {
                $this->provinceCounter++;
                $namaProvinsi = $this->bersihkanNamaProvinsi($m[1]);

                $this->currentProvince = Province::firstOrCreate(
                    ['name' => $namaProvinsi],
                    ['code' => 'PROV-' . str_pad($this->provinceCounter, 2, '0', STR_PAD_LEFT)]
                );

                $this->regencyCounter = 0;
                continue;
            }

            // Baris kabupaten/kota, contoh: "19. Kota Banda Aceh"
            if ($this->currentProvince
                && preg_match('/^\d+\.\s*(Kabupaten|Kota|Kab\.)\s*(.+)$/i', $text, $m)) {
                $this->regencyCounter++;
                $namaKabupatenKota = trim($m[1]) . ' ' . trim($m[2]);

                Regency::firstOrCreate(
                    ['name' => $namaKabupatenKota, 'province_id' => $this->currentProvince->id],
                    ['code' => $this->currentProvince->code . '-' . str_pad($this->regencyCounter, 3, '0', STR_PAD_LEFT)]
                );
                continue;
            }

            // Baris yang tidak cocok pola apa pun (mis. format tak terduga) dicatat, tidak dikarang.
            $this->skipped[] = ($index + 1) . ': ' . $text;
        }
    }

    // Menghapus singkatan dalam kurung, contoh "Sumatera Utara (SUMUT)" -> "Sumatera Utara"
    private function bersihkanNamaProvinsi(string $nama): string
    {
        $nama = preg_replace('/\s*\([^)]*\)\s*/', '', $nama);
        return trim($nama);
    }
}
