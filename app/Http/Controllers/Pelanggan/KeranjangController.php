<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use App\Models\Produk;
use Illuminate\Http\Request;

class KeranjangController extends Controller
{
    public function index()
    {
        $cart = session('cart', []);

        $produks = Produk::whereIn(
            'id',
            array_map('intval', array_keys($cart))
        )->get()->keyBy('id');

        $items = [];
        $total = 0;

        foreach ($cart as $id => $jumlah) {
            $produk = $produks->get((int) $id);

            if (!$produk) {
                continue;
            }

            $jumlah = (int) $jumlah;
            $subtotal = (float) $produk->harga * $jumlah;

            $items[] = [
                'produk' => $produk,
                'jumlah' => $jumlah,
                'subtotal' => $subtotal,
            ];

            $total += $subtotal;
        }

        return view('pelanggan.keranjang.index', compact(
            'items',
            'total'
        ));
    }

    public function store(Request $request, string $slug)
    {
        $validated = $request->validate([
            'jumlah' => ['required', 'integer', 'min:1'],
        ]);

        $produk = Produk::where('slug', $slug)->firstOrFail();
        $jumlah = (int) $validated['jumlah'];

        if ($produk->stok < 1) {
            return back()->withErrors([
                'keranjang' => 'Maaf, stok produk sedang habis.',
            ]);
        }

        $cart = session('cart', []);
        $id = (string) $produk->id;

        $jumlahBaru = (int) ($cart[$id] ?? 0) + $jumlah;

        if ($jumlahBaru > $produk->stok) {
            return back()->withErrors([
                'keranjang' => 'Jumlah produk melebihi stok yang tersedia.',
            ]);
        }

        $cart[$id] = $jumlahBaru;

        session(['cart' => $cart]);

        return redirect()
            ->route('keranjang.index')
            ->with('success', 'Produk berhasil ditambahkan ke keranjang.');
    }

    public function update(Request $request, int $id)
    {
        $validated = $request->validate([
            'jumlah' => ['required', 'integer', 'min:1'],
        ]);

        $cart = session('cart', []);
        $key = (string) $id;

        if (!array_key_exists($key, $cart)) {
            return redirect()->route('keranjang.index')
                ->withErrors(['keranjang' => 'Produk tidak ditemukan di keranjang.']);
        }

        $produk = Produk::find($id);

        if (!$produk) {
            unset($cart[$key]);
            session(['cart' => $cart]);

            return redirect()->route('keranjang.index')
                ->withErrors(['keranjang' => 'Produk sudah tidak tersedia.']);
        }

        $jumlah = (int) $validated['jumlah'];

        if ($jumlah > $produk->stok) {
            return back()->withErrors([
                'keranjang' => "Stok {$produk->nama_produk} hanya tersedia {$produk->stok}.",
            ]);
        }

        $cart[$key] = $jumlah;
        session(['cart' => $cart]);

        return redirect()->route('keranjang.index')
            ->with('success', 'Jumlah produk berhasil diperbarui.');
    }

    public function destroy(int $id)
    {
        $cart = session('cart', []);

        unset($cart[(string) $id]);

        session(['cart' => $cart]);

        return redirect()->route('keranjang.index')
            ->with('success', 'Produk berhasil dihapus dari keranjang.');
    }
}
