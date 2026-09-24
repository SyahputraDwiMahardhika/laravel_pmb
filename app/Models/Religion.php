<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// Model referensi/lookup agama.
class Religion extends Model
{
    protected $fillable = ['name'];

    public function pendaftarans()
    {
        return $this->hasMany(Pendaftaran::class);
    }
}
