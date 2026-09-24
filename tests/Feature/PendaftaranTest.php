<?php

namespace Tests\Feature;

use App\Models\ProgramStudi;
use App\Models\Province;
use App\Models\Regency;
use App\Models\Religion;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PendaftaranTest extends TestCase
{
    use RefreshDatabase;

    private function siapkanMasterData(): array
    {
        $religion = Religion::create(['name' => 'Islam']);
        $province = Province::create(['code' => '99', 'name' => 'Provinsi Uji Coba']);
        $regency = Regency::create(['code' => '9901', 'province_id' => $province->id, 'name' => 'Kabupaten Uji Coba']);
        $prodi = ProgramStudi::create(['kode' => 'TI', 'nama' => 'Teknik Informatika', 'jenjang' => 'S1']);

        return compact('religion', 'province', 'regency', 'prodi');
    }

    /** @test */
    public function calon_mahasiswa_dapat_menyimpan_pendaftaran_dengan_data_valid(): void
    {
        $mahasiswa = User::factory()->create(['role' => 'mahasiswa']);
        $master = $this->siapkanMasterData();

        $response = $this->actingAs($mahasiswa)->post('/mahasiswa/pendaftaran', [
            'nama_lengkap' => 'Budi Santoso',
            'nik' => '1234567890123456',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '2005-05-10',
            'jenis_kelamin' => 'L',
            'religion_id' => $master['religion']->id,
            'nomor_hp' => '081234567890',
            'email' => 'budi@example.com',
            'alamat' => 'Jl. Contoh No. 1',
            'province_id' => $master['province']->id,
            'regency_id' => $master['regency']->id,
            'kecamatan' => 'Kecamatan A',
            'kelurahan' => 'Kelurahan B',
            'kode_pos' => '12345',
            'asal_sekolah' => 'SMA Negeri 1',
            'jurusan_asal_sekolah' => 'IPA',
            'tahun_lulus' => '2023',
            'nilai_rata_rata' => '85.5',
            'program_studi_id' => $master['prodi']->id,
            'jalur_pendaftaran' => 'Reguler',
        ]);

        $response->assertRedirect(route('mahasiswa.pendaftaran.show'));
        $this->assertDatabaseHas('pendaftarans', [
            'nama_lengkap' => 'Budi Santoso',
            'user_id' => $mahasiswa->id,
        ]);
    }

    /** @test */
    public function pendaftaran_gagal_jika_email_format_salah(): void
    {
        $mahasiswa = User::factory()->create(['role' => 'mahasiswa']);
        $master = $this->siapkanMasterData();

        $response = $this->actingAs($mahasiswa)->post('/mahasiswa/pendaftaran', [
            'nama_lengkap' => 'Budi Santoso',
            'nik' => '1234567890123456',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '2005-05-10',
            'jenis_kelamin' => 'L',
            'religion_id' => $master['religion']->id,
            'nomor_hp' => '081234567890',
            'email' => 'bukan-format-email',
            'alamat' => 'Jl. Contoh No. 1',
            'province_id' => $master['province']->id,
            'regency_id' => $master['regency']->id,
            'kecamatan' => 'Kecamatan A',
            'kelurahan' => 'Kelurahan B',
            'kode_pos' => '12345',
            'asal_sekolah' => 'SMA Negeri 1',
            'jurusan_asal_sekolah' => 'IPA',
            'tahun_lulus' => '2023',
            'nilai_rata_rata' => '85.5',
            'program_studi_id' => $master['prodi']->id,
            'jalur_pendaftaran' => 'Reguler',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertDatabaseMissing('pendaftarans', ['nama_lengkap' => 'Budi Santoso']);
    }
}
