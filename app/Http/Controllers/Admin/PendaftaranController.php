<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gelombang;
use App\Models\Pendaftaran;
use App\Models\ProgramStudi;
use App\Models\Province;
use App\Models\Regency;
use App\Models\Religion;
use Illuminate\Http\Request;

// Admin: mengelola seluruh data pendaftaran calon mahasiswa.
class PendaftaranController extends Controller
{
    // Dashboard admin dengan ringkasan statistik.
    public function dashboard()
    {
        $stats = [
            'jumlah_user' => \App\Models\User::count(),
            'jumlah_pendaftar' => Pendaftaran::count(),
            'menunggu' => Pendaftaran::where('status_pendaftaran', 'Menunggu')->count(),
            'diverifikasi' => Pendaftaran::where('status_pendaftaran', 'Diverifikasi')->count(),
            'diterima' => Pendaftaran::where('status_pendaftaran', 'Diterima')->count(),
            'ditolak' => Pendaftaran::where('status_pendaftaran', 'Ditolak')->count(),
        ];

        $pendaftarTerbaru = Pendaftaran::with(['programStudi1'])
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'pendaftarTerbaru'));
    }

    public function index(Request $request)
    {
        $status = $request->get('status');
        $keyword = $request->get('q');

        $pendaftarans = Pendaftaran::with(['programStudi1', 'province', 'regency'])
            ->when($status, fn ($q) => $q->where('status_pendaftaran', $status))
            ->when($keyword, function ($q) use ($keyword) {
                $q->where('nama_lengkap', 'like', "%{$keyword}%")
                  ->orWhere('nomor_pendaftaran', 'like', "%{$keyword}%");
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.pendaftaran.index', compact('pendaftarans', 'status', 'keyword'));
    }

    public function show(Pendaftaran $pendaftaran)
    {
        $pendaftaran->load(['user', 'religion', 'province', 'regency', 'programStudi1', 'programStudi2', 'gelombang']);

        return view('admin.pendaftaran.show', compact('pendaftaran'));
    }

    public function edit(Pendaftaran $pendaftaran)
    {
        $provinces = Province::orderBy('name')->get();
        $regencies = Regency::where('province_id', $pendaftaran->province_id)->orderBy('name')->get();
        $religions = Religion::orderBy('name')->get();
        $programStudis = ProgramStudi::orderBy('nama')->get();
        $gelombangs = Gelombang::orderBy('tanggal_mulai')->get();

        return view('admin.pendaftaran.edit', compact('pendaftaran', 'provinces', 'regencies', 'religions', 'programStudis', 'gelombangs'));
    }

    public function update(Request $request, Pendaftaran $pendaftaran)
    {
        $request->validate([
            'nama_lengkap' => 'required|string|max:150',
            'status_pendaftaran' => 'required|in:Menunggu,Diverifikasi,Diterima,Ditolak',
            'jalur_pendaftaran' => 'required|in:Reguler,Beasiswa,Mandiri',
            'program_studi_1_id' => 'required|exists:program_studis,id',
            'program_studi_2_id' => 'required|exists:program_studis,id|different:program_studi_1_id',
            'gelombang_id' => 'required|exists:gelombangs,id',
        ], [
            'nama_lengkap.required' => 'Nama lengkap wajib diisi.',
            'status_pendaftaran.required' => 'Status pendaftaran wajib dipilih.',
            'program_studi_1_id.exists' => 'Program studi pilihan 1 tidak valid.',
            'program_studi_2_id.different' => 'Program studi pilihan 2 harus berbeda dari pilihan 1.',
        ]);

        $pendaftaran->update($request->only([
            'nama_lengkap', 'status_pendaftaran', 'jalur_pendaftaran',
            'program_studi_1_id', 'program_studi_2_id', 'gelombang_id',
        ]));

        return redirect()->route('admin.pendaftaran.index')
            ->with('success', 'Data pendaftaran berhasil diperbarui.');
    }

    public function destroy(Pendaftaran $pendaftaran)
    {
        $pendaftaran->delete();

        return redirect()->route('admin.pendaftaran.index')
            ->with('success', 'Data pendaftaran berhasil dihapus.');
    }

    // Ambil daftar kabupaten/kota berdasarkan provinsi (dipakai dropdown dependent via AJAX).
    public function regenciesByProvince(Province $province)
    {
        return response()->json(
            $province->regencies()->orderBy('name')->get(['id', 'name'])
        );
    }
}
