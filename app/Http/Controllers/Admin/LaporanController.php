<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $bulan = (int) $request->input('bulan', now()->month);
        $tahun = (int) $request->input('tahun', now()->year);

        // Validasi filter
        $bulan = ($bulan >= 1 && $bulan <= 12) ? $bulan : now()->month;
        $tahun = ($tahun >= 2026) ? $tahun : now()->year;

        $query = Pesanan::with('pelanggan')
            ->whereMonth('created_at', $bulan)
            ->whereYear('created_at', $tahun);

        $totalTransaksi = (clone $query)->count();

        $totalSelesai = (clone $query)
            ->where('status', 'selesai')
            ->count();

        $totalDibatalkan = (clone $query)
            ->where('status', 'dibatalkan')
            ->count();

        $totalPendapatan = (clone $query)
            ->where('status_pembayaran', 'lunas')
            ->where('status', '!=', 'dibatalkan')
            ->sum('total');

        $pesanan = (clone $query)
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.laporan.index', compact(
            'bulan',
            'tahun',
            'pesanan',
            'totalTransaksi',
            'totalSelesai',
            'totalDibatalkan',
            'totalPendapatan'
        ));
    }

    public function exportPdf(Request $request)
    {
        $bulan = (int) $request->input('bulan', now()->month);
        $tahun = (int) $request->input('tahun', now()->year);

        $bulan = ($bulan >= 1 && $bulan <= 12) ? $bulan : now()->month;
        $tahun = ($tahun >= 2026) ? $tahun : now()->year;

        $query = Pesanan::with('pelanggan')
            ->whereMonth('created_at', $bulan)
            ->whereYear('created_at', $tahun);

        $pesanan = (clone $query)->latest()->get();

        $totalTransaksi = (clone $query)->count();

        $totalSelesai = (clone $query)
            ->where('status', 'selesai')
            ->count();

        $totalDibatalkan = (clone $query)
            ->where('status', 'dibatalkan')
            ->count();

        $totalPendapatan = (clone $query)
            ->where('status_pembayaran', 'lunas')
            ->where('status', '!=', 'dibatalkan')
            ->sum('total');

        $namaBulan = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ];

        $pdf = Pdf::loadView('admin.laporan.pdf', compact(
            'bulan',
            'tahun',
            'namaBulan',
            'pesanan',
            'totalTransaksi',
            'totalSelesai',
            'totalDibatalkan',
            'totalPendapatan'
        ))->setPaper('a4', 'landscape');

        return $pdf->download("laporan-transaksi-{$tahun}-" . str_pad($bulan, 2, '0', STR_PAD_LEFT) . '.pdf');
    }
}
