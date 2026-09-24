@extends('layouts.app')
@section('title', 'Registrasi - PMB Online')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card shadow-sm mt-5">
            <div class="card-body p-4">
                <h4 class="text-center mb-1"><i class="fa-solid fa-user-plus"></i> Registrasi Akun</h4>
                <p class="text-center text-muted mb-4">Untuk Calon Mahasiswa Baru</p>

                <form method="POST" action="{{ route('register.attempt') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" name="name" value="{{ old('name') }}" class="form-control" required autofocus>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required minlength="6">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" class="form-control" required minlength="6">
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Daftar</button>
                </form>
                <hr>
                <p class="text-center small mb-0">Sudah punya akun? <a href="{{ route('login') }}">Login di sini</a></p>
            </div>
        </div>
    </div>
</div>
@endsection
