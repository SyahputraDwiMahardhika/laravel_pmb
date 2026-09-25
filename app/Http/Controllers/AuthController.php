<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class AuthController extends Controller
{
    // Tampilkan form login (dipakai untuk admin & calon mahasiswa).
    public function showLogin()
    {
        return view('auth.login');
    }

    // Proses login. Setelah berhasil, arahkan sesuai role.
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Email harus menggunakan format email yang valid.',
            'password.required' => 'Password wajib diisi.',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            $user = Auth::user();

            if ($user->isAdmin()) {
                return redirect()->intended(route('admin.dashboard'))
                    ->with('success', 'Berhasil login sebagai Admin.');
            }

            return redirect()->intended(route('mahasiswa.dashboard'))
                ->with('success', 'Berhasil login. Selamat datang!');
        }

        return back()
            ->withErrors(['email' => 'Email atau password yang Anda masukkan salah.'])
            ->onlyInput('email');
    }

    // Tampilkan form registrasi akun calon mahasiswa.
    public function showRegister()
    {
        return view('auth.register');
    }

    // Proses registrasi akun baru (role otomatis: mahasiswa).
    // Field & validasi mengikuti dokumen soal "F.1 Form Registrasi Akun".
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:3|max:100',
            'email' => 'required|email|unique:users,email',
            'nomor_hp' => 'required|numeric|digits_between:10,15',
            'password' => 'required|string|min:8|confirmed',
            'persetujuan' => 'accepted',
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'name.min' => 'Nama lengkap minimal 3 karakter.',
            'name.max' => 'Nama lengkap maksimal 100 karakter.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Email harus menggunakan format email yang valid.',
            'email.unique' => 'Email sudah terdaftar, silakan gunakan email lain.',
            'nomor_hp.required' => 'Nomor HP wajib diisi.',
            'nomor_hp.numeric' => 'Nomor HP hanya boleh berisi angka.',
            'nomor_hp.digits_between' => 'Nomor HP harus 10-15 digit.',
            'password.required' => 'Kata sandi wajib diisi.',
            'password.min' => 'Kata sandi minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak sama.',
            'persetujuan.accepted' => 'Anda harus menyetujui ketentuan terlebih dahulu.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'nomor_hp' => $request->nomor_hp,
            'password' => Hash::make($request->password),
            'role' => 'mahasiswa',
        ]);

        Auth::login($user);

        return redirect()->route('mahasiswa.dashboard')
            ->with('success', 'Registrasi berhasil! Silakan lengkapi formulir pendaftaran PMB.');
    }

    // Logout & hancurkan session.
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Anda berhasil logout.');
    }
}
