<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// Model utama: data pendaftaran mahasiswa baru.
class Pendaftaran extends Model
{
    protected $fillable = [
        'user_id',
        'nomor_pendaftaran',
        'nama_lengkap',
        'nik',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'religion_id',
        'nomor_hp',
        'email',
        'foto',
        'alamat',
        'province_id',
        'regency_id',
        'kecamatan',
        'kelurahan',
        'kode_pos',
        'asal_sekolah',
        'jurusan_asal_sekolah',
        'tahun_lulus',
        'nilai_rata_rata',
        'program_studi_id',
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

    public function programStudi()
    {
        return $this->belongsTo(ProgramStudi::class);
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
