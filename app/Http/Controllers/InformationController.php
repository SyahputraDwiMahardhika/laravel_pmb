<?php

namespace App\Http\Controllers;

class InformationController extends Controller
{
    // Halaman publik "Informasi PMB" berisi banner gambar & video (requirement multimedia).
    public function index()
    {
        return view('informasi.index');
    }
}
