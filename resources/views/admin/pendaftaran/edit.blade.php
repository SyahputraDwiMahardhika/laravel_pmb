@extends('layouts.app')
@section('title', 'Edit Pendaftaran')
@section('sidebar') @include('components.admin-sidebar') @endsection

@section('content')
<h4 class="mb-3">Edit Pendaftaran — {{ $pendaftaran->nomor_pendaftaran }}</h4>
<div class="card shadow-sm"><div class="card-body">
    <form method="POST" action="{{ route('admin.pendaftaran.update', $pendaftaran) }}">
        @csrf @method('PUT')
        <div class="mb-3">
            <label class="form-label">Nama Lengkap</label>
            <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap', $pendaftaran->nama_lengkap) }}" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Program Studi</label>
            <select name="program_studi_id" class="form-select" required>
                @foreach($programStudis as $prodi)
                    <option value="{{ $prodi->id }}" {{ $pendaftaran->program_studi_id==$prodi->id?'selected':'' }}>{{ $prodi->nama }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Jalur Pendaftaran</label>
            <select name="jalur_pendaftaran" class="form-select" required>
                @foreach(['Reguler','Beasiswa','Mandiri'] as $j)
                    <option value="{{ $j }}" {{ $pendaftaran->jalur_pendaftaran==$j?'selected':'' }}>{{ $j }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Status Pendaftaran</label>
            <select name="status_pendaftaran" class="form-select" required>
                @foreach(['Menunggu','Diverifikasi','Diterima','Ditolak'] as $s)
                    <option value="{{ $s }}" {{ $pendaftaran->status_pendaftaran==$s?'selected':'' }}>{{ $s }}</option>
                @endforeach
            </select>
        </div>
        <button class="btn btn-primary">Perbarui</button>
        <a href="{{ route('admin.pendaftaran.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div></div>
@endsection
