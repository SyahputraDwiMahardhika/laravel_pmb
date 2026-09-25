<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Gelombang;
use App\Models\Pendaftaran;
use App\Models\ProgramStudi;
use App\Models\Province;
use App\Models\Regency;
use App\Models\Religion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

// Calon mahasiswa: mengisi, melihat, dan mencetak pendaftaran miliknya sendiri.
// Field & validasi bagian "Form Pendaftaran Mahasiswa Baru" mengikuti dokumen soal F.2.
class PendaftaranController extends Controller
{
    public function dashboard()
    {
        $pendaftaran = Auth::user()->pendaftaran;

        return view('mahasiswa.dashboard', compact('pendaftaran'));
    }

    // Form pengisian pendaftaran (hanya jika belum pernah mendaftar).
    public function create()
    {
        if (Auth::user()->pendaftaran) {
            return redirect()->route('mahasiswa.pendaftaran.show')
                ->with('error', 'Anda sudah melakukan pendaftaran sebelumnya.');
        }

        $provinces = Province::orderBy('name')->get();
        $religions = Religion::orderBy('name')->get();
        $programStudis = ProgramStudi::orderBy('nama')->get();
        $gelombangs = Gelombang::where('aktif', true)->orderBy('tanggal_mulai')->get();

        return view('mahasiswa.pendaftaran.create', compact('provinces', 'religions', 'programStudis', 'gelombangs'));
    }

    public function store(Request $request)
    {
        if (Auth::user()->pendaftaran) {
            return redirect()->route('mahasiswa.pendaftaran.show');
        }

        // Validasi server-side lengkap sesuai dokumen soal F.2.
        $validated = $request->validate([
            'nama_lengkap' => 'required|string|max:150',
            'nik' => 'required|digits:16|unique:pendaftarans,nik',
            'alamat_ktp' => 'required|string|max:255',
            'alamat_domisili' => 'required|string|max:255',
            'province_id' => 'required|exists:provinces,id',
            'regency_id' => 'required|exists:regencies,id',
            'kecamatan' => 'required|string|min:2|max:100',
            'kode_pos' => 'nullable|digits:5',
            'nomor_telepon' => 'nullable|numeric',
            'nomor_hp' => 'required|numeric|digits_between:10,15',
            'email' => 'required|email',
            'kewarganegaraan' => 'required|in:WNI,WNA',
            'negara_asal' => 'required_if:kewarganegaraan,WNA|nullable|string|max:100',
            'tanggal_lahir' => 'required|date|before_or_equal:' . now()->subYears(14)->format('Y-m-d'),
            'tempat_lahir' => 'required|string|max:100',
            'jenis_kelamin' => 'required|in:Pria,Wanita',
            'status_perkawinan' => 'required|in:Belum Menikah,Menikah,Lain-lain',
            'religion_id' => 'required|exists:religions,id',
            'program_studi_1_id' => 'required|exists:program_studis,id',
            'program_studi_2_id' => 'required|exists:program_studis,id|different:program_studi_1_id',
            'gelombang_id' => 'required|exists:gelombangs,id',

            // Field pelengkap di luar tabel F.2 (data akademik & foto).
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'asal_sekolah' => 'nullable|string|max:150',
            'jurusan_asal_sekolah' => 'nullable|string|max:100',
            'tahun_lulus' => 'nullable|digits:4|integer|min:2000|max:' . date('Y'),
            'nilai_rata_rata' => 'nullable|numeric|min:0|max:100',
            'jalur_pendaftaran' => 'required|in:Reguler,Beasiswa,Mandiri',
        ], [
            'nik.digits' => 'NIK harus terdiri dari 16 digit angka.',
            'nik.unique' => 'NIK ini sudah pernah digunakan untuk mendaftar.',
            'alamat_ktp.max' => 'Alamat KTP maksimal 255 karakter.',
            'kecamatan.min' => 'Kecamatan minimal 2 karakter.',
            'kode_pos.digits' => 'Kode pos harus 5 digit angka.',
            'nomor_telepon.numeric' => 'Nomor telepon hanya boleh berisi angka.',
            'nomor_hp.numeric' => 'Nomor HP hanya boleh berisi angka.',
            'nomor_hp.digits_between' => 'Nomor HP harus 10-15 digit.',
            'email.email' => 'Email harus menggunakan format email yang valid.',
            'negara_asal.required_if' => 'Negara asal wajib diisi jika kewarganegaraan WNA.',
            'tanggal_lahir.before_or_equal' => 'Usia calon mahasiswa minimal 14 tahun.',
            'program_studi_2_id.different' => 'Program studi pilihan 2 harus berbeda dari pilihan 1.',
            'foto.image' => 'File yang diunggah harus berupa gambar.',
            'foto.max' => 'Ukuran foto maksimal 2MB.',
            '*.required' => 'Field ini wajib diisi.',
        ]);

        $validated['user_id'] = Auth::id();
        $validated['nomor_pendaftaran'] = Pendaftaran::generateNomorPendaftaran();
        $validated['status_pendaftaran'] = 'Menunggu';

        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('foto_pendaftar', 'public');
        }

        Pendaftaran::create($validated);

        return redirect()->route('mahasiswa.pendaftaran.show')
            ->with('success', 'Pendaftaran berhasil disimpan! Nomor pendaftaran Anda: ' . $validated['nomor_pendaftaran']);
    }

    // Lihat detail pendaftaran milik sendiri.
    public function show()
    {
        $pendaftaran = Auth::user()->pendaftaran;

        if (! $pendaftaran) {
            return redirect()->route('mahasiswa.pendaftaran.create')
                ->with('error', 'Anda belum melakukan pendaftaran.');
        }

        $pendaftaran->load(['religion', 'province', 'regency', 'programStudi1', 'programStudi2', 'gelombang']);

        return view('mahasiswa.pendaftaran.show', compact('pendaftaran'));
    }

    // Ambil daftar kabupaten/kota berdasarkan provinsi (AJAX, dependent dropdown).
    public function regenciesByProvince(Province $province)
    {
        return response()->json(
            $province->regencies()->orderBy('name')->get(['id', 'name'])
        );
    }

    // Cetak bukti pendaftaran ke PDF menggunakan DomPDF.
    public function cetak()
    {
        $pendaftaran = Auth::user()->pendaftaran;

        if (! $pendaftaran) {
            return redirect()->route('mahasiswa.pendaftaran.create')
                ->with('error', 'Anda belum melakukan pendaftaran.');
        }

        $pendaftaran->load(['religion', 'province', 'regency', 'programStudi1', 'programStudi2', 'gelombang']);

        $pdf = Pdf::loadView('pdf.bukti', compact('pendaftaran'))->setPaper('a4');

        return $pdf->stream('bukti-pendaftaran-' . $pendaftaran->nomor_pendaftaran . '.pdf');
    }
}
