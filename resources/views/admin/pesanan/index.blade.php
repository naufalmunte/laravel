@extends('layouts.admin')

@section('title', 'Manajemen Pesanan')

@section('content')
    <div class="space-y-4">

        {{-- Header Halaman --}}
        <div>
            <h1 class="text-xl font-bold text-gray-800">Manajemen Pesanan</h1>
            <p class="mt-1 text-xs text-gray-500">
                Kelola dan pantau seluruh pesanan pelanggan Gerai Ikan Bilih Mario.
            </p>
        </div>

        {{-- Ringkasan --}}
        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 xl:grid-cols-3">

            {{-- Total Pesanan --}}
            <div class="rounded-lg border border-gray-200 bg-white p-3 shadow-sm">
                <div class="flex items-center justify-between gap-2">
                    <div>
                        <p class="text-xs text-gray-500">Total Pesanan</p>
                        <p class="mt-1.5 text-xl font-bold text-gray-800">
                            {{ $pesanans->total() }}
                        </p>
                    </div>
                    <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-amber-100 text-amber-700">
                        <i class="fa-solid fa-receipt text-base"></i>
                    </div>
                </div>
            </div>

            {{-- Menunggu Konfirmasi --}}
            <div class="rounded-lg border border-gray-200 bg-white p-3 shadow-sm">
                <div class="flex items-center justify-between gap-2">
                    <div>
                        <p class="text-xs text-gray-500">Menunggu Konfirmasi</p>
                        <p class="mt-1.5 text-xl font-bold text-amber-600">
                            {{ \App\Models\Pesanan::where('status', 'menunggu_konfirmasi')->count() }}
                        </p>
                    </div>
                    <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-amber-100 text-amber-700">
                        <i class="fa-solid fa-clock text-base"></i>
                    </div>
                </div>
            </div>

            {{-- Menunggu Verifikasi --}}
            <div class="rounded-lg border border-gray-200 bg-white p-3 shadow-sm">
                <div class="flex items-center justify-between gap-2">
                    <div>
                        <p class="text-xs text-gray-500">Menunggu Verifikasi Pembayaran</p>
                        <p class="mt-1.5 text-xl font-bold text-blue-600">
                            {{ \App\Models\Pesanan::where('status_pembayaran', 'menunggu_verifikasi')->count() }}
                        </p>
                    </div>
                    <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-blue-100 text-blue-700">
                        <i class="fa-solid fa-money-check-dollar text-base"></i>
                    </div>
                </div>
            </div>

        </div>

        {{-- Tabel Pesanan --}}
        <div class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm">

            {{-- Header dan Live Search --}}
            <div
                class="flex flex-col gap-3 border-b border-gray-200 px-3 py-3 sm:flex-row sm:items-center sm:justify-between">

                <div>
                    <h2 class="text-sm font-semibold text-gray-800">Daftar Pesanan</h2>
                    <p class="mt-1 text-[10px] text-gray-500">
                        Cari berdasarkan nama pelanggan atau kode pesanan.
                    </p>
                </div>

                <form action="{{ route('admin.pesanan.index') }}" method="GET" class="relative w-full sm:max-w-xs">

                    <i
                        class="fa-solid fa-magnifying-glass absolute left-2.5 top-1/2 -translate-y-1/2 text-xs text-gray-400"></i>

                    <input type="text" id="cari-pesanan" name="cari" value="{{ $keyword }}"
                        placeholder="Cari nama atau kode pesanan..." autocomplete="off"
                        class="w-full rounded-md border border-gray-300 py-2 pl-8 pr-8 text-xs outline-none transition focus:border-amber-500 focus:ring-1 focus:ring-amber-100">

                    <span id="loading-cari"
                        class="absolute right-2.5 top-1/2 hidden -translate-y-1/2 text-xs text-gray-400">
                        <i class="fa-solid fa-spinner fa-spin"></i>
                    </span>
                </form>

            </div>

            {{-- Tabel Compact --}}
            <div class="w-full">
                <table class="w-full table-fixed text-left text-[10px] leading-tight">

                    <thead class="bg-gray-50 text-[9px] uppercase tracking-wide text-gray-500">
                        <tr>
                            <th class="w-[15%] px-1.5 py-2 font-semibold">Kode Pesanan</th>
                            <th class="w-[19%] px-1.5 py-2 font-semibold">Pelanggan</th>
                            <th class="w-[12%] px-1.5 py-2 font-semibold">Tanggal</th>
                            <th class="w-[13%] px-1.5 py-2 font-semibold">Total</th>
                            <th class="w-[15%] px-1.5 py-2 font-semibold">Pembayaran</th>
                            <th class="w-[18%] px-1.5 py-2 font-semibold">Status Pesanan</th>
                            <th class="w-[8%] px-1 py-2 text-center font-semibold">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100">

                        @forelse ($pesanans as $pesanan)
                            <tr class="transition hover:bg-gray-50">

                                {{-- Kode Pesanan --}}
                                <td class="break-words px-1.5 py-2 font-semibold text-gray-800">
                                    {{ $pesanan->kode_pesanan }}
                                </td>

                                {{-- Pelanggan --}}
                                <td class="break-words px-1.5 py-2">
                                    <p class="font-medium text-gray-800">
                                        {{ $pesanan->nama_penerima }}
                                    </p>
                                    <p class="mt-1 break-all text-[9px] text-gray-500">
                                        {{ $pesanan->no_whatsapp }}
                                    </p>
                                </td>

                                {{-- Tanggal --}}
                                <td class="px-1.5 py-2 text-gray-600">
                                    <p>{{ $pesanan->created_at->format('d/m/Y') }}</p>
                                    <p class="mt-1 text-[9px] text-gray-400">
                                        {{ $pesanan->created_at->format('H:i') }}
                                    </p>
                                </td>

                                {{-- Total --}}
                                <td class="break-words px-1.5 py-2 font-semibold text-gray-800">
                                    Rp{{ number_format($pesanan->total, 0, ',', '.') }}
                                </td>

                                {{-- Pembayaran --}}
                                <td class="px-1.5 py-2">
                                    <p class="break-words font-medium text-gray-700">
                                        {{ strtoupper($pesanan->metode_pembayaran) }}
                                    </p>

                                    @if ($pesanan->status_pembayaran === 'menunggu_verifikasi')
                                        <span
                                            class="mt-1 inline-block max-w-full break-words rounded-full bg-blue-100 px-1.5 py-0.5 text-[9px] font-medium text-blue-700">
                                            Menunggu Verifikasi
                                        </span>
                                    @elseif ($pesanan->status_pembayaran === 'belum_bayar')
                                        <span
                                            class="mt-1 inline-block max-w-full break-words rounded-full bg-amber-100 px-1.5 py-0.5 text-[9px] font-medium text-amber-700">
                                            Belum Bayar
                                        </span>
                                    @elseif ($pesanan->status_pembayaran === 'lunas')
                                        <span
                                            class="mt-1 inline-block rounded-full bg-green-100 px-1.5 py-0.5 text-[9px] font-medium text-green-700">
                                            Lunas
                                        </span>
                                    @else
                                        <span
                                            class="mt-1 inline-block max-w-full break-words rounded-full bg-gray-100 px-1.5 py-0.5 text-[9px] font-medium text-gray-600">
                                            {{ str_replace('_', ' ', ucfirst($pesanan->status_pembayaran)) }}
                                        </span>
                                    @endif
                                </td>

                                {{-- Status Pesanan --}}
                                <td class="px-1.5 py-2">
                                    @php
                                        $statusPesanan = [
                                            'menunggu_konfirmasi' => [
                                                'Menunggu Konfirmasi',
                                                'bg-amber-100 text-amber-700',
                                            ],
                                            'diproses' => ['Diproses', 'bg-blue-100 text-blue-700'],
                                            'dikirim' => ['Dikirim', 'bg-indigo-100 text-indigo-700'],
                                            'siap_diambil' => ['Siap Diambil', 'bg-purple-100 text-purple-700'],
                                            'menunggu_konfirmasi_pelanggan' => [
                                                'Pesanan Diserahkan ke Pelanggan',
                                                'bg-orange-100 text-orange-700',
                                            ],
                                            'selesai' => ['Selesai', 'bg-green-100 text-green-700'],
                                            'dibatalkan' => ['Dibatalkan', 'bg-red-100 text-red-700'],
                                            'ditolak' => ['Ditolak', 'bg-red-100 text-red-700'],
                                        ];

                                        $status = $statusPesanan[$pesanan->status] ?? [
                                            str_replace('_', ' ', ucfirst($pesanan->status)),
                                            'bg-gray-100 text-gray-700',
                                        ];
                                    @endphp

                                    <span
                                        class="inline-block max-w-full break-words rounded-full px-1.5 py-1 text-[9px] font-medium leading-tight {{ $status[1] }}">
                                        {{ $status[0] }}
                                    </span>
                                </td>

                                {{-- Aksi --}}
                                <td class="px-1 py-2 text-center">
                                    <a href="{{ route('admin.pesanan.show', $pesanan->id) }}" title="Lihat Detail"
                                        aria-label="Lihat detail pesanan {{ $pesanan->kode_pesanan }}"
                                        class="inline-flex h-7 w-7 items-center justify-center rounded-md bg-amber-500 text-white transition hover:bg-amber-600">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-3 py-10 text-center">
                                    <div
                                        class="mx-auto flex h-10 w-10 items-center justify-center rounded-full bg-gray-100 text-gray-400">
                                        <i class="fa-solid fa-receipt text-lg"></i>
                                    </div>

                                    <h3 class="mt-3 text-xs font-semibold text-gray-800">
                                        {{ $keyword ? 'Pesanan Tidak Ditemukan' : 'Belum Ada Pesanan' }}
                                    </h3>

                                    <p class="mt-1 text-[10px] text-gray-500">
                                        {{ $keyword ? 'Coba nama atau kode pesanan lain.' : 'Pesanan pelanggan akan muncul di halaman ini.' }}
                                    </p>
                                </td>
                            </tr>
                        @endforelse

                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if ($pesanans->hasPages())
                <div class="border-t border-gray-200 px-3 py-2">
                    {{ $pesanans->links() }}
                </div>
            @endif

        </div>

    </div>

    {{-- Live Search --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const input = document.getElementById('cari-pesanan');
            const loading = document.getElementById('loading-cari');
            let timeout = null;

            if (!input) return;

            input.addEventListener('input', function() {
                clearTimeout(timeout);

                if (loading) {
                    loading.classList.remove('hidden');
                }

                timeout = setTimeout(function() {
                    input.form.requestSubmit();
                }, 400);
            });
        });
    </script>
@endsection
