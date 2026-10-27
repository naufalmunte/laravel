@extends('layouts.main')

@section('title', 'Riwayat Pesanan')

@section('content')
    <section class="min-h-screen px-4 py-10 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-6xl">

            {{-- Header --}}
            <div class="mb-8">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                    <div>
                        <p class="mb-2 text-sm font-semibold uppercase tracking-[0.2em] text-amber-700">
                            Pesanan Saya
                        </p>

                        <h1 class="text-3xl font-bold text-[#30251F] sm:text-4xl">
                            Riwayat Pesanan
                        </h1>

                        <p class="mt-2 text-sm text-gray-500">
                            Pantau status dan lihat detail pesananmu di sini.
                        </p>
                    </div>

                    <a href="{{ route('produk.index') }}"
                        class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#30251F] px-5 py-3 text-sm font-semibold text-white transition hover:bg-[#49382F]">
                        <i class="fa-solid fa-bag-shopping"></i>
                        Belanja Lagi
                    </a>
                </div>
            </div>

            {{-- Notifikasi --}}
            @if (session('success'))
                <div
                    class="mb-6 flex items-start gap-3 rounded-xl border border-green-200 bg-green-50 p-4 text-sm text-green-800">
                    <i class="fa-solid fa-circle-check mt-0.5"></i>
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            @if (session('error'))
                <div
                    class="mb-6 flex items-start gap-3 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-800">
                    <i class="fa-solid fa-circle-exclamation mt-0.5"></i>
                    <p>{{ session('error') }}</p>
                </div>
            @endif

            {{-- Daftar Pesanan --}}
            @forelse ($pesanans as $pesanan)
                @php
                    $statusPesanan = [
                        'menunggu_konfirmasi' => [
                            'label' => 'Menunggu Konfirmasi',
                            'class' => 'bg-amber-100 text-amber-800',
                            'icon' => 'fa-clock',
                        ],
                        'diproses' => [
                            'label' => 'Sedang Diproses',
                            'class' => 'bg-blue-100 text-blue-800',
                            'icon' => 'fa-box',
                        ],
                        'dikirim' => [
                            'label' => 'Sedang Dikirim',
                            'class' => 'bg-indigo-100 text-indigo-800',
                            'icon' => 'fa-truck',
                        ],
                        'siap_diambil' => [
                            'label' => 'Siap Diambil',
                            'class' => 'bg-purple-100 text-purple-800',
                            'icon' => 'fa-store',
                        ],
                        'menunggu_konfirmasi_pelanggan' => [
                            'label' => 'Konfirmasi Penerimaan',
                            'class' => 'bg-orange-100 text-orange-800',
                            'icon' => 'fa-box-open',
                        ],
                        'selesai' => [
                            'label' => 'Selesai',
                            'class' => 'bg-green-100 text-green-800',
                            'icon' => 'fa-circle-check',
                        ],
                        'dibatalkan' => [
                            'label' => 'Dibatalkan',
                            'class' => 'bg-red-100 text-red-800',
                            'icon' => 'fa-circle-xmark',
                        ],
                    ];

                    $status = $statusPesanan[$pesanan->status] ?? [
                        'label' => ucfirst(str_replace('_', ' ', $pesanan->status)),
                        'class' => 'bg-gray-100 text-gray-700',
                        'icon' => 'fa-circle-info',
                    ];

                    $metodePembayaran = [
                        'transfer' => 'Transfer Bank',
                        'qris' => 'QRIS',
                        'tunai' => 'Tunai',
                    ];

                    $namaMetodePembayaran =
                        $metodePembayaran[$pesanan->metode_pembayaran] ??
                        ucfirst(str_replace('_', ' ', $pesanan->metode_pembayaran));
                @endphp

                <article class="mb-4 overflow-hidden rounded-xl border border-[#E9E1D8] bg-white shadow-sm">

                    <div class="flex flex-col gap-4 p-4 sm:flex-row sm:items-center sm:justify-between sm:p-5">

                        {{-- Informasi Pesanan --}}
                        <div class="min-w-0 flex-1">
                            <div class="mb-2 flex flex-wrap items-center gap-2">
                                <span class="text-sm font-bold text-[#30251F]">
                                    {{ $pesanan->kode_pesanan }}
                                </span>

                                <span
                                    class="inline-flex shrink-0 items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-semibold leading-4 {{ $status['class'] }}">
                                    <i class="fa-solid {{ $status['icon'] }}"></i>
                                    {{ $status['label'] }}
                                </span>
                            </div>

                            <p class="text-xs text-gray-500">
                                Dipesan pada
                                {{ $pesanan->created_at->format('d M Y, H:i') }}
                            </p>

                            <div class="mt-3 flex flex-wrap items-center gap-x-5 gap-y-2">
                                <div>
                                    <p class="text-xs text-gray-500">
                                        Total Pembayaran
                                    </p>
                                    <p class="mt-0.5 text-base font-bold text-[#30251F]">
                                        Rp{{ number_format((float) $pesanan->total, 0, ',', '.') }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        {{-- Tombol Detail --}}
                        <div class="shrink-0 border-t border-gray-100 pt-3 sm:border-l sm:border-t-0 sm:pl-5 sm:pt-0">
                            <a href="{{ route('pelanggan.pesanan.show', $pesanan->kode_pesanan) }}"
                                class="inline-flex w-full items-center justify-center gap-2 rounded-lg border border-[#30251F] px-4 py-2.5 text-sm font-semibold text-[#30251F] transition hover:bg-[#30251F] hover:text-white sm:w-auto">
                                Lihat Detail
                                <i class="fa-solid fa-arrow-right text-xs"></i>
                            </a>
                        </div>

                    </div>
                </article>

            @empty
                {{-- Belum Ada Pesanan --}}
                <div class="rounded-2xl border border-dashed border-[#D9CBBE] bg-white px-6 py-16 text-center">
                    <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-full bg-[#F5EEE6]">
                        <i class="fa-solid fa-bag-shopping text-3xl text-[#80634F]"></i>
                    </div>

                    <h2 class="mt-5 text-xl font-bold text-[#30251F]">
                        Belum Ada Pesanan
                    </h2>

                    <p class="mx-auto mt-2 max-w-md text-sm leading-6 text-gray-500">
                        Kamu belum memiliki riwayat pesanan. Yuk, temukan aneka oleh-oleh khas Minang favoritmu!
                    </p>

                    <a href="{{ route('produk.index') }}"
                        class="mt-6 inline-flex items-center justify-center gap-2 rounded-xl bg-[#30251F] px-6 py-3 text-sm font-semibold text-white transition hover:bg-[#49382F]">
                        <i class="fa-solid fa-bag-shopping"></i>
                        Mulai Belanja
                    </a>
                </div>
            @endforelse

            {{-- Pagination --}}
            @if ($pesanans->hasPages())
                <div class="mt-8">
                    {{ $pesanans->links() }}
                </div>
            @endif

        </div>
    </section>
@endsection
