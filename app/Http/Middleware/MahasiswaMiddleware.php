<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

// Middleware: hanya izinkan role mahasiswa mengakses halaman calon mahasiswa.
class MahasiswaMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user() || ! $request->user()->isMahasiswa()) {
            abort(403, 'Halaman ini hanya untuk calon mahasiswa.');
        }

        return $next($request);
    }
}
