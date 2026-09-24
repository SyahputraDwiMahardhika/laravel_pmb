<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// Model referensi program studi.
class ProgramStudi extends Model
{
    protected $fillable = ['kode', 'nama', 'jenjang'];

    public function pendaftarans()
    {
        return $this->hasMany(Pendaftaran::class);
    }
}
