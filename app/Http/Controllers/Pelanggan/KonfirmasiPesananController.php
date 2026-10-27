<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class KonfirmasiPesananController extends Controller
{
    public function konfirmasiDiterima($id)
    {
        $pesanan = Pesanan::where('user_id', Auth::id())
            ->findOrFail($id);

        if ($pesanan->status !== 'menunggu_konfirmasi_pelanggan') {
            throw ValidationException::withMessages([
                'pesanan' => 'Pesanan belum dapat dikonfirmasi.',
            ]);
        }

        if (!$pesanan->bukti_penyerahan || !$pesanan->diserahkan_pada) {
            throw ValidationException::withMessages([
                'pesanan' => 'Bukti penyerahan barang belum tersedia.',
            ]);
        }

        $pesanan->update([
            'status' => 'selesai',
        ]);

        return back()->with(
            'success',
            'Terima kasih! Penerimaan barang berhasil dikonfirmasi.'
        );
    }
}
