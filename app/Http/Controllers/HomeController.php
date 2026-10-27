<?php

namespace App\Http\Controllers;

use App\Models\Produk;

class HomeController extends Controller
{
    public function index()
    {
        $produks = Produk::query()
            ->whereNotNull('gambar')
            ->where('gambar', '!=', '')
            ->latest()
            ->get();

        return view('welcome', compact('produks'));
    }
}
