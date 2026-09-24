<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Tabel referensi/lookup agama.
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('religions', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50); // Nama agama, contoh: Islam, Kristen, dll.
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('religions');
    }
};
