<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class PesananController extends Controller
{
    public function index(Request $request)
    {
        $keyword = trim($request->input('cari', ''));

        $query = Pesanan::with([
            'pelanggan',
            'detailPesanan',
        ]);

        if ($keyword !== '') {
            $query->where(function ($q) use ($keyword) {
                $q->where('kode_pesanan', 'like', "%{$keyword}%")
                    ->orWhere('nama_penerima', 'like', "%{$keyword}%");
            });
        }

        $pesanans = $query->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.pesanan.index', compact(
            'pesanans',
            'keyword'
        ));
    }

    public function show($id)
    {
        $pesanan = Pesanan::with([
            'pelanggan',
            'detailPesanan.produk',
        ])->findOrFail($id);

        return view('admin.pesanan.show', compact('pesanan'));
    }

    public function updateStatus(Request $request, $id)
    {
        $pesanan = Pesanan::findOrFail($id);

        $validated = $request->validate([
            'status' => [
                'required',
                Rule::in([
                    'diproses',
                    'dikirim',
                    'siap_diambil',
                    'selesai',
                ]),
            ],
        ]);

        $statusSekarang = $pesanan->status;
        $statusBaru = $validated['status'];

        $alurStatus = [
            'menunggu_konfirmasi' => ['diproses'],
            'diproses' => $pesanan->metode_pengambilan === 'diantar'
                ? ['dikirim']
                : ['siap_diambil'],
        ];

        if (!in_array(
            $statusBaru,
            $alurStatus[$statusSekarang] ?? [],
            true
        )) {
            throw ValidationException::withMessages([
                'status' => 'Perubahan status pesanan tidak valid.',
            ]);
        }

        // Pesanan QRIS harus lunas sebelum diproses.
        if (
            $statusSekarang === 'menunggu_konfirmasi'
            && $pesanan->metode_pembayaran === 'qris'
            && $pesanan->status_pembayaran !== 'lunas'
        ) {
            throw ValidationException::withMessages([
                'status' => 'Verifikasi pembayaran QRIS terlebih dahulu.',
            ]);
        }

        $pesanan->update([
            'status' => $statusBaru,
        ]);

        return redirect()
            ->route('admin.pesanan.show', $pesanan->id)
            ->with('success', 'Status pesanan berhasil diperbarui.');
    }

    public function serahkanBarang(Request $request, $id)
    {
        $pesanan = Pesanan::findOrFail($id);

        if (!in_array($pesanan->status, [
            'dikirim',
            'siap_diambil',
        ], true)) {
            throw ValidationException::withMessages([
                'bukti_penyerahan' =>
                    'Pesanan belum berada pada tahap penyerahan barang.',
            ]);
        }

        if (
            $pesanan->metode_pembayaran === 'qris'
            && $pesanan->status_pembayaran !== 'lunas'
        ) {
            throw ValidationException::withMessages([
                'bukti_penyerahan' =>
                    'Pembayaran QRIS harus diverifikasi terlebih dahulu.',
            ]);
        }

        $validated = $request->validate([
            'bukti_penyerahan' => [
                'required',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
            ],
        ], [
            'bukti_penyerahan.required' =>
                'Foto bukti penyerahan wajib diunggah.',
            'bukti_penyerahan.image' =>
                'File harus berupa gambar.',
            'bukti_penyerahan.mimes' =>
                'Format gambar harus JPG, JPEG, PNG, atau WEBP.',
            'bukti_penyerahan.max' =>
                'Ukuran gambar maksimal 5 MB.',
        ]);

        // Simpan foto baru terlebih dahulu.
        $pathBaru = $validated['bukti_penyerahan']
            ->store('bukti-penyerahan', 'public');

        // Penyerahan pesanan tunai menandakan pembayaran diterima.
        if ($pesanan->metode_pembayaran === 'cash') {
            $pesanan->status_pembayaran = 'lunas';
        }

        // Waktu 24 jam dihitung sejak barang diserahkan.
        $pathLama = $pesanan->bukti_penyerahan;

        $pesanan->bukti_penyerahan = $pathBaru;
        $pesanan->diserahkan_pada = now();
        $pesanan->status = 'menunggu_konfirmasi_pelanggan';
        $pesanan->save();

        // Hapus foto lama jika sebelumnya ada.
        if ($pathLama && $pathLama !== $pathBaru) {
            Storage::disk('public')->delete($pathLama);
        }

        return redirect()
            ->route('admin.pesanan.show', $pesanan->id)
            ->with(
                'success',
                'Bukti penyerahan berhasil diunggah. Pesanan telah diserahkan kepada pelanggan.'
            );
    }
}