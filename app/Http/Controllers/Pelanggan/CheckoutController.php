<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use App\Models\DetailPesanan;
use App\Models\Pesanan;
use App\Models\Produk;
use App\Models\RiwayatStok;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Throwable;

class CheckoutController extends Controller
{
    public function index()
    {
        $cart = session('cart', []);

        if (empty($cart)) {
            return redirect()->route('keranjang.index')
                ->withErrors([
                    'keranjang' => 'Keranjang belanja masih kosong.',
                ]);
        }

        $produks = Produk::whereIn(
            'id',
            array_map('intval', array_keys($cart))
        )->get()->keyBy('id');

        $items = [];
        $subtotal = 0;

        foreach ($cart as $id => $jumlah) {
            $produk = $produks->get((int) $id);

            if (!$produk) {
                continue;
            }

            $jumlah = (int) $jumlah;

            if ($jumlah < 1) {
                continue;
            }

            $subtotalItem = (float) $produk->harga * $jumlah;

            $items[] = [
                'produk' => $produk,
                'jumlah' => $jumlah,
                'subtotal' => $subtotalItem,
            ];

            $subtotal += $subtotalItem;
        }

        if (empty($items)) {
            return redirect()->route('keranjang.index')
                ->withErrors([
                    'keranjang' => 'Produk di keranjang sudah tidak tersedia.',
                ]);
        }

        $ongkir = 0;
        $total = $subtotal;
        $user = auth()->user();

        return view('pelanggan.checkout.index', compact(
            'items',
            'subtotal',
            'ongkir',
            'total',
            'user'
        ));
    }

    public function store(Request $request)
    {
        $metodePengambilan = $request->input('metode_pengambilan');

        $aturanPembayaran = [
            'required',
            Rule::in(['cash', 'qris']),
        ];

        if ($metodePengambilan === 'diantar') {
            $aturanPembayaran = [
                'required',
                Rule::in(['qris']),
            ];
        }

        $validated = $request->validate([
            'metode_pengambilan' => [
                'required',
                Rule::in(['jemput_sendiri', 'diantar']),
            ],

            'metode_pembayaran' => $aturanPembayaran,

            'nama_penerima' => [
                Rule::requiredIf($metodePengambilan === 'diantar'),
                'nullable',
                'string',
                'max:255',
            ],

            'no_whatsapp' => [
                Rule::requiredIf($metodePengambilan === 'diantar'),
                'nullable',
                'string',
                'max:20',
            ],

            'alamat' => [
                Rule::requiredIf($metodePengambilan === 'diantar'),
                'nullable',
                'string',
                'max:1000',
            ],

            'catatan' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ]);

        $cart = session('cart', []);

        if (empty($cart)) {
            return redirect()->route('keranjang.index')
                ->withErrors([
                    'keranjang' => 'Keranjang belanja masih kosong.',
                ]);
        }

        $cart = collect($cart)
            ->mapWithKeys(fn ($jumlah, $id) => [
                (int) $id => (int) $jumlah,
            ])
            ->all();

        foreach ($cart as $id => $jumlah) {
            if ($id < 1 || $jumlah < 1) {
                throw ValidationException::withMessages([
                    'keranjang' => 'Data produk di keranjang tidak valid.',
                ]);
            }
        }

        $kodePesanan = 'GIBM-'
            . now()->format('Ymd')
            . '-'
            . Str::upper(Str::random(5));

        try {
            DB::transaction(function () use (
                $validated,
                $cart,
                $kodePesanan,
                $request
            ) {
                $produks = Produk::whereIn('id', array_keys($cart))
                    ->orderBy('id')
                    ->lockForUpdate()
                    ->get()
                    ->keyBy('id');

                if ($produks->count() !== count($cart)) {
                    throw ValidationException::withMessages([
                        'keranjang' => 'Ada produk yang sudah tidak tersedia.',
                    ]);
                }

                $subtotal = 0;

                foreach ($cart as $id => $jumlah) {
                    $produk = $produks->get($id);

                    if (!$produk || $produk->stok < $jumlah) {
                        $namaProduk = $produk?->nama_produk ?? 'Produk';

                        throw ValidationException::withMessages([
                            'keranjang' => "Stok {$namaProduk} tidak mencukupi.",
                        ]);
                    }

                    $subtotal += (float) $produk->harga * $jumlah;
                }

                $ongkir = 0;
                $total = $subtotal;

                $user = $request->user();
                $jemputSendiri =
                    $validated['metode_pengambilan'] === 'jemput_sendiri';

                $metodeQris =
                    $validated['metode_pembayaran'] === 'qris';

                $pesanan = Pesanan::create([
                    'user_id' => $user->id,
                    'kode_pesanan' => $kodePesanan,

                    'nama_penerima' => $jemputSendiri
                        ? $user->name
                        : $validated['nama_penerima'],

                    'no_whatsapp' => $jemputSendiri
                        ? ($user->no_whatsapp ?: '-')
                        : $validated['no_whatsapp'],

                    'alamat' => $jemputSendiri
                        ? null
                        : $validated['alamat'],

                    'metode_pengambilan' =>
                        $validated['metode_pengambilan'],

                    'catatan' => $validated['catatan'] ?? null,

                    'subtotal' => $subtotal,
                    'ongkir' => $ongkir,
                    'total' => $total,

                    'metode_pembayaran' =>
                        $validated['metode_pembayaran'],

                    'bukti_pembayaran' => null,

                    'status' => 'menunggu_konfirmasi',

                    'status_pembayaran' => $metodeQris
                        ? 'menunggu_pembayaran'
                        : 'belum_bayar',
                    
                    'batas_pembayaran' => $metodeQris
                        ? now()->addMinutes(30)
                        : null,
                ]);

                foreach ($cart as $id => $jumlah) {
                    $produk = $produks->get($id);

                    $stokSebelum = (int) $produk->stok;
                    $stokSesudah = $stokSebelum - $jumlah;
                    $harga = (float) $produk->harga;
                    $subtotalItem = $harga * $jumlah;

                    DetailPesanan::create([
                        'pesanan_id' => $pesanan->id,
                        'produk_id' => $produk->id,
                        'nama_produk' => $produk->nama_produk,
                        'harga' => $harga,
                        'jumlah' => $jumlah,
                        'subtotal' => $subtotalItem,
                    ]);

                    $produk->stok = $stokSesudah;
                    $produk->save();

                    RiwayatStok::create([
                        'produk_id' => $produk->id,
                        'jenis' => 'keluar',
                        'jumlah' => $jumlah,
                        'stok_sebelum' => $stokSebelum,
                        'stok_sesudah' => $stokSesudah,
                        'keterangan' => 'Penjualan pesanan ' . $kodePesanan,
                    ]);
                }
            });
        } catch (Throwable $e) {
            throw $e;
        }

        session()->forget('cart');

        if ($validated['metode_pembayaran'] === 'qris') {
            return redirect()->route('checkout.payment', $kodePesanan);
        }

        return redirect()->route('checkout.success', $kodePesanan);
    }

