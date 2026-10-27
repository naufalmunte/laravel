<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Produk;
use App\Models\RiwayatStok;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Barryvdh\DomPDF\Facade\Pdf;

class ManajemenStokController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'search' => ['nullable', 'string', 'max:255'],
            'bulan' => ['nullable', 'integer', 'between:1,12'],
            'tahun' => ['nullable', 'integer', 'between:2000,2100'],
        ]);

        // Daftar produk
        $query = Produk::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('nama_produk', 'like', "%{$search}%");
        }

        $produk = $query
            ->orderBy('nama_produk')
            ->paginate(10)
            ->withQueryString();

        // Riwayat stok dengan filter bulan dan tahun
        $riwayatQuery = RiwayatStok::with('produk');

        if ($request->filled('bulan')) {
            $riwayatQuery->whereMonth('created_at', $request->bulan);
        }

        if ($request->filled('tahun')) {
            $riwayatQuery->whereYear('created_at', $request->tahun);
        }

        $riwayatStok = $riwayatQuery
            ->latest()
            ->paginate(10, ['*'], 'riwayat_page')
            ->withQueryString();

        return view('admin.stok.index', compact(
            'produk',
            'riwayatStok'
        ));
    }

    public function create()
    {
        $produk = Produk::orderBy('nama_produk')->get();

        return view('admin.stok.form', compact('produk'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'produk_id' => [
                'required',
                'integer',
                'exists:produk,id',
            ],
            'jenis' => [
                'required',
                Rule::in(['masuk', 'penyesuaian']),
            ],
            'jumlah' => [
                'required',
                'integer',
                'min:0',
            ],
            'keterangan' => [
                'nullable',
                'string',
                'max:255',
            ],
        ]);

        DB::transaction(function () use ($validated) {
            $produk = Produk::whereKey($validated['produk_id'])
                ->lockForUpdate()
                ->firstOrFail();

            $stokSebelum = $produk->stok;

            if ($validated['jenis'] === 'masuk') {
                if ($validated['jumlah'] < 1) {
                    throw \Illuminate\Validation\ValidationException::withMessages([
                        'jumlah' => 'Jumlah restock harus lebih dari 0.',
                    ]);
                }

                $stokSesudah = $stokSebelum + $validated['jumlah'];
                $jumlahPerubahan = $validated['jumlah'];
            } else {
                $stokSesudah = $validated['jumlah'];
                $jumlahPerubahan = abs($stokSesudah - $stokSebelum);

                if ($jumlahPerubahan === 0) {
                    throw \Illuminate\Validation\ValidationException::withMessages([
                        'jumlah' => 'Stok fisik sama dengan stok saat ini. Tidak ada perubahan yang perlu dicatat.',
                    ]);
                }
            }

            $produk->update([
                'stok' => $stokSesudah,
            ]);

            RiwayatStok::create([
                'produk_id' => $produk->id,
                'jenis' => $validated['jenis'],
                'jumlah' => $jumlahPerubahan,
                'stok_sebelum' => $stokSebelum,
                'stok_sesudah' => $stokSesudah,
                'keterangan' => $validated['keterangan']
                    ?? ($validated['jenis'] === 'masuk'
                        ? 'Restock produk'
                        : 'Penyesuaian stok fisik'),
            ]);
        });

        return redirect()
            ->route('admin.stok.index')
            ->with('success', 'Stok produk berhasil diperbarui.');
    }

    public function exportPdf(Request $request)
    {
        $validated = $request->validate([
            'bulan' => ['required', 'integer', 'between:1,12'],
            'tahun' => ['required', 'integer', 'between:2000,2100'],
        ]);

        $riwayatStok = RiwayatStok::with('produk')
            ->whereMonth('created_at', $validated['bulan'])
            ->whereYear('created_at', $validated['tahun'])
            ->orderBy('created_at')
            ->get();

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

        $periode = $namaBulan[(int) $validated['bulan']]
            . ' ' . $validated['tahun'];

        $pdf = Pdf::loadView('admin.stok.pdf', compact(
            'riwayatStok',
            'periode'
        ))->setPaper('a4', 'landscape');

        return $pdf->download(
            'riwayat-stok-' . $validated['tahun'] . '-'
            . str_pad($validated['bulan'], 2, '0', STR_PAD_LEFT)
            . '.pdf'
        );
    }
}