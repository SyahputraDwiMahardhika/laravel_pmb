@extends('layouts.app')
@section('title', 'Dashboard Calon Mahasiswa')

@section('content')
<h4 class="mb-4">Selamat datang, {{ auth()->user()->name }}</h4>

@if(!$pendaftaran)
    <div class="card shadow-sm">
        <div class="card-body text-center py-5">
            <i class="fa-solid fa-file-circle-plus fa-3x text-primary mb-3"></i>
            <h5>Anda belum melakukan pendaftaran</h5>
            <p class="text-muted">Silakan isi formulir pendaftaran PMB untuk melanjutkan proses seleksi.</p>
            <a href="{{ route('mahasiswa.pendaftaran.create') }}" class="btn btn-primary">Isi Formulir Pendaftaran</a>
        </div>
    </div>
@else
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="row">
                <div class="col-md-8">
                    <p><strong>Nomor Pendaftaran:</strong> {{ $pendaftaran->nomor_pendaftaran }}</p>
                    <p><strong>Nama:</strong> {{ $pendaftaran->nama_lengkap }}</p>
                    <p><strong>Tanggal Daftar:</strong> {{ $pendaftaran->created_at->format('d F Y') }}</p>
                    <p><strong>Status:</strong> <span class="badge badge-status-{{ $pendaftaran->status_pendaftaran }}">{{ $pendaftaran->status_pendaftaran }}</span></p>
                </div>
                <div class="col-md-4 text-md-end">
                    <a href="{{ route('mahasiswa.pendaftaran.show') }}" class="btn btn-info text-white mb-2 w-100"><i class="fa-solid fa-eye"></i> Lihat Detail</a>
                    <a href="{{ route('mahasiswa.pendaftaran.cetak') }}" target="_blank" class="btn btn-success w-100"><i class="fa-solid fa-print"></i> Cetak Bukti</a>
                </div>
            </div>
        </div>
    </div>
@endif
@endsection
