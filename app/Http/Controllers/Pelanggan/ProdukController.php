<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use App\Models\Produk;
use Illuminate\Http\Request;

class ProdukController extends Controller
{
    public function index(Request $request)
    {
        $query = Produk::with('kategori');

        // Pencarian nama produk
        if ($request->filled('cari')) {
            $cari = trim($request->cari);

            $query->where('nama_produk', 'like', '%' . $cari . '%');
        }

        // Filter berdasarkan slug kategori
        if ($request->filled('kategori')) {
            $query->whereHas('kategori', function ($q) use ($request) {
                $q->where('slug', $request->kategori);
            });
        }

        $produks = $query
            ->latest()
            ->paginate(12)
            ->withQueryString();

        $kategoris = Kategori::orderBy('nama_kategori')->get();

        return view('pelanggan.produk.index', compact(
            'produks',
            'kategoris'
        ));
    }

    public function show(string $slug)
    {
        $produk = Produk::with('kategori')
            ->where('slug', $slug)
            ->firstOrFail();

        $produkLainnya = Produk::with('kategori')
            ->where('kategori_id', $produk->kategori_id)
            ->where('id', '!=', $produk->id)
            ->latest()
            ->take(4)
            ->get();

        return view('pelanggan.produk.show', compact(
            'produk',
            'produkLainnya'
        ));
    }
}
