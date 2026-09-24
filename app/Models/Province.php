<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// Model referensi/lookup provinsi.
class Province extends Model
{
    protected $fillable = ['code', 'name'];

    // Satu provinsi punya banyak kabupaten/kota.
    public function regencies()
    {
        return $this->hasMany(Regency::class);
    }

    public function pendaftarans()
    {
        return $this->hasMany(Pendaftaran::class);
    }
}
