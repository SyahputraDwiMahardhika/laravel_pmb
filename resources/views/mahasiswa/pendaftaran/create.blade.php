@extends('layouts.app')
@section('title', 'Formulir Pendaftaran PMB')

@section('content')
<h4 class="mb-3">Formulir Pendaftaran Mahasiswa Baru</h4>

<div class="card shadow-sm"><div class="card-body">
    <form method="POST" action="{{ route('mahasiswa.pendaftaran.store') }}" enctype="multipart/form-data" id="form-pendaftaran">
        @csrf

        <h6 class="text-primary border-bottom pb-2 mb-3">Identitas & Alamat</h6>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Nama Lengkap</label>
                <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap', auth()->user()->name) }}" class="form-control" required>
                <div class="form-text">Sesuai identitas (KTP/KK).</div>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">NIK</label>
                <input type="text" name="nik" id="nik" value="{{ old('nik') }}" class="form-control" maxlength="16" required>
                <div class="form-text">16 digit angka, unik.</div>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Alamat KTP</label>
                <textarea name="alamat_ktp" id="alamat_ktp" class="form-control" rows="2" maxlength="255" required>{{ old('alamat_ktp') }}</textarea>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label d-flex justify-content-between">
                    Alamat Domisili
                    <button type="button" class="btn btn-link btn-sm p-0" id="btn-salin-alamat">Sama dengan alamat KTP</button>
                </label>
                <textarea name="alamat_domisili" id="alamat_domisili" class="form-control" rows="2" maxlength="255" required>{{ old('alamat_domisili') }}</textarea>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Provinsi (Domisili)</label>
                <select name="province_id" id="province_id" class="form-select" required>
                    <option value="">-- Pilih Provinsi --</option>
                    @foreach($provinces as $province)
                        <option value="{{ $province->id }}" {{ old('province_id')==$province->id?'selected':'' }}>{{ $province->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Kabupaten/Kota (Domisili)</label>
                <select name="regency_id" id="regency_id" class="form-select" required>
                    <option value="">-- Pilih Provinsi Dahulu --</option>
                </select>
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Kecamatan</label>
                <input type="text" name="kecamatan" value="{{ old('kecamatan') }}" class="form-control" minlength="2" maxlength="100" required>
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Kode Pos <span class="text-muted small">(opsional)</span></label>
                <input type="text" name="kode_pos" id="kode_pos" value="{{ old('kode_pos') }}" class="form-control" maxlength="5">
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Nomor Telepon <span class="text-muted small">(opsional)</span></label>
                <input type="text" name="nomor_telepon" id="nomor_telepon" value="{{ old('nomor_telepon') }}" class="form-control">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Nomor HP</label>
                <input type="text" name="nomor_hp" id="nomor_hp" value="{{ old('nomor_hp', auth()->user()->nomor_hp) }}" class="form-control" required>
                <div class="form-text">10-15 digit angka.</div>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" id="email" value="{{ old('email', auth()->user()->email) }}" class="form-control" required>
            </div>
        </div>

        <h6 class="text-primary border-bottom pb-2 mb-3 mt-4">Data Diri</h6>
        <div class="row">
            <div class="col-md-3 mb-3">
                <label class="form-label d-block">Kewarganegaraan</label>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="kewarganegaraan" id="wni" value="WNI" {{ old('kewarganegaraan','WNI')=='WNI'?'checked':'' }}>
                    <label class="form-check-label" for="wni">WNI</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="kewarganegaraan" id="wna" value="WNA" {{ old('kewarganegaraan')=='WNA'?'checked':'' }}>
                    <label class="form-check-label" for="wna">WNA</label>
                </div>
            </div>
            <div class="col-md-3 mb-3 d-none" id="wrapper-negara-asal">
                <label class="form-label">Negara Asal</label>
                <input type="text" name="negara_asal" id="negara_asal" value="{{ old('negara_asal') }}" class="form-control">
            </div>
            <div class="col-md-3 mb-3">
                <label class="form-label">Tanggal Lahir</label>
                <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}" class="form-control" required>
                <div class="form-text">Usia minimal 14 tahun.</div>
            </div>
            <div class="col-md-3 mb-3">
                <label class="form-label">Tempat Lahir</label>
                <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir') }}" class="form-control" placeholder="Kota/Kabupaten" required>
            </div>
            <div class="col-md-3 mb-3">
                <label class="form-label d-block">Jenis Kelamin</label>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="jenis_kelamin" id="pria" value="Pria" {{ old('jenis_kelamin')=='Pria'?'checked':'' }} required>
                    <label class="form-check-label" for="pria">Pria</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="jenis_kelamin" id="wanita" value="Wanita" {{ old('jenis_kelamin')=='Wanita'?'checked':'' }}>
                    <label class="form-check-label" for="wanita">Wanita</label>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <label class="form-label">Status Perkawinan</label>
                <select name="status_perkawinan" class="form-select" required>
                    <option value="">-- Pilih --</option>
                    <option value="Belum Menikah" {{ old('status_perkawinan')=='Belum Menikah'?'selected':'' }}>Belum Menikah</option>
                    <option value="Menikah" {{ old('status_perkawinan')=='Menikah'?'selected':'' }}>Menikah</option>
                    <option value="Lain-lain" {{ old('status_perkawinan')=='Lain-lain'?'selected':'' }}>Lain-lain</option>
                </select>
            </div>
            <div class="col-md-3 mb-3">
                <label class="form-label">Agama</label>
                <select name="religion_id" class="form-select" required>
                    <option value="">-- Pilih --</option>
                    @foreach($religions as $religion)
                        <option value="{{ $religion->id }}" {{ old('religion_id')==$religion->id?'selected':'' }}>{{ $religion->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 mb-3">
                <label class="form-label">Foto <span class="text-muted small">(opsional, JPG/PNG maks 2MB)</span></label>
                <input type="file" name="foto" id="foto" accept="image/*" class="form-control">
                <img id="preview-foto" src="#" alt="Preview" class="mt-2 rounded d-none" style="max-width:120px;">
            </div>
        </div>

        <h6 class="text-primary border-bottom pb-2 mb-3 mt-4">Pilihan Program Studi & Gelombang</h6>
        <div class="row">
            <div class="col-md-4 mb-3">
                <label class="form-label">Program Studi Pilihan 1</label>
                <select name="program_studi_1_id" id="prodi1" class="form-select" required>
                    <option value="">-- Pilih --</option>
                    @foreach($programStudis as $prodi)
                        <option value="{{ $prodi->id }}" {{ old('program_studi_1_id')==$prodi->id?'selected':'' }}>{{ $prodi->nama }} ({{ $prodi->jenjang }})</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Program Studi Pilihan 2</label>
                <select name="program_studi_2_id" id="prodi2" class="form-select" required>
                    <option value="">-- Pilih --</option>
                    @foreach($programStudis as $prodi)
                        <option value="{{ $prodi->id }}" {{ old('program_studi_2_id')==$prodi->id?'selected':'' }}>{{ $prodi->nama }} ({{ $prodi->jenjang }})</option>
                    @endforeach
                </select>
                <div class="form-text">Harus berbeda dari pilihan 1.</div>
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Gelombang</label>
                <select name="gelombang_id" class="form-select" required>
                    <option value="">-- Pilih --</option>
                    @foreach($gelombangs as $gelombang)
                        <option value="{{ $gelombang->id }}" {{ old('gelombang_id')==$gelombang->id?'selected':'' }}>{{ $gelombang->nama }}</option>
                    @endforeach
                </select>
                @if($gelombangs->isEmpty())
                    <div class="form-text text-danger">Tidak ada gelombang aktif saat ini. Hubungi admin.</div>
                @endif
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Jalur Pendaftaran</label>
                <select name="jalur_pendaftaran" class="form-select" required>
                    <option value="Reguler">Reguler</option>
                    <option value="Beasiswa">Beasiswa</option>
                    <option value="Mandiri">Mandiri</option>
                </select>
            </div>
        </div>

        <h6 class="text-primary border-bottom pb-2 mb-3 mt-4">Data Pendidikan Asal <span class="text-muted small fw-normal">(pelengkap, opsional)</span></h6>
        <div class="row">
            <div class="col-md-5 mb-3">
                <label class="form-label">Asal Sekolah</label>
                <input type="text" name="asal_sekolah" value="{{ old('asal_sekolah') }}" class="form-control">
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Jurusan Asal Sekolah</label>
                <input type="text" name="jurusan_asal_sekolah" value="{{ old('jurusan_asal_sekolah') }}" class="form-control">
            </div>
            <div class="col-md-1 mb-3">
                <label class="form-label">Tahun Lulus</label>
                <input type="number" name="tahun_lulus" value="{{ old('tahun_lulus') }}" class="form-control" min="2000" max="{{ date('Y') }}">
            </div>
            <div class="col-md-2 mb-3">
                <label class="form-label">Nilai Rata-rata</label>
                <input type="number" step="0.01" name="nilai_rata_rata" value="{{ old('nilai_rata_rata') }}" class="form-control" min="0" max="100">
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
        if (!provinceId) { regencySelect.innerHTML = '<option value="">-- Pilih Provinsi Dahulu --</option>'; return; }

        fetch(`{{ url('mahasiswa/provinces') }}/${provinceId}/regencies`)
            .then(res => res.json())
            .then(data => {
                regencySelect.innerHTML = '<option value="">-- Pilih Kabupaten/Kota --</option>';
                data.forEach(item => {
                    const opt = document.createElement('option');
                    opt.value = item.id; opt.textContent = item.name;
                    regencySelect.appendChild(opt);
                });
            })
            .catch(() => { regencySelect.innerHTML = '<option value="">Gagal memuat data</option>'; });
    });

    // Tombol "Sama dengan alamat KTP" -> salin ke alamat domisili
    document.getElementById('btn-salin-alamat').addEventListener('click', function () {
        document.getElementById('alamat_domisili').value = document.getElementById('alamat_ktp').value;
    });

    // Tampilkan field "Negara Asal" hanya jika kewarganegaraan = WNA
    function toggleNegaraAsal() {
        const isWna = document.getElementById('wna').checked;
        const wrapper = document.getElementById('wrapper-negara-asal');
        wrapper.classList.toggle('d-none', !isWna);
        document.getElementById('negara_asal').required = isWna;
        if (!isWna) document.getElementById('negara_asal').value = '';
    }
    document.getElementById('wni').addEventListener('change', toggleNegaraAsal);
    document.getElementById('wna').addEventListener('change', toggleNegaraAsal);

    // Preview foto sebelum upload
    document.getElementById('foto').addEventListener('change', function (e) {
        const file = e.target.files[0];
        const preview = document.getElementById('preview-foto');
        if (file) { preview.src = URL.createObjectURL(file); preview.classList.remove('d-none'); }
    });

    // Validasi field angka: nik, nomor_hp, nomor_telepon, kode_pos
    function pasangValidasiAngka(id, label) {
        const el = document.getElementById(id);
        if (!el) return;
        el.addEventListener('input', function () {
            const original = this.value;
            const cleaned = original.replace(/[^0-9]/g, '');
            if (original !== cleaned) {
                Swal.fire({ icon: 'warning', title: 'Input tidak valid', text: `${label} hanya boleh berisi angka.` });
            }
            this.value = cleaned;
        });
    }
    pasangValidasiAngka('nik', 'NIK');
    pasangValidasiAngka('nomor_hp', 'Nomor HP');
    pasangValidasiAngka('nomor_telepon', 'Nomor telepon');
    pasangValidasiAngka('kode_pos', 'Kode pos');

    // Validasi email: hanya karakter yang diizinkan, dan simbol '@' saja (bebas domain)
    function validasiEmailGmail(inputId) {
        const el = document.getElementById(inputId);
        if (!el) return;

        el.addEventListener('input', function () {
            const original = this.value;
            let cleaned = original.replace(/[^a-zA-Z0-9._%+\-@]/g, '');

            const jumlahAt = (cleaned.match(/@/g) || []).length;
            if (jumlahAt > 1) {
                const posisiAt = cleaned.indexOf('@');
                cleaned = cleaned.slice(0, posisiAt + 1) + cleaned.slice(posisiAt + 1).replace(/@/g, '');
            }

            if (original !== cleaned) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Simbol tidak valid',
                    text: 'Email hanya boleh berisi huruf, angka, titik, underscore, dan simbol "@" — bukan "#" atau simbol lainnya.',
                });
            }
            this.value = cleaned;
        });

        el.addEventListener('blur', function () {
            if (!this.value) return;
            const pattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!pattern.test(this.value)) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Format email tidak valid',
                    text: 'Pastikan format email benar, contoh: nama@domain.com.',
                });
            }
        });
    }
    validasiEmailGmail('email');

    // Program Studi 2 tidak boleh sama dengan Program Studi 1
    function cegahProdiSama() {
        const p1 = document.getElementById('prodi1').value;
        Array.from(document.getElementById('prodi2').options).forEach(opt => {
            opt.disabled = (opt.value !== '' && opt.value === p1);
        });
        if (document.getElementById('prodi2').value === p1 && p1 !== '') {
            document.getElementById('prodi2').value = '';
        }
    }
    document.getElementById('prodi1').addEventListener('change', cegahProdiSama);

    // Alert konfirmasi sebelum submit
    document.getElementById('form-pendaftaran').addEventListener('submit', function (e) {
        e.preventDefault();
        const form = this;
        if (document.getElementById('prodi1').value !== '' && document.getElementById('prodi1').value === document.getElementById('prodi2').value) {
            Swal.fire({ icon: 'error', title: 'Program studi tidak valid', text: 'Program Studi Pilihan 2 harus berbeda dari Pilihan 1.' });
            return;
        }
        Swal.fire({
            title: 'Kirim Pendaftaran?',
            text: 'Pastikan seluruh data yang Anda isi sudah benar sebelum disimpan.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Simpan',
            cancelButtonText: 'Periksa Lagi',
        }).then((result) => { if (result.isConfirmed) form.submit(); });
    });
</script>
@endpush
@endsection