    /**
     * Menampilkan halaman pembayaran QRIS.
     */
    public function payment(string $kode_pesanan)
    {
        $pesanan = Pesanan::with('detailPesanan')
            ->where('kode_pesanan', $kode_pesanan)
            ->where('user_id', auth()->id())
            ->where('metode_pembayaran', 'qris')
            ->firstOrFail();

        return view('pelanggan.checkout.payment', compact('pesanan'));
    }

    /**
     * Menyimpan bukti pembayaran untuk pesanan yang sudah dibuat.
     */
    
    public function uploadProof(Request $request, $kode_pesanan)
    {
        $pesanan = Pesanan::where('kode_pesanan', $kode_pesanan)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        if ($pesanan->metode_pembayaran !== 'qris') {
            return redirect()->route('checkout.success', $pesanan->kode_pesanan)
                ->with('error', 'Pesanan ini tidak menggunakan pembayaran QRIS.');
        }

        $validated = $request->validate([
            'bukti_pembayaran' => 'required|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        $path = $request->file('bukti_pembayaran')
            ->store('bukti-pembayaran', 'public');

        $pesanan->update([
            'bukti_pembayaran' => $path,
            'status_pembayaran' => 'menunggu_verifikasi',
        ]);

        return redirect()
            ->route('checkout.success', $pesanan->kode_pesanan)
            ->with('success', 'Bukti pembayaran berhasil diunggah.');
    }

    public function success(string $kode_pesanan)
    {
        $pesanan = Pesanan::with('detailPesanan')
            ->where('kode_pesanan', $kode_pesanan)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        return view('pelanggan.checkout.success', compact('pesanan'));
    }
}
