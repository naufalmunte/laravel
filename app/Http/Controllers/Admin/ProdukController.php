<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use App\Models\RiwayatStok;
use Illuminate\Support\Facades\DB;

class ProdukController extends Controller
{
    public function index(Request $request)
    {
        $query = Produk::with('kategori');

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('nama_produk', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%");
            });
        }

        $produk = $query->latest()->paginate(10);

        return view('admin.produk.index', compact('produk'));
    }

    public function create()
    {
        $kategori = Kategori::orderBy('nama_kategori')->get();

        return view('admin.produk.create', compact('kategori'));
    }

   
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kategori_id' => [
                'required',
                'exists:kategori,id',
            ],
            'nama_produk' => [
                'required',
                'string',
                'max:255',
            ],
            'deskripsi' => [
                'nullable',
                'string',
            ],
            'harga' => [
                'required',
                'numeric',
                'min:0',
            ],
            'stok' => [
                'required',
                'integer',
                'min:0',
            ],
            'gambar' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],
        ]);

        $slug = Str::slug($validated['nama_produk']);

        if (Produk::where('slug', $slug)->exists()) {
            return back()
                ->withInput()
                ->withErrors([
                    'nama_produk' => 'Nama produk tersebut sudah digunakan.',
                ]);
        }

        $validated['slug'] = $slug;

        $gambar = null;

        if ($request->hasFile('gambar')) {
            $gambar = $request->file('gambar')
                ->store('produk', 'public');

            $validated['gambar'] = $gambar;
        }

        try {
            DB::transaction(function () use ($validated) {
                $produk = Produk::create($validated);

                // Catat stok awal jika jumlahnya lebih dari nol.
                if ($produk->stok > 0) {
                    RiwayatStok::create([
                        'produk_id' => $produk->id,
                        'jenis' => 'masuk',
                        'jumlah' => $produk->stok,
                        'stok_sebelum' => 0,
                        'stok_sesudah' => $produk->stok,
                        'keterangan' => 'Stok awal produk',
                    ]);
                }
            });
        } catch (\Throwable $e) {
            // Hapus gambar jika penyimpanan database gagal.
            if ($gambar) {
                \Illuminate\Support\Facades\Storage::disk('public')
                    ->delete($gambar);
            }

            throw $e;
        }

        return redirect()
            ->route('admin.produk.index')
            ->with('success', 'Produk berhasil ditambahkan.');
    }


    public function edit(Produk $produk)
    {
        $kategori = Kategori::orderBy('nama_kategori')->get();

        return view('admin.produk.edit', compact('produk', 'kategori'));
    }

    public function update(Request $request, Produk $produk)
    {
        $validated = $request->validate([
            'kategori_id' => [
                'required',
                'exists:kategori,id',
            ],
            'nama_produk' => [
                'required',
                'string',
                'max:255',
                Rule::unique('produk', 'nama_produk')->ignore($produk->id),
            ],
            'deskripsi' => [
                'nullable',
                'string',
            ],
            'harga' => [
                'required',
                'numeric',
                'min:0',
            ],
            'gambar' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],
        ]);

        $slug = Str::slug($validated['nama_produk']);

        $slugExists = Produk::where('slug', $slug)
            ->where('id', '!=', $produk->id)
            ->exists();

        if ($slugExists) {
            return back()
                ->withInput()
                ->withErrors([
                    'nama_produk' => 'Slug produk tersebut sudah digunakan.',
                ]);
        }

        $validated['slug'] = $slug;

        if ($request->hasFile('gambar')) {
            if ($produk->gambar && \Storage::disk('public')->exists($produk->gambar)) {
                \Storage::disk('public')->delete($produk->gambar);
            }

            $validated['gambar'] = $request->file('gambar')
                ->store('produk', 'public');
        }

        $produk->update($validated);

        return redirect()
            ->route('admin.produk.index')
            ->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Produk $produk)
    {
        DB::transaction(function () use ($produk) {
            RiwayatStok::where('produk_id', $produk->id)->delete();

            $produk->delete();
        });

        return redirect()
            ->route('admin.produk.index')
            ->with('success', 'Produk berhasil dihapus.');
    }
}
