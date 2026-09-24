<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// Model referensi/lookup kabupaten/kota.
class Regency extends Model
{
    protected $fillable = ['code', 'province_id', 'name'];

    public function province()
    {
        return $this->belongsTo(Province::class);
    }

    public function pendaftarans()
    {
        return $this->hasMany(Pendaftaran::class);
    }
}
