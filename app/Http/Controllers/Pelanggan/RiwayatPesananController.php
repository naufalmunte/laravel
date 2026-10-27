<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use Illuminate\Http\Request;

class RiwayatPesananController extends Controller
{
    public function index(Request $request)
    {
        $pesanans = Pesanan::with('detailPesanan.produk')
            ->where('user_id', $request->user()->id)
            ->latest()
            ->paginate(10);

        return view('pelanggan.pesanan.index', compact('pesanans'));
    }

    public function show(Request $request, string $kode_pesanan)
    {
        $pesanan = Pesanan::with('detailPesanan.produk')
            ->where('user_id', $request->user()->id)
            ->where('kode_pesanan', $kode_pesanan)
            ->firstOrFail();

        return view('pelanggan.pesanan.show', compact('pesanan'));
    }
}
