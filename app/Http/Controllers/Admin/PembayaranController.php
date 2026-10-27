<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PembayaranController extends Controller
{
    public function index()
    {
        $pesanans = Pesanan::with('pelanggan')
            ->where(function ($query) {
                $query->where('metode_pembayaran', 'qris')
                    ->orWhere('metode_pembayaran', 'cash');
            })
            ->latest()
            ->paginate(10);

        return view('admin.pembayaran.index', compact('pesanans'));
    }

    public function verifikasi($id)
    {
        DB::transaction(function () use ($id) {
            $pesanan = Pesanan::whereKey($id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($pesanan->metode_pembayaran !== 'qris') {
                throw ValidationException::withMessages([
                    'pembayaran' =>
                        'Hanya pembayaran QRIS yang dapat diverifikasi melalui fitur ini.',
                ]);
            }

            if ($pesanan->status_pembayaran !== 'menunggu_verifikasi') {
                throw ValidationException::withMessages([
                    'pembayaran' =>
                        'Pembayaran ini tidak sedang menunggu verifikasi.',
                ]);
            }

            if (!$pesanan->bukti_pembayaran) {
                throw ValidationException::withMessages([
                    'pembayaran' =>
                        'Bukti pembayaran belum tersedia.',
                ]);
            }

            $pesanan->update([
                'status_pembayaran' => 'lunas',
            ]);
        });

        return redirect()
            ->route('admin.pembayaran.index')
            ->with('success', 'Pembayaran QRIS berhasil diverifikasi.');
    }
}
