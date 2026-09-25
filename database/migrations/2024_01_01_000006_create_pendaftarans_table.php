<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Tabel utama pendaftaran mahasiswa baru — struktur field mengikuti
 * dokumen soal "F.2 Form Pendaftaran Mahasiswa Baru" persis, ditambah
 * beberapa field pelengkap (asal_sekolah, jurusan, tahun_lulus, nilai,
 * jalur_pendaftaran, foto) yang diminta pada bagian requirement umum
 * (data pendidikan & multimedia foto) namun tidak tercantum di tabel F.2 —
 * ditandai jelas pada komentar di bawah agar tidak tertukar.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pendaftarans', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();

            // Otomatis, unik, read-only setelah kirim.
            $table->string('nomor_pendaftaran', 30)->unique();

            $table->string('nama_lengkap', 150);
            $table->string('nik', 16)->unique(); // 16 digit, unik

            $table->string('alamat_ktp', 255);
            $table->string('alamat_domisili', 255);

            // Provinsi & kabupaten/kota mengikuti alamat domisili.
            $table->foreignId('province_id')->constrained('provinces');
            $table->foreignId('regency_id')->constrained('regencies');
            $table->string('kecamatan', 100);
            $table->string('kode_pos', 5)->nullable(); // tidak wajib, 5 digit bila diisi

            $table->string('nomor_telepon', 20)->nullable(); // tidak wajib
            $table->string('nomor_hp', 20); // wajib, 10-15 digit
            $table->string('email', 150);

            $table->enum('kewarganegaraan', ['WNI', 'WNA'])->default('WNI');
            $table->string('negara_asal', 100)->nullable(); // wajib diisi hanya jika WNA

            $table->date('tanggal_lahir'); // usia minimal 14 tahun, divalidasi di controller
            $table->string('tempat_lahir', 100);
            $table->enum('jenis_kelamin', ['Pria', 'Wanita']);
            $table->enum('status_perkawinan', ['Belum Menikah', 'Menikah', 'Lain-lain']);

            $table->foreignId('religion_id')->constrained('religions');

            // Program studi pilihan 1 & 2 (harus berbeda satu sama lain).
            $table->foreignId('program_studi_1_id')->constrained('program_studis');
            $table->foreignId('program_studi_2_id')->constrained('program_studis');

            $table->foreignId('gelombang_id')->constrained('gelombangs');

            // ===== Field pelengkap (di luar tabel F.2, lihat komentar atas) =====
            $table->string('foto')->nullable();
            $table->string('asal_sekolah', 150)->nullable();
            $table->string('jurusan_asal_sekolah', 100)->nullable();
            $table->year('tahun_lulus')->nullable();
            $table->decimal('nilai_rata_rata', 5, 2)->nullable();
            $table->enum('jalur_pendaftaran', ['Reguler', 'Beasiswa', 'Mandiri'])->default('Reguler');

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
