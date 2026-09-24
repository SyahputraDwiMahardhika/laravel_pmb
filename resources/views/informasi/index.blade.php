@extends('layouts.app')
@section('title', 'Informasi PMB - PMB Online')

@section('content')
<div class="p-4 rounded mb-4 text-white" style="background: linear-gradient(120deg,#1e3a8a,#2563eb);">
    <img src="https://via.placeholder.com/1200x300/2563eb/ffffff?text=Banner+Penerimaan+Mahasiswa+Baru"
         alt="Banner PMB" class="img-fluid rounded mb-3 w-100">
    <h2>Penerimaan Mahasiswa Baru — Tahun Akademik {{ date('Y') }}/{{ date('Y') + 1 }}</h2>
    <p class="mb-0">Daftarkan dirimu sekarang dan wujudkan masa depan cerah bersama kami!</p>
</div>

<div class="row mb-4">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-body">
                <h5><i class="fa-solid fa-circle-play"></i> Video Informasi PMB</h5>
                <p class="text-muted small">
                    Video di bawah ini adalah placeholder demo. Letakkan file video Anda di
                    <code>public/videos/informasi-pmb.mp4</code> agar video benar-benar tampil.
                </p>
                <video width="100%" controls poster="https://via.placeholder.com/800x450/1e293b/ffffff?text=Video+Informasi+PMB">
                    <source src="{{ asset('videos/informasi-pmb.mp4') }}" type="video/mp4">
                    Browser Anda tidak mendukung tag video. Silakan unduh videonya
                    <a href="{{ asset('videos/informasi-pmb.mp4') }}">di sini</a>.
                </video>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <h5><i class="fa-solid fa-list-check"></i> Alur Pendaftaran</h5>
                <ol class="small ps-3">
                    <li>Buat akun (Registrasi)</li>
                    <li>Login ke sistem</li>
                    <li>Isi formulir pendaftaran PMB</li>
                    <li>Tunggu verifikasi oleh Admin</li>
                    <li>Cetak bukti pendaftaran</li>
                </ol>
                <a href="{{ route('register') }}" class="btn btn-primary w-100">Daftar Sekarang</a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-4 mb-3">
        <div class="card shadow-sm h-100"><div class="card-body">
            <h6><i class="fa-solid fa-book"></i> Program Studi</h6>
            <p class="small text-muted mb-0">Tersedia berbagai pilihan program studi jenjang D3, D4, hingga S1.</p>
        </div></div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card shadow-sm h-100"><div class="card-body">
            <h6><i class="fa-solid fa-money-bill-wave"></i> Jalur Pendaftaran</h6>
            <p class="small text-muted mb-0">Reguler, Beasiswa, dan Mandiri — pilih sesuai kebutuhanmu.</p>
        </div></div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card shadow-sm h-100"><div class="card-body">
            <h6><i class="fa-solid fa-headset"></i> Butuh Bantuan?</h6>
            <p class="small text-muted mb-0">Hubungi panitia PMB melalui email panitia@pmb.test.</p>
        </div></div>
    </div>
</div>
@endsection
