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
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Program Studi Pilihan 1</label>
                <select name="program_studi_1_id" id="prodi1" class="form-select" required>
                    @foreach($programStudis as $prodi)
                        <option value="{{ $prodi->id }}" {{ $pendaftaran->program_studi_1_id==$prodi->id?'selected':'' }}>{{ $prodi->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Program Studi Pilihan 2</label>
                <select name="program_studi_2_id" id="prodi2" class="form-select" required>
                    @foreach($programStudis as $prodi)
                        <option value="{{ $prodi->id }}" {{ $pendaftaran->program_studi_2_id==$prodi->id?'selected':'' }}>{{ $prodi->nama }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label">Gelombang</label>
            <select name="gelombang_id" class="form-select" required>
                @foreach($gelombangs as $gelombang)
                    <option value="{{ $gelombang->id }}" {{ $pendaftaran->gelombang_id==$gelombang->id?'selected':'' }}>{{ $gelombang->nama }} {{ $gelombang->aktif ? '' : '(nonaktif)' }}</option>
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

@push('scripts')
<script>
    // Cegah admin memilih program studi 1 & 2 yang sama
    function cegahProdiSama() {
        const p1 = document.getElementById('prodi1').value;
        Array.from(document.getElementById('prodi2').options).forEach(opt => {
            opt.disabled = (opt.value === p1);
        });
    }
    document.getElementById('prodi1').addEventListener('change', cegahProdiSama);
    cegahProdiSama();
</script>
@endpush
@endsection
