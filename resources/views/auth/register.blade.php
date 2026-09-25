@extends('layouts.app')
@section('title', 'Registrasi - PMB Online')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card shadow-sm mt-5">
            <div class="card-body p-4">
                <h4 class="text-center mb-1"><i class="fa-solid fa-user-plus"></i> Registrasi Akun</h4>
                <p class="text-center text-muted mb-4">Untuk Calon Mahasiswa Baru</p>

                <form method="POST" action="{{ route('register.attempt') }}" id="form-register">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" name="name" value="{{ old('name') }}" class="form-control" minlength="3" maxlength="100" required autofocus>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nomor HP</label>
                        <input type="text" name="nomor_hp" id="nomor_hp" value="{{ old('nomor_hp') }}" class="form-control" minlength="10" maxlength="15" required>
                        <div class="form-text">10-15 digit angka.</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kata Sandi</label>
                        <input type="password" name="password" class="form-control" required minlength="8">
                        <div class="form-text">Minimal 8 karakter.</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Konfirmasi Kata Sandi</label>
                        <input type="password" name="password_confirmation" class="form-control" required minlength="8">
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" name="persetujuan" value="1" class="form-check-input" id="persetujuan" required {{ old('persetujuan') ? 'checked' : '' }}>
                        <label class="form-check-label small" for="persetujuan">
                            Saya menyetujui <a href="#" data-bs-toggle="modal" data-bs-target="#modalKetentuan">ketentuan pendaftaran</a> yang berlaku.
                        </label>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Daftar</button>
                </form>
                <hr>
                <p class="text-center small mb-0">Sudah punya akun? <a href="{{ route('login') }}">Login di sini</a></p>
            </div>
        </div>
    </div>
</div>

<!-- Modal Ketentuan Pendaftaran -->
<div class="modal fade" id="modalKetentuan" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ketentuan Pendaftaran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body small">
                <ol>
                    <li>Data yang diisi harus benar dan dapat dipertanggungjawabkan.</li>
                    <li>Satu akun hanya dapat digunakan untuk satu kali pendaftaran.</li>
                    <li>Panitia berhak menolak pendaftaran dengan data tidak valid.</li>
                </ol>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Validasi nomor HP hanya angka + alert (F.1: 10-15 digit)
    document.getElementById('nomor_hp').addEventListener('input', function () {
        const original = this.value;
        const cleaned = original.replace(/[^0-9]/g, '');
        if (original !== cleaned) {
            Swal.fire({ icon: 'warning', title: 'Nomor HP tidak valid', text: 'Nomor HP hanya boleh berisi angka.' });
        }
        this.value = cleaned;
    });

    // Validasi email: hanya karakter yang diizinkan, dan simbol '@' saja (bebas domain)
    function validasiEmailGmail(inputId) {
        const el = document.getElementById(inputId);
        if (!el) return;

        // Saat mengetik: buang karakter yang tidak diizinkan (mis. '#' atau simbol lain selain '@')
        el.addEventListener('input', function () {
            const original = this.value;
            let cleaned = original.replace(/[^a-zA-Z0-9._%+\-@]/g, '');

            // Pastikan hanya ada satu tanda '@'
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

        // Saat keluar dari field: pastikan formatnya email umum yang valid (bebas domain apa saja)
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
</script>
@endpush
@endsection
