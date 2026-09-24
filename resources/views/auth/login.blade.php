@extends('layouts.app')
@section('title', 'Login - PMB Online')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card shadow-sm mt-5">
            <div class="card-body p-4">
                <h4 class="text-center mb-1"><i class="fa-solid fa-graduation-cap"></i> PMB Online</h4>
                <p class="text-center text-muted mb-4">Silakan login untuk melanjutkan</p>

                <form method="POST" action="{{ route('login.attempt') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" class="form-control" required autofocus>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" name="remember" class="form-check-input" id="remember">
                        <label class="form-check-label" for="remember">Ingat saya</label>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Login</button>
                </form>

                <hr>
                <p class="text-center small mb-1">Belum punya akun calon mahasiswa?
                    <a href="{{ route('register') }}">Daftar di sini</a>
                </p>
                <div class="text-center small text-muted mt-3">
                    <p class="mb-0">Akun demo Admin: admin@pmb.test / password</p>
                    <p class="mb-0">Akun demo Mahasiswa: mahasiswa@pmb.test / password</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
