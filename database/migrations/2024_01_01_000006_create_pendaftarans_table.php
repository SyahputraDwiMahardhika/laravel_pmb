<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Tabel utama pendaftaran mahasiswa baru.
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pendaftarans', function (Blueprint $table) {
            $table->id();

            // Relasi ke user (akun calon mahasiswa yang mendaftar)
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();

            $table->string('nomor_pendaftaran', 30)->unique();

            // Data pribadi
            $table->string('nama_lengkap', 150);
            $table->string('nik', 20);
            $table->string('tempat_lahir', 100);
            $table->date('tanggal_lahir');
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->foreignId('religion_id')->constrained('religions');
            $table->string('nomor_hp', 20);
            $table->string('email', 150);
            $table->string('foto')->nullable(); // path file foto di storage

            // Data alamat
            $table->text('alamat');
            $table->foreignId('province_id')->constrained('provinces');
            $table->foreignId('regency_id')->constrained('regencies');
            $table->string('kecamatan', 100);
            $table->string('kelurahan', 100);
            $table->string('kode_pos', 10);

            // Data pendidikan
            $table->string('asal_sekolah', 150);
            $table->string('jurusan_asal_sekolah', 100);
            $table->year('tahun_lulus');
            $table->decimal('nilai_rata_rata', 5, 2);

            // Data pilihan
            $table->foreignId('program_studi_id')->constrained('program_studis');
            $table->enum('jalur_pendaftaran', ['Reguler', 'Beasiswa', 'Mandiri'])->default('Reguler');

            // Status
            $table->enum('status_pendaftaran', ['Menunggu', 'Diverifikasi', 'Diterima', 'Ditolak'])
                  ->default('Menunggu');

            $table->timestamps();

            $table->index('status_pendaftaran');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pendaftarans');
    }
};
