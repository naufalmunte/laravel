<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Produk;
use App\Models\Pesanan;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistik produk
        $totalProduk = Produk::count();

        $produkStokMenipis = Produk::where('stok', '<=', 5)
            ->orderBy('stok')
            ->limit(5)
            ->get();

        $totalStokMenipis = Produk::where('stok', '<=', 5)->count();

        // Statistik pesanan
        $totalPesanan = Pesanan::count();

        $menungguVerifikasi = Pesanan::where('metode_pembayaran', 'qris')
            ->where('status_pembayaran', 'menunggu_verifikasi')
            ->count();

        $totalBelumBayar = Pesanan::where('status_pembayaran', 'belum_bayar')
            ->count();

        $totalLunas = Pesanan::where('status_pembayaran', 'lunas')
            ->count();

        // Pendapatan dari pembayaran yang sudah lunas
        $totalPendapatan = Pesanan::where('status_pembayaran', 'lunas')
            ->sum('total');

        $pendapatanHariIni = Pesanan::where('status_pembayaran', 'lunas')
            ->whereDate('created_at', today())
            ->sum('total');

        // Ringkasan status pesanan
        $ringkasanStatus = Pesanan::select('status', DB::raw('COUNT(*) as jumlah'))
            ->groupBy('status')
            ->pluck('jumlah', 'status');

        // Pesanan terbaru
        $pesananTerbaru = Pesanan::with('pelanggan')
            ->latest()
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalProduk',
            'totalPesanan',
            'menungguVerifikasi',
            'totalBelumBayar',
            'totalLunas',
            'totalPendapatan',
            'pendapatanHariIni',
            'ringkasanStatus',
            'pesananTerbaru',
            'produkStokMenipis',
            'totalStokMenipis'
        ));
    }
}