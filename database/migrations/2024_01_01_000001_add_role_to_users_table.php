<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Menambahkan kolom 'role' pada tabel users bawaan Laravel.
 *
 * KEPUTUSAN DESAIN (didokumentasikan juga di README):
 * Soal menyebutkan tabel "roles/role". Untuk kesederhanaan dan karena hanya
 * ada 2 peran (admin & calon mahasiswa), kita gunakan kolom enum 'role'
 * pada tabel users, bukan tabel roles terpisah. Ini tetap memenuhi
 * kebutuhan lookup peran tanpa relasi many-to-many yang tidak diperlukan.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin', 'mahasiswa'])->default('mahasiswa')->after('email');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
        });
    }
};
