<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// Model utama: data pendaftaran mahasiswa baru (struktur sesuai F.2).
class Pendaftaran extends Model
{
    protected $fillable = [
        'user_id',
        'nomor_pendaftaran',
        'nama_lengkap',
        'nik',
        'alamat_ktp',
        'alamat_domisili',
        'province_id',
        'regency_id',
        'kecamatan',
        'kode_pos',
        'nomor_telepon',
        'nomor_hp',
        'email',
        'kewarganegaraan',
        'negara_asal',
        'tanggal_lahir',
        'tempat_lahir',
        'jenis_kelamin',
        'status_perkawinan',
        'religion_id',
        'program_studi_1_id',
        'program_studi_2_id',
        'gelombang_id',
        'foto',
        'asal_sekolah',
        'jurusan_asal_sekolah',
        'tahun_lulus',
        'nilai_rata_rata',
        'jalur_pendaftaran',
        'status_pendaftaran',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function religion()
    {
        return $this->belongsTo(Religion::class);
    }

    public function province()
    {
        return $this->belongsTo(Province::class);
    }

    public function regency()
    {
        return $this->belongsTo(Regency::class);
    }

    // Program studi pilihan pertama.
    public function programStudi1()
    {
        return $this->belongsTo(ProgramStudi::class, 'program_studi_1_id');
    }

    // Program studi pilihan kedua.
    public function programStudi2()
    {
        return $this->belongsTo(ProgramStudi::class, 'program_studi_2_id');
    }

    public function gelombang()
    {
        return $this->belongsTo(Gelombang::class);
    }

    // Generator nomor pendaftaran unik, format: PMB-2026-000001
    public static function generateNomorPendaftaran(): string
    {
        $tahun = date('Y');
        $terakhir = self::where('nomor_pendaftaran', 'like', "PMB-{$tahun}-%")
            ->orderByDesc('id')
            ->first();

        $urutan = 1;
        if ($terakhir) {
            $bagian = explode('-', $terakhir->nomor_pendaftaran);
            $urutan = (int) end($bagian) + 1;
        }

        return sprintf('PMB-%s-%06d', $tahun, $urutan);
    }
}
