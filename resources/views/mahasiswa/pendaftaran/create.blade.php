@extends('layouts.app')
@section('title', 'Formulir Pendaftaran PMB')

@section('content')
<h4 class="mb-3">Formulir Pendaftaran Mahasiswa Baru</h4>

<div class="card shadow-sm"><div class="card-body">
    <form method="POST" action="{{ route('mahasiswa.pendaftaran.store') }}" enctype="multipart/form-data" id="form-pendaftaran">
        @csrf

        <h6 class="text-primary border-bottom pb-2 mb-3">Data Pribadi</h6>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Nama Lengkap</label>
                <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap') }}" class="form-control" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">NIK (16 digit)</label>
                <input type="text" name="nik" value="{{ old('nik') }}" class="form-control" maxlength="16" pattern="\d{16}" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Tempat Lahir</label>
                <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir') }}" class="form-control" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Tanggal Lahir</label>
                <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}" class="form-control" required>
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Jenis Kelamin</label>
                <select name="jenis_kelamin" class="form-select" required>
                    <option value="">-- Pilih --</option>
                    <option value="L" {{ old('jenis_kelamin')=='L'?'selected':'' }}>Laki-laki</option>
                    <option value="P" {{ old('jenis_kelamin')=='P'?'selected':'' }}>Perempuan</option>
                </select>
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Agama</label>
                <select name="religion_id" class="form-select" required>
                    <option value="">-- Pilih --</option>
                    @foreach($religions as $religion)
                        <option value="{{ $religion->id }}" {{ old('religion_id')==$religion->id?'selected':'' }}>{{ $religion->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Nomor HP</label>
                <input type="text" name="nomor_hp" id="nomor_hp" value="{{ old('nomor_hp') }}" class="form-control" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}" class="form-control" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Foto (JPG/PNG, maks 2MB)</label>
                <input type="file" name="foto" id="foto" accept="image/*" class="form-control">
                <img id="preview-foto" src="#" alt="Preview" class="mt-2 rounded d-none" style="max-width:150px;">
            </div>
        </div>

        <h6 class="text-primary border-bottom pb-2 mb-3 mt-4">Data Alamat</h6>
        <div class="row">
            <div class="col-md-12 mb-3">
                <label class="form-label">Alamat Lengkap</label>
                <textarea name="alamat" class="form-control" rows="2" required>{{ old('alamat') }}</textarea>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Provinsi</label>
                <select name="province_id" id="province_id" class="form-select" required>
                    <option value="">-- Pilih Provinsi --</option>
                    @foreach($provinces as $province)
                        <option value="{{ $province->id }}" {{ old('province_id')==$province->id?'selected':'' }}>{{ $province->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Kabupaten/Kota</label>
                <select name="regency_id" id="regency_id" class="form-select" required>
                    <option value="">-- Pilih Provinsi Dahulu --</option>
                </select>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Kecamatan</label>
                <input type="text" name="kecamatan" value="{{ old('kecamatan') }}" class="form-control" required>
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Kelurahan</label>
                <input type="text" name="kelurahan" value="{{ old('kelurahan') }}" class="form-control" required>
            </div>
            <div class="col-md-2 mb-3">
                <label class="form-label">Kode Pos</label>
                <input type="text" name="kode_pos" value="{{ old('kode_pos') }}" class="form-control" required>
            </div>
        </div>

        <h6 class="text-primary border-bottom pb-2 mb-3 mt-4">Data Pendidikan</h6>
        <div class="row">
            <div class="col-md-5 mb-3">
                <label class="form-label">Asal Sekolah</label>
                <input type="text" name="asal_sekolah" value="{{ old('asal_sekolah') }}" class="form-control" required>
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Jurusan Asal Sekolah</label>
                <input type="text" name="jurusan_asal_sekolah" value="{{ old('jurusan_asal_sekolah') }}" class="form-control" required>
            </div>
            <div class="col-md-1 mb-3">
                <label class="form-label">Tahun Lulus</label>
                <input type="number" name="tahun_lulus" value="{{ old('tahun_lulus') }}" class="form-control" min="2000" max="{{ date('Y') }}" required>
            </div>
            <div class="col-md-2 mb-3">
                <label class="form-label">Nilai Rata-rata</label>
                <input type="number" step="0.01" name="nilai_rata_rata" value="{{ old('nilai_rata_rata') }}" class="form-control" min="0" max="100" required>
            </div>
        </div>

        <h6 class="text-primary border-bottom pb-2 mb-3 mt-4">Data Pilihan</h6>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Program Studi</label>
                <select name="program_studi_id" class="form-select" required>
                    <option value="">-- Pilih --</option>
                    @foreach($programStudis as $prodi)
                        <option value="{{ $prodi->id }}" {{ old('program_studi_id')==$prodi->id?'selected':'' }}>{{ $prodi->nama }} ({{ $prodi->jenjang }})</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Jalur Pendaftaran</label>
                <select name="jalur_pendaftaran" class="form-select" required>
                    <option value="Reguler">Reguler</option>
                    <option value="Beasiswa">Beasiswa</option>
                    <option value="Mandiri">Mandiri</option>
                </select>
            </div>
        </div>

        <button type="submit" class="btn btn-primary px-4">Simpan Pendaftaran</button>
        <a href="{{ route('mahasiswa.dashboard') }}" class="btn btn-secondary">Batal</a>
    </form>
</div></div>

@push('scripts')
<script>
    // Dependent dropdown Provinsi -> Kabupaten/Kota
    document.getElementById('province_id').addEventListener('change', function () {
        const provinceId = this.value;
        const regencySelect = document.getElementById('regency_id');
        regencySelect.innerHTML = '<option value="">Memuat...</option>';

        if (!provinceId) {
            regencySelect.innerHTML = '<option value="">-- Pilih Provinsi Dahulu --</option>';
            return;
        }

        fetch(`{{ url('mahasiswa/provinces') }}/${provinceId}/regencies`)
            .then(res => res.json())
            .then(data => {
                regencySelect.innerHTML = '<option value="">-- Pilih Kabupaten/Kota --</option>';
                data.forEach(item => {
                    const opt = document.createElement('option');
                    opt.value = item.id;
                    opt.textContent = item.name;
                    regencySelect.appendChild(opt);
                });
            })
            .catch(() => {
                regencySelect.innerHTML = '<option value="">Gagal memuat data</option>';
            });
    });

    // Preview foto sebelum upload
    document.getElementById('foto').addEventListener('change', function (e) {
        const file = e.target.files[0];
        const preview = document.getElementById('preview-foto');
        if (file) {
            preview.src = URL.createObjectURL(file);
            preview.classList.remove('d-none');
        }
    });

    // Validasi nomor HP hanya angka + alert
    document.getElementById('nomor_hp').addEventListener('input', function () {
        const original = this.value;
        const cleaned = original.replace(/[^0-9]/g, '');
        if (original !== cleaned) {
            Swal.fire({ icon: 'warning', title: 'Nomor HP tidak valid', text: 'Nomor HP hanya boleh berisi angka.' });
        }
        this.value = cleaned;
    });

    // Alert konfirmasi sebelum submit form pendaftaran
    document.getElementById('form-pendaftaran').addEventListener('submit', function (e) {
        e.preventDefault();
        const form = this;
        Swal.fire({
            title: 'Kirim Pendaftaran?',
            text: 'Pastikan seluruh data yang Anda isi sudah benar sebelum disimpan.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Simpan',
            cancelButtonText: 'Periksa Lagi',
        }).then((result) => {
            if (result.isConfirmed) form.submit();
        });
    });
</script>
@endpush
@endsection
