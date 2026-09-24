@extends('layouts.app')
@section('title', 'Detail Pendaftaran Saya')

@section('content')
<h4 class="mb-3">Detail Pendaftaran Saya</h4>
<div class="card shadow-sm"><div class="card-body">
    <div class="row">
        <div class="col-md-3 text-center mb-3">
            @if($pendaftaran->foto)
                <img src="{{ asset('storage/'.$pendaftaran->foto) }}" class="img-fluid rounded" alt="Foto">
            @else
                <div class="bg-light border rounded p-5 text-muted">Tidak ada foto</div>
            @endif
        </div>
        <div class="col-md-9">
            <table class="table table-borderless table-sm">
                <tr><th width="220">Nomor Pendaftaran</th><td>: {{ $pendaftaran->nomor_pendaftaran }}</td></tr>
                <tr><th>Nama Lengkap</th><td>: {{ $pendaftaran->nama_lengkap }}</td></tr>
                <tr><th>NIK</th><td>: {{ $pendaftaran->nik }}</td></tr>
                <tr><th>Tempat, Tanggal Lahir</th><td>: {{ $pendaftaran->tempat_lahir }}, {{ $pendaftaran->tanggal_lahir->format('d-m-Y') }}</td></tr>
                <tr><th>Jenis Kelamin</th><td>: {{ $pendaftaran->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</td></tr>
                <tr><th>Agama</th><td>: {{ $pendaftaran->religion->name }}</td></tr>
                <tr><th>No. HP</th><td>: {{ $pendaftaran->nomor_hp }}</td></tr>
                <tr><th>Email</th><td>: {{ $pendaftaran->email }}</td></tr>
                <tr><th>Alamat</th><td>: {{ $pendaftaran->alamat }}, {{ $pendaftaran->kelurahan }}, {{ $pendaftaran->kecamatan }}, {{ $pendaftaran->regency->name }}, {{ $pendaftaran->province->name }} {{ $pendaftaran->kode_pos }}</td></tr>
                <tr><th>Asal Sekolah</th><td>: {{ $pendaftaran->asal_sekolah }} ({{ $pendaftaran->jurusan_asal_sekolah }})</td></tr>
                <tr><th>Program Studi</th><td>: {{ $pendaftaran->programStudi->nama }}</td></tr>
                <tr><th>Jalur Pendaftaran</th><td>: {{ $pendaftaran->jalur_pendaftaran }}</td></tr>
                <tr><th>Status</th><td>: <span class="badge badge-status-{{ $pendaftaran->status_pendaftaran }}">{{ $pendaftaran->status_pendaftaran }}</span></td></tr>
            </table>
        </div>
    </div>
    <a href="{{ route('mahasiswa.pendaftaran.cetak') }}" target="_blank" class="btn btn-success"><i class="fa-solid fa-print"></i> Cetak Bukti PDF</a>
    <a href="{{ route('mahasiswa.dashboard') }}" class="btn btn-secondary">Kembali</a>
</div></div>
@endsection
