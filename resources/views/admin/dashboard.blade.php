@extends('layouts.app')
@section('title', 'Dashboard Admin')
@section('sidebar') @include('components.admin-sidebar') @endsection

@section('content')
<h4 class="mb-4">Dashboard Admin</h4>

<div class="row g-3 mb-4">
    <div class="col-md-2 col-6"><div class="card card-stat"><div class="card-body">
        <div class="text-muted small">Total User</div><h3>{{ $stats['jumlah_user'] }}</h3>
    </div></div></div>
    <div class="col-md-2 col-6"><div class="card card-stat"><div class="card-body">
        <div class="text-muted small">Total Pendaftar</div><h3>{{ $stats['jumlah_pendaftar'] }}</h3>
    </div></div></div>
    <div class="col-md-2 col-6"><div class="card card-stat"><div class="card-body">
        <div class="text-muted small">Menunggu</div><h3 class="text-warning">{{ $stats['menunggu'] }}</h3>
    </div></div></div>
    <div class="col-md-2 col-6"><div class="card card-stat"><div class="card-body">
        <div class="text-muted small">Diverifikasi</div><h3 class="text-primary">{{ $stats['diverifikasi'] }}</h3>
    </div></div></div>
    <div class="col-md-2 col-6"><div class="card card-stat"><div class="card-body">
        <div class="text-muted small">Diterima</div><h3 class="text-success">{{ $stats['diterima'] }}</h3>
    </div></div></div>
    <div class="col-md-2 col-6"><div class="card card-stat"><div class="card-body">
        <div class="text-muted small">Ditolak</div><h3 class="text-danger">{{ $stats['ditolak'] }}</h3>
    </div></div></div>
</div>

<div class="card shadow-sm">
    <div class="card-header bg-white">Pendaftar Terbaru</div>
    <div class="card-body p-0">
        <table class="table mb-0">
            <thead><tr><th>No. Pendaftaran</th><th>Nama</th><th>Program Studi</th><th>Status</th><th>Tanggal</th></tr></thead>
            <tbody>
            @forelse($pendaftarTerbaru as $p)
                <tr>
                    <td>{{ $p->nomor_pendaftaran }}</td>
                    <td>{{ $p->nama_lengkap }}</td>
                    <td>{{ $p->programStudi->nama ?? '-' }}</td>
                    <td><span class="badge badge-status-{{ $p->status_pendaftaran }}">{{ $p->status_pendaftaran }}</span></td>
                    <td>{{ $p->created_at->format('d/m/Y') }}</td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-center text-muted py-3">Belum ada data pendaftaran.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
