@extends('layouts.app')
@section('title', 'Data Pendaftaran')
@section('sidebar') @include('components.admin-sidebar') @endsection

@section('content')
<h4 class="mb-3">Data Pendaftaran</h4>

<div class="card shadow-sm"><div class="card-body">
    <form method="GET" class="row g-2 mb-3">
        <div class="col-md-3">
            <input type="text" name="q" value="{{ $keyword }}" class="form-control form-control-sm" placeholder="Cari nama / no. pendaftaran">
        </div>
        <div class="col-md-3">
            <select name="status" class="form-select form-select-sm">
                <option value="">Semua Status</option>
                @foreach(['Menunggu','Diverifikasi','Diterima','Ditolak'] as $s)
                    <option value="{{ $s }}" {{ $status==$s?'selected':'' }}>{{ $s }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2"><button class="btn btn-sm btn-outline-secondary w-100">Filter</button></div>
    </form>

    <div class="table-responsive">
    <table class="table table-hover">
        <thead><tr><th>No. Pendaftaran</th><th>Nama</th><th>Prodi</th><th>Jalur</th><th>Status</th><th width="160">Aksi</th></tr></thead>
        <tbody>
        @forelse($pendaftarans as $p)
            <tr>
                <td>{{ $p->nomor_pendaftaran }}</td>
                <td>{{ $p->nama_lengkap }}</td>
                <td>{{ $p->programStudi1->nama ?? '-' }}</td>
                <td>{{ $p->jalur_pendaftaran }}</td>
                <td><span class="badge badge-status-{{ $p->status_pendaftaran }}">{{ $p->status_pendaftaran }}</span></td>
                <td>
                    <a href="{{ route('admin.pendaftaran.show', $p) }}" class="btn btn-sm btn-info text-white"><i class="fa-solid fa-eye"></i></a>
                    <a href="{{ route('admin.pendaftaran.edit', $p) }}" class="btn btn-sm btn-warning"><i class="fa-solid fa-pen"></i></a>
                    <form action="{{ route('admin.pendaftaran.destroy', $p) }}" method="POST" class="d-inline form-delete">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger"><i class="fa-solid fa-trash"></i></button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="6" class="text-center text-muted py-3">Belum ada data pendaftaran.</td></tr>
        @endforelse
        </tbody>
    </table>
    </div>
    {{ $pendaftarans->links() }}
</div></div>
@endsection
