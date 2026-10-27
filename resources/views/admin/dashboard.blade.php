@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@section('page-heading', 'Dashboard')

@section('content')
    @php
        $statusPesanan = [
            'menunggu_konfirmasi' => [
                'label' => 'Menunggu Konfirmasi',
                'class' => 'bg-amber-100 text-amber-700',
            ],
            'diproses' => [
                'label' => 'Diproses',
                'class' => 'bg-blue-100 text-blue-700',
            ],
            'dikirim' => [
                'label' => 'Dikirim',
                'class' => 'bg-indigo-100 text-indigo-700',
            ],
            'siap_diambil' => [
                'label' => 'Siap Diambil',
                'class' => 'bg-purple-100 text-purple-700',
            ],
            'menunggu_konfirmasi_pelanggan' => [
                'label' => 'Konfirmasi Penerimaan',
                'class' => 'bg-orange-100 text-orange-700',
            ],
            'selesai' => [
                'label' => 'Selesai',
                'class' => 'bg-green-100 text-green-700',
            ],
            'dibatalkan' => [
                'label' => 'Dibatalkan',
                'class' => 'bg-red-100 text-red-700',
            ],
        ];

        $statusPembayaran = [
            'menunggu_verifikasi' => [
                'label' => 'Menunggu Verifikasi',
                'class' => 'bg-amber-100 text-amber-700',
            ],
            'belum_bayar' => [
                'label' => 'Belum Dibayar',
                'class' => 'bg-red-100 text-red-700',
            ],
            'lunas' => [
                'label' => 'Lunas',
                'class' => 'bg-green-100 text-green-700',
            ],
        ];
    @endphp

    <div class="space-y-6">

        {{-- Sambutan --}}
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">
                    Selamat Datang, Admin!
                </h2>

                <p class="mt-1 text-sm text-gray-500">
                    Berikut ringkasan aktivitas toko oleh-oleh khas Minang.
                </p>
            </div>

            <div class="rounded-xl border border-amber-200 bg-amber-50 px-4 py-3">
                <p class="text-xs font-medium text-amber-700">
                    Pendapatan Hari Ini
                </p>
                <p class="mt-1 text-lg font-bold text-[#30251F]">
                    Rp{{ number_format((float) $pendapatanHariIni, 0, ',', '.') }}
                </p>
            </div>
        </div>

        {{-- Statistik Utama --}}
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 xl:grid-cols-4">

            {{-- Total Produk --}}
            <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <p class="text-sm font-medium text-gray-500">Total Produk</p>
                    <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-blue-100 text-blue-600">
                        <i class="fa-solid fa-box text-lg"></i>
                    </span>
                </div>

                <h3 class="mt-4 text-3xl font-bold text-gray-800">
                    {{ number_format($totalProduk) }}
                </h3>

                <p class="mt-2 text-xs text-gray-400">Produk terdaftar</p>

                <a href="{{ url('/admin/produk') }}"
                    class="mt-4 inline-flex items-center gap-2 text-sm font-semibold text-blue-700 hover:text-blue-800">
                    Kelola Produk
                    <i class="fa-solid fa-arrow-right text-xs"></i>
                </a>
            </div>

            {{-- Total Pesanan --}}
            <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <p class="text-sm font-medium text-gray-500">Total Pesanan</p>
                    <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-amber-100 text-amber-600">
                        <i class="fa-solid fa-cart-shopping text-lg"></i>
                    </span>
                </div>

                <h3 class="mt-4 text-3xl font-bold text-gray-800">
                    {{ number_format($totalPesanan) }}
                </h3>

                <p class="mt-2 text-xs text-gray-400">Seluruh pesanan pelanggan</p>

                <a href="{{ url('/admin/pesanan') }}"
                    class="mt-4 inline-flex items-center gap-2 text-sm font-semibold text-amber-700 hover:text-amber-800">
                    Lihat Pesanan
                    <i class="fa-solid fa-arrow-right text-xs"></i>
                </a>
            </div>

            {{-- Menunggu Verifikasi --}}
            <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <p class="text-sm font-medium text-gray-500">Menunggu Verifikasi</p>
                    <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-orange-100 text-orange-600">
                        <i class="fa-solid fa-clock text-lg"></i>
                    </span>
                </div>

                <h3 class="mt-4 text-3xl font-bold text-gray-800">
                    {{ number_format($menungguVerifikasi) }}
                </h3>

                <p class="mt-2 text-xs text-gray-400">Pembayaran QRIS perlu diperiksa</p>

                <a href="{{ url('/admin/pembayaran') }}"
                    class="mt-4 inline-flex items-center gap-2 text-sm font-semibold text-orange-700 hover:text-orange-800">
                    Periksa Pembayaran
                    <i class="fa-solid fa-arrow-right text-xs"></i>
                </a>
            </div>

            {{-- Total Pendapatan --}}
            <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <p class="text-sm font-medium text-gray-500">Total Pendapatan</p>
                    <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-green-100 text-green-600">
                        <i class="fa-solid fa-wallet text-lg"></i>
                    </span>
                </div>

                <h3 class="mt-4 break-words text-2xl font-bold text-gray-800">
                    Rp{{ number_format((float) $totalPendapatan, 0, ',', '.') }}
                </h3>

                <p class="mt-2 text-xs text-gray-400">
                    Dari pesanan dengan pembayaran lunas
                </p>

                <a href="{{ url('/admin/pembayaran') }}"
                    class="mt-4 inline-flex items-center gap-2 text-sm font-semibold text-green-700 hover:text-green-800">
                    Lihat Transaksi
                    <i class="fa-solid fa-arrow-right text-xs"></i>
                </a>
            </div>

        </div>

        {{-- Ringkasan Status --}}
        <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
            <div>
                <h3 class="text-lg font-bold text-gray-800">
                    Ringkasan Pesanan
                </h3>
                <p class="mt-1 text-sm text-gray-500">
                    Jumlah pesanan berdasarkan status saat ini.
                </p>
            </div>

            <div class="mt-5 grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
                @foreach ($statusPesanan as $key => $status)
                    <div class="rounded-xl border border-gray-100 bg-gray-50 p-4">
                        <div class="flex items-center justify-between gap-3">
                            <p class="text-sm text-gray-600">
                                {{ $status['label'] }}
                            </p>

                            <span
                                class="inline-flex h-8 min-w-8 items-center justify-center rounded-lg px-2 text-sm font-bold {{ $status['class'] }}">
                                {{ $ringkasanStatus->get($key, 0) }}
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div class="flex items-center justify-between rounded-xl border border-amber-100 bg-amber-50 p-4">
                    <div>
                        <p class="text-sm font-medium text-amber-800">Menunggu Verifikasi</p>
                        <p class="mt-1 text-xs text-amber-700">Pembayaran QRIS</p>
                    </div>

                    <span class="text-2xl font-bold text-amber-800">
                        {{ $menungguVerifikasi }}
                    </span>
                </div>

                <div class="flex items-center justify-between rounded-xl border border-red-100 bg-red-50 p-4">
                    <div>
                        <p class="text-sm font-medium text-red-800">Belum Dibayar</p>
                        <p class="mt-1 text-xs text-red-700">Pembayaran belum lunas</p>
                    </div>

                    <span class="text-2xl font-bold text-red-800">
                        {{ $totalBelumBayar }}
                    </span>
                </div>
            </div>
        </div>

        {{-- Pesanan Terbaru --}}
        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
            <div
                class="flex flex-col gap-3 border-b border-gray-100 px-5 py-5 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h3 class="text-lg font-bold text-gray-800">
                        Pesanan Terbaru
                    </h3>
                    <p class="mt-1 text-sm text-gray-500">
                        Lima pesanan terakhir yang masuk ke toko.
                    </p>
                </div>

                <a href="{{ url('/admin/pesanan') }}"
                    class="inline-flex items-center gap-2 text-sm font-semibold text-amber-700 hover:text-amber-800">
                    Lihat Semua
                    <i class="fa-solid fa-arrow-right text-xs"></i>
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full min-w-[850px] text-left text-sm">
                    <thead class="bg-gray-50 text-xs uppercase tracking-wide text-gray-500">
                        <tr>
                            <th class="px-5 py-4 font-semibold">Pesanan</th>
                            <th class="px-5 py-4 font-semibold">Pelanggan</th>
                            <th class="px-5 py-4 text-right font-semibold">Total</th>
                            <th class="px-5 py-4 font-semibold">Status Pesanan</th>
                            <th class="px-5 py-4 font-semibold">Pembayaran</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100">
                        @forelse ($pesananTerbaru as $pesanan)
                            @php
                                $infoStatus = $statusPesanan[$pesanan->status] ?? [
                                    'label' => ucfirst(str_replace('_', ' ', $pesanan->status ?? '-')),
                                    'class' => 'bg-gray-100 text-gray-700',
                                ];

                                $infoPembayaran = $statusPembayaran[$pesanan->status_pembayaran] ?? [
                                    'label' => ucfirst(str_replace('_', ' ', $pesanan->status_pembayaran ?? '-')),
                                    'class' => 'bg-gray-100 text-gray-700',
                                ];
                            @endphp

                            <tr class="transition hover:bg-gray-50">
                                <td class="px-5 py-4">
                                    <a href="{{ url('/admin/pesanan/' . $pesanan->id) }}"
                                        class="font-semibold text-amber-700 hover:text-amber-800">
                                        {{ $pesanan->kode_pesanan }}
                                    </a>

                                    <p class="mt-1 text-xs text-gray-400">
                                        {{ $pesanan->created_at?->format('d M Y, H:i') ?? '-' }}
                                    </p>
                                </td>

                                <td class="px-5 py-4">
                                    <p class="font-medium text-gray-800">
                                        {{ $pesanan->nama_penerima ?: $pesanan->pelanggan->name ?? '-' }}
                                    </p>

                                    <p class="mt-1 text-xs text-gray-400">
                                        {{ $pesanan->no_whatsapp ?: '-' }}
                                    </p>
                                </td>

                                <td class="whitespace-nowrap px-5 py-4 text-right font-semibold text-gray-800">
                                    Rp{{ number_format((float) $pesanan->total, 0, ',', '.') }}
                                </td>

                                <td class="px-5 py-4">
                                    <span
                                        class="inline-flex whitespace-nowrap rounded-full px-3 py-1 text-xs font-semibold {{ $infoStatus['class'] }}">
                                        {{ $infoStatus['label'] }}
                                    </span>
                                </td>

                                <td class="px-5 py-4">
                                    <span
                                        class="inline-flex whitespace-nowrap rounded-full px-3 py-1 text-xs font-semibold {{ $infoPembayaran['class'] }}">
                                        {{ $infoPembayaran['label'] }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-5 py-12 text-center">
                                    <i class="fa-solid fa-receipt mb-3 text-3xl text-gray-300"></i>
                                    <p class="font-medium text-gray-600">
                                        Belum ada pesanan
                                    </p>
                                    <p class="mt-1 text-sm text-gray-400">
                                        Pesanan pelanggan akan muncul di sini.
                                    </p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Stok Menipis --}}
        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
            <div
                class="flex flex-col gap-3 border-b border-gray-100 px-5 py-5 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h3 class="text-lg font-bold text-gray-800">
                        Perhatian Stok
                    </h3>
                    <p class="mt-1 text-sm text-gray-500">
                        Produk dengan stok 5 atau kurang.
                    </p>
                </div>

                <a href="{{ url('/admin/stok') }}" class="text-sm font-semibold text-amber-700 hover:text-amber-800">
                    Kelola Stok
                    <i class="fa-solid fa-arrow-right ml-1 text-xs"></i>
                </a>
            </div>

            @if ($produkStokMenipis->isNotEmpty())
                <div class="divide-y divide-gray-100">
                    @foreach ($produkStokMenipis as $produk)
                        <div class="flex items-center justify-between gap-4 px-5 py-4">
                            <div class="flex min-w-0 items-center gap-3">
                                <span
                                    class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-orange-50 text-orange-600">
                                    <i class="fa-solid fa-box-open"></i>
                                </span>

                                <div class="min-w-0">
                                    <p class="truncate font-semibold text-gray-800">
                                        {{ $produk->nama_produk }}
                                    </p>
                                    <p class="mt-1 text-xs text-gray-400">
                                        Stok produk
                                    </p>
                                </div>
                            </div>

                            <span
                                class="shrink-0 rounded-full px-3 py-1 text-xs font-bold {{ $produk->stok <= 0 ? 'bg-red-100 text-red-700' : 'bg-amber-100 text-amber-700' }}">
                                {{ $produk->stok <= 0 ? 'Habis' : $produk->stok . ' tersisa' }}
                            </span>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="px-5 py-8 text-center">
                    <i class="fa-solid fa-circle-check mb-2 text-2xl text-green-500"></i>
                    <p class="text-sm text-gray-600">
                        Tidak ada produk dengan stok menipis.
                    </p>
                </div>
            @endif

            @if ($totalStokMenipis > 5)
                <div class="border-t border-gray-100 px-5 py-3 text-center text-xs text-gray-500">
                    Menampilkan 5 dari {{ $totalStokMenipis }} produk dengan stok menipis.
                </div>
            @endif
        </div>

        {{-- Menu Cepat --}}
        <div>
            <h3 class="text-lg font-bold text-gray-800">
                Menu Cepat
            </h3>
            <p class="mt-1 text-sm text-gray-500">
                Akses fitur pengelolaan toko.
            </p>

            <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
                <a href="{{ url('/admin/produk') }}"
                    class="group flex items-center gap-4 rounded-xl border border-gray-200 bg-white p-5 shadow-sm transition hover:border-amber-300 hover:shadow-md">
                    <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-amber-50 text-amber-600">
                        <i class="fa-solid fa-box-open text-lg"></i>
                    </span>
                    <div class="min-w-0 flex-1">
                        <p class="font-semibold text-gray-800">Kelola Produk</p>
                        <p class="mt-1 text-xs text-gray-500">Data dan stok produk</p>
                    </div>
                    <i class="fa-solid fa-chevron-right text-xs text-gray-400 transition group-hover:translate-x-1"></i>
                </a>

                <a href="{{ url('/admin/kategori') }}"
                    class="group flex items-center gap-4 rounded-xl border border-gray-200 bg-white p-5 shadow-sm transition hover:border-amber-300 hover:shadow-md">
                    <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                        <i class="fa-solid fa-tags text-lg"></i>
                    </span>
                    <div class="min-w-0 flex-1">
                        <p class="font-semibold text-gray-800">Kelola Kategori</p>
                        <p class="mt-1 text-xs text-gray-500">Kategori produk toko</p>
                    </div>
                    <i class="fa-solid fa-chevron-right text-xs text-gray-400 transition group-hover:translate-x-1"></i>
                </a>

                <a href="{{ url('/admin/pesanan') }}"
                    class="group flex items-center gap-4 rounded-xl border border-gray-200 bg-white p-5 shadow-sm transition hover:border-amber-300 hover:shadow-md">
                    <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-green-50 text-green-600">
                        <i class="fa-solid fa-receipt text-lg"></i>
                    </span>
                    <div class="min-w-0 flex-1">
                        <p class="font-semibold text-gray-800">Kelola Pesanan</p>
                        <p class="mt-1 text-xs text-gray-500">Proses pesanan pelanggan</p>
                    </div>
                    <i class="fa-solid fa-chevron-right text-xs text-gray-400 transition group-hover:translate-x-1"></i>
                </a>

                <a href="{{ url('/admin/laporan') }}"
                    class="group flex items-center gap-4 rounded-xl border border-gray-200 bg-white p-5 shadow-sm transition hover:border-amber-300 hover:shadow-md">
                    <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-purple-50 text-purple-600">
                        <i class="fa-solid fa-chart-column text-lg"></i>
                    </span>
                    <div class="min-w-0 flex-1">
                        <p class="font-semibold text-gray-800">Lihat Laporan</p>
                        <p class="mt-1 text-xs text-gray-500">Ringkasan penjualan</p>
                    </div>
                    <i class="fa-solid fa-chevron-right text-xs text-gray-400 transition group-hover:translate-x-1"></i>
                </a>
            </div>
        </div>

    </div>
@endsection
