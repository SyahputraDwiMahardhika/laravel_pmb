<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// Model referensi/lookup gelombang pendaftaran.
class Gelombang extends Model
{
    protected $fillable = ['nama', 'tanggal_mulai', 'tanggal_selesai', 'aktif'];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'aktif' => 'boolean',
    ];

    public function pendaftarans()
    {
        return $this->hasMany(Pendaftaran::class);
    }
}
