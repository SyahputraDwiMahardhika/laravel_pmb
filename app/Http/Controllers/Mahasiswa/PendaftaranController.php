<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Pendaftaran;
use App\Models\ProgramStudi;
use App\Models\Province;
use App\Models\Regency;
use App\Models\Religion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

// Calon mahasiswa: mengisi, melihat, dan mencetak pendaftaran miliknya sendiri.
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

        return view('mahasiswa.pendaftaran.create', compact('provinces', 'religions', 'programStudis'));
    }

    public function store(Request $request)
    {
        if (Auth::user()->pendaftaran) {
            return redirect()->route('mahasiswa.pendaftaran.show');
        }

        // Validasi server-side lengkap sesuai requirement soal.
        $validated = $request->validate([
            'nama_lengkap' => 'required|string|max:150',
            'nik' => 'required|digits:16',
            'tempat_lahir' => 'required|string|max:100',
            'tanggal_lahir' => 'required|date|before:today',
            'jenis_kelamin' => 'required|in:L,P',
            'religion_id' => 'required|exists:religions,id',
            'nomor_hp' => 'required|numeric|digits_between:9,15',
            'email' => 'required|email',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'alamat' => 'required|string',
            'province_id' => 'required|exists:provinces,id',
            'regency_id' => 'required|exists:regencies,id',
            'kecamatan' => 'required|string|max:100',
            'kelurahan' => 'required|string|max:100',
            'kode_pos' => 'required|digits_between:5,10',
            'asal_sekolah' => 'required|string|max:150',
            'jurusan_asal_sekolah' => 'required|string|max:100',
            'tahun_lulus' => 'required|digits:4|integer|min:2000|max:' . date('Y'),
            'nilai_rata_rata' => 'required|numeric|min:0|max:100',
            'program_studi_id' => 'required|exists:program_studis,id',
            'jalur_pendaftaran' => 'required|in:Reguler,Beasiswa,Mandiri',
        ], [
            'nik.digits' => 'NIK harus terdiri dari 16 digit angka.',
            'tanggal_lahir.before' => 'Tanggal lahir harus valid dan sebelum hari ini.',
            'nomor_hp.numeric' => 'Nomor HP hanya boleh berisi angka.',
            'email.email' => 'Email harus menggunakan format email yang valid.',
            'foto.image' => 'File yang diunggah harus berupa gambar.',
            'foto.max' => 'Ukuran foto maksimal 2MB.',
            'kode_pos.digits_between' => 'Kode pos harus berupa angka 5-10 digit.',
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

        $pendaftaran->load(['religion', 'province', 'regency', 'programStudi']);

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

        $pendaftaran->load(['religion', 'province', 'regency', 'programStudi']);

        $pdf = Pdf::loadView('pdf.bukti', compact('pendaftaran'))->setPaper('a4');

        return $pdf->stream('bukti-pendaftaran-' . $pendaftaran->nomor_pendaftaran . '.pdf');
    }
}
