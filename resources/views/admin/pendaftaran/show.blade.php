@extends('layouts.app')
@section('title', 'Detail Pendaftaran')
@section('sidebar') @include('components.admin-sidebar') @endsection

@section('content')
<h4 class="mb-3">Detail Pendaftaran — {{ $pendaftaran->nomor_pendaftaran }}</h4>
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
                <tr><th width="220">Nama Lengkap</th><td>: {{ $pendaftaran->nama_lengkap }}</td></tr>
                <tr><th>NIK</th><td>: {{ $pendaftaran->nik }}</td></tr>
                <tr><th>Alamat KTP</th><td>: {{ $pendaftaran->alamat_ktp }}</td></tr>
                <tr><th>Alamat Domisili</th><td>: {{ $pendaftaran->alamat_domisili }}, {{ $pendaftaran->kecamatan }}, {{ $pendaftaran->regency->name }}, {{ $pendaftaran->province->name }} {{ $pendaftaran->kode_pos }}</td></tr>
                <tr><th>Telepon / HP</th><td>: {{ $pendaftaran->nomor_telepon ?: '-' }} / {{ $pendaftaran->nomor_hp }}</td></tr>
                <tr><th>Email</th><td>: {{ $pendaftaran->email }}</td></tr>
                <tr><th>Kewarganegaraan</th><td>: {{ $pendaftaran->kewarganegaraan }} {{ $pendaftaran->negara_asal ? '('.$pendaftaran->negara_asal.')' : '' }}</td></tr>
                <tr><th>Tempat, Tanggal Lahir</th><td>: {{ $pendaftaran->tempat_lahir }}, {{ $pendaftaran->tanggal_lahir->format('d-m-Y') }}</td></tr>
                <tr><th>Jenis Kelamin</th><td>: {{ $pendaftaran->jenis_kelamin }}</td></tr>
                <tr><th>Status Perkawinan</th><td>: {{ $pendaftaran->status_perkawinan }}</td></tr>
                <tr><th>Agama</th><td>: {{ $pendaftaran->religion->name }}</td></tr>
                <tr><th>Asal Sekolah</th><td>: {{ $pendaftaran->asal_sekolah ?: '-' }} ({{ $pendaftaran->jurusan_asal_sekolah ?: '-' }})</td></tr>
                <tr><th>Tahun Lulus / Nilai</th><td>: {{ $pendaftaran->tahun_lulus ?: '-' }} / {{ $pendaftaran->nilai_rata_rata ?: '-' }}</td></tr>
                <tr><th>Program Studi Pilihan 1</th><td>: {{ $pendaftaran->programStudi1->nama }}</td></tr>
                <tr><th>Program Studi Pilihan 2</th><td>: {{ $pendaftaran->programStudi2->nama }}</td></tr>
                <tr><th>Gelombang</th><td>: {{ $pendaftaran->gelombang->nama }}</td></tr>
                <tr><th>Jalur Pendaftaran</th><td>: {{ $pendaftaran->jalur_pendaftaran }}</td></tr>
                <tr><th>Status</th><td>: <span class="badge badge-status-{{ $pendaftaran->status_pendaftaran }}">{{ $pendaftaran->status_pendaftaran }}</span></td></tr>
            </table>
        </div>
    </div>
    <a href="{{ route('admin.pendaftaran.edit', $pendaftaran) }}" class="btn btn-warning">Edit</a>
    <a href="{{ route('admin.pendaftaran.index') }}" class="btn btn-secondary">Kembali</a>
</div></div>
@endsection
