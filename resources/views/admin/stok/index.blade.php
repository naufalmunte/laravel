@extends('layouts.admin')

@section('content')
    <div class="space-y-6">

        {{-- Header --}}
        <div>
            <h1 class="text-2xl font-bold text-gray-800">
                Manajemen Stok
            </h1>
            <p class="mt-1 text-sm text-gray-500">
                Pantau stok produk dan riwayat pergerakan stok.
            </p>
        </div>

        {{-- Notifikasi --}}
        @if (session('success'))
            <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                {{ session('success') }}
            </div>
        @endif

        {{-- Daftar Produk --}}
        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">

            {{-- Header, Search, dan Tombol --}}
            <div class="border-b border-gray-200 px-6 py-5 sm:px-7">

                <div>
                    <h2 class="text-lg font-semibold text-gray-800">
                        Stok Produk
                    </h2>
                    <p class="mt-1 text-sm text-gray-500">
                        Pantau jumlah stok setiap produk yang tersedia.
                    </p>
                </div>

                <div class="mt-5 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

                    {{-- Search --}}
                    <form id="searchStokForm" action="{{ route('admin.stok.index') }}" method="GET" class="min-w-0 flex-1">

                        <div class="relative w-full sm:max-w-sm">
                            <input type="text" id="searchStok" name="search" value="{{ request('search') }}"
                                placeholder="Cari nama produk..." autocomplete="off"
                                class="w-full rounded-lg border border-gray-300 px-4 py-2.5 pr-10 text-sm outline-none transition focus:border-amber-500 focus:ring-2 focus:ring-amber-100">

                            <button type="button" id="clearSearchStok" aria-label="Hapus pencarian"
                                class="{{ request('search') ? '' : 'hidden' }} absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 transition hover:text-gray-700">
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                        </div>
                    </form>

                    {{-- Tombol Perubahan Stok --}}
                    <a href="{{ route('admin.stok.create') }}"
                        class="inline-flex shrink-0 items-center justify-center gap-2 rounded-lg bg-amber-500 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-amber-600">
                        <i class="fa-solid fa-plus"></i>
                        Perubahan Stok
                    </a>

                </div>
            </div>

            {{-- Tabel Produk --}}
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-gray-50 text-xs uppercase text-gray-500">
                        <tr>
                            <th class="px-6 py-4">No.</th>
                            <th class="px-6 py-4">Nama Produk</th>
                            <th class="px-6 py-4">Kategori</th>
                            <th class="px-6 py-4">Stok Terkini</th>
                            <th class="px-6 py-4">Status</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100">
                        @forelse ($produk as $item)
                            <tr class="transition hover:bg-gray-50">
                                <td class="px-6 py-4 text-gray-500">
                                    {{ $produk->firstItem() + $loop->index }}
                                </td>

                                <td class="px-6 py-4 font-medium text-gray-800">
                                    {{ $item->nama_produk }}
                                </td>

                                <td class="px-6 py-4 text-gray-600">
                                    {{ $item->kategori->nama_kategori ?? '-' }}
                                </td>

                                <td class="px-6 py-4 font-semibold text-gray-800">
                                    {{ number_format($item->stok, 0, ',', '.') }}
                                </td>

                                <td class="px-6 py-4">
                                    @if ($item->stok == 0)
                                        <span
                                            class="inline-flex rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-700">
                                            Habis
                                        </span>
                                    @elseif ($item->stok <= 5)
                                        <span
                                            class="inline-flex rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-700">
                                            Stok Menipis
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700">
                                            Tersedia
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-10 text-center text-gray-500">
                                    Produk tidak ditemukan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination Produk --}}
            <div class="border-t border-gray-200 px-6 py-4 sm:px-7">
                {{ $produk->links() }}
            </div>
        </div>

        {{-- Riwayat Stok --}}
        <div id="riwayat-stok" class="scroll-mt-6 overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">

            {{-- Header dan Filter --}}
            <div class="border-b border-gray-200 px-5 py-4 sm:px-6">
                <div class="flex flex-col gap-4 xl:flex-row xl:items-center xl:justify-between">

                    <div>
                        <h2 class="text-lg font-semibold text-gray-800">
                            Riwayat Pergerakan Stok
                        </h2>
                        <p class="mt-1 text-sm text-gray-500">
                            Catatan stok awal dan perubahan stok produk.
                        </p>
                    </div>

                    {{-- Filter Riwayat --}}
                    <form action="{{ route('admin.stok.index') }}#riwayat-stok" method="GET"
                        class="flex flex-col gap-2 sm:flex-row sm:flex-wrap sm:items-center">

                        @if (request('search'))
                            <input type="hidden" name="search" value="{{ request('search') }}">
                        @endif

                        {{-- Filter Bulan --}}
                        <select name="bulan"
                            class="rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm outline-none focus:border-amber-500 focus:ring-2 focus:ring-amber-100">

                            <option value="">Semua Bulan</option>

                            @foreach ([
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
        ] as $nomor => $namaBulan)
                                <option value="{{ $nomor }}" @selected((string) request('bulan') === (string) $nomor)>
                                    {{ $namaBulan }}
                                </option>
                            @endforeach
                        </select>

                        {{-- Filter Tahun --}}
                        <select name="tahun"
                            class="rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm outline-none focus:border-amber-500 focus:ring-2 focus:ring-amber-100">

                            <option value="">Semua Tahun</option>

                            ```blade
                            @foreach (range(2026, max(2026, (int) date('Y'))) as $tahun)
                                <option value="{{ $tahun }}" @selected((string) request('tahun') === (string) $tahun)>
                                    {{ $tahun }}
                                </option>
                            @endforeach
                            ```
                        </select>

                        {{-- Tombol Terapkan --}}
                        <button type="submit"
                            class="rounded-lg bg-amber-500 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-amber-600">
                            Terapkan
                        </button>

                        {{-- Tombol Reset --}}
                        <a href="{{ route('admin.stok.index', request('search') ? ['search' => request('search')] : []) }}#riwayat-stok"
                            class="rounded-lg border border-gray-300 px-4 py-2.5 text-center text-sm font-medium text-gray-600 transition hover:bg-gray-50">
                            Reset
                        </a>

                        {{-- Tombol Unduh PDF --}}
                        @if (request()->filled('bulan') && request()->filled('tahun'))
                            <a href="{{ route('admin.stok.export-pdf', [
                                'bulan' => request('bulan'),
                                'tahun' => request('tahun'),
                            ]) }}"
                                class="rounded-lg border border-gray-300 px-4 py-2.5 text-center text-sm font-semibold text-gray-700 transition hover:bg-gray-50">
                                Unduh PDF
                            </a>
                        @endif
                    </form>
                </div>
            </div>

            {{-- Tabel Riwayat --}}
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-gray-50 text-xs uppercase text-gray-500">
                        <tr>
                            <th class="whitespace-nowrap px-5 py-3">No.</th>
                            <th class="whitespace-nowrap px-5 py-3">Tanggal</th>
                            <th class="px-5 py-3">Produk</th>
                            <th class="px-5 py-3">Jenis</th>
                            <th class="whitespace-nowrap px-5 py-3">Jumlah</th>
                            <th class="whitespace-nowrap px-5 py-3">Stok Sebelum</th>
                            <th class="whitespace-nowrap px-5 py-3">Stok Sesudah</th>
                            <th class="px-5 py-3">Keterangan</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100">
                        @forelse ($riwayatStok as $riwayat)
                            <tr class="transition hover:bg-gray-50">

                                <td class="whitespace-nowrap px-5 py-3 text-gray-500">
                                    {{ $riwayatStok->firstItem() + $loop->index }}
                                </td>

                                <td class="whitespace-nowrap px-5 py-3 text-gray-600">
                                    {{ $riwayat->created_at->format('d/m/Y H:i') }}
                                </td>

                                <td class="px-5 py-3 font-medium text-gray-800">
                                    {{ $riwayat->produk->nama_produk ?? 'Produk tidak tersedia' }}
                                </td>

                                <td class="whitespace-nowrap px-5 py-3">
                                    @if ($riwayat->jenis === 'masuk')
                                        <span
                                            class="inline-flex rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700">
                                            Masuk
                                        </span>
                                    @elseif ($riwayat->jenis === 'keluar')
                                        <span
                                            class="inline-flex rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-700">
                                            Keluar
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-700">
                                            Penyesuaian
                                        </span>
                                    @endif
                                </td>

                                <td class="whitespace-nowrap px-5 py-3 font-semibold text-gray-800">
                                    {{ number_format($riwayat->jumlah, 0, ',', '.') }}
                                </td>

                                <td class="whitespace-nowrap px-5 py-3 text-gray-600">
                                    {{ number_format($riwayat->stok_sebelum, 0, ',', '.') }}
                                </td>

                                <td class="whitespace-nowrap px-5 py-3 font-semibold text-gray-800">
                                    {{ number_format($riwayat->stok_sesudah, 0, ',', '.') }}
                                </td>

                                <td class="min-w-40 px-5 py-3 text-gray-600">
                                    {{ $riwayat->keterangan ?? '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-5 py-10 text-center text-gray-500">
                                    Tidak ada riwayat stok pada periode ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination Riwayat --}}
            <div class="border-t border-gray-200 px-5 py-4 sm:px-6">
                {{ $riwayatStok->fragment('riwayat-stok')->links() }}
            </div>
        </div>
    </div>

    {{-- Script Pencarian Produk --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('searchStokForm');
            const input = document.getElementById('searchStok');
            const clearButton = document.getElementById('clearSearchStok');

            if (!form || !input || !clearButton) return;

            let timeout = null;

            input.addEventListener('input', function() {
                clearTimeout(timeout);

                timeout = setTimeout(function() {
                    form.requestSubmit();
                }, 200);
            });

            clearButton.addEventListener('click', function() {
                clearTimeout(timeout);
                input.value = '';
                form.requestSubmit();
            });
        });
    </script>
@endsection
