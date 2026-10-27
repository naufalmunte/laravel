@extends('layouts.main')

@section('content')
    @php
        $statusSekarang = $pesanan->status;

        $urutanProgres = [
            'menunggu_konfirmasi' => 0,
            'diproses' => 1,
            'dikirim' => 2,
            'siap_diambil' => 2,
            'menunggu_konfirmasi_pelanggan' => 3,
            'selesai' => 4,
        ];

        $progresAktif = $urutanProgres[$statusSekarang] ?? -1;

        $statusLabel = [
            'menunggu_konfirmasi' => 'Menunggu Konfirmasi',
            'diproses' => 'Sedang Diproses',
            'dikirim' => 'Sedang Dikirim',
            'siap_diambil' => 'Siap Diambil',
            'menunggu_konfirmasi_pelanggan' => 'Menunggu Konfirmasi Penerimaan',
            'selesai' => 'Selesai',
            'dibatalkan' => 'Dibatalkan',
        ];

        $warnaStatus = [
            'menunggu_konfirmasi' => 'bg-amber-100 text-amber-700',
            'diproses' => 'bg-blue-100 text-blue-700',
            'dikirim' => 'bg-indigo-100 text-indigo-700',
            'siap_diambil' => 'bg-purple-100 text-purple-700',
            'menunggu_konfirmasi_pelanggan' => 'bg-orange-100 text-orange-700',
            'selesai' => 'bg-green-100 text-green-700',
            'dibatalkan' => 'bg-red-100 text-red-700',
        ];

        $statusPembayaranLabel = [
            'belum_bayar' => 'Belum Dibayar',
            'menunggu_verifikasi' => 'Menunggu Verifikasi',
            'lunas' => 'Lunas',
            'ditolak' => 'Pembayaran Ditolak',
        ];

        $warnaPembayaran = [
            'belum_bayar' => 'bg-amber-100 text-amber-700',
            'menunggu_verifikasi' => 'bg-blue-100 text-blue-700',
            'lunas' => 'bg-green-100 text-green-700',
            'ditolak' => 'bg-red-100 text-red-700',
        ];

        $tahapanProgres = [
            [
                'judul' => 'Pesanan Berhasil Dibuat',
                'deskripsi' => 'Pesanan kamu berhasil dibuat dan tercatat di sistem.',
                'ikon' => 'shopping-bag',
            ],
            [
                'judul' => 'Pesanan Diproses',
                'deskripsi' => 'Pesanan sedang disiapkan oleh penjual.',
                'ikon' => 'package',
            ],
            [
                'judul' =>
                    $pesanan->metode_pengambilan === 'diambil' ? 'Pesanan Siap Diambil' : 'Pesanan Dalam Pengiriman',
                'deskripsi' =>
                    $pesanan->metode_pengambilan === 'diambil'
                        ? 'Pesanan sudah siap untuk diambil.'
                        : 'Pesanan sedang disiapkan atau dikirim ke alamat tujuan.',
                'ikon' => $pesanan->metode_pengambilan === 'diambil' ? 'store' : 'truck',
            ],
            [
                'judul' => 'Konfirmasi Penerimaan',
                'deskripsi' => 'Periksa bukti penyerahan, lalu konfirmasi jika pesanan sudah diterima.',
                'ikon' => 'clipboard-check',
            ],
            [
                'judul' => 'Pesanan Diterima',
                'deskripsi' => 'Pesanan telah selesai. Terima kasih sudah berbelanja!',
                'ikon' => 'check-circle',
            ],
        ];

        $tahapanTampilan = array_reverse($tahapanProgres);

        $buktiPenyerahanUrl = $pesanan->bukti_penyerahan ? asset('storage/' . $pesanan->bukti_penyerahan) : null;

        $buktiPembayaranUrl = $pesanan->bukti_pembayaran ? asset('storage/' . $pesanan->bukti_pembayaran) : null;

        $metodePengambilanLabel = $pesanan->metode_pengambilan === 'diambil' ? 'Ambil di Toko' : 'Diantar ke Alamat';

        $metodePembayaranLabel = [
            'transfer' => 'Transfer Bank',
            'qris' => 'QRIS',
            'tunai' => 'Tunai',
        ];

        $pesananDibatalkan = $statusSekarang === 'dibatalkan';
    @endphp

    <div class="mt-3 min-h-screen bg-[#FAF8F4] px-4 py-6 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-5xl space-y-5">

            {{-- Tombol Kembali --}}
            <a href="{{ route('pelanggan.pesanan.index') }}"
                class="inline-flex items-center gap-2 text-sm font-medium text-[#6B5A4E] transition hover:text-[#30251F]">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                    stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
                Kembali ke Riwayat Pesanan
            </a>

            {{-- Header Pesanan --}}
            <div class="rounded-xl border border-[#E9E1D8] bg-white p-4 sm:p-5">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                    <div class="min-w-0">
                        <p class="text-xs text-gray-500">Detail Pesanan</p>

                        <h1 class="mt-1 text-lg font-bold text-[#30251F] sm:text-xl">
                            {{ $pesanan->kode_pesanan }}
                        </h1>

                        <p class="mt-1 text-xs text-gray-500">
                            Dipesan pada
                            {{ $pesanan->created_at->translatedFormat('d F Y, H:i') }}
                        </p>
                    </div>

                    <div class="flex flex-wrap items-center gap-2 sm:shrink-0">
                        <span
                            class="inline-flex shrink-0 self-start rounded-full px-3 py-1.5 text-xs font-semibold leading-4 {{ $warnaStatus[$statusSekarang] ?? 'bg-gray-100 text-gray-700' }}">
                            {{ $statusLabel[$statusSekarang] ?? ucfirst(str_replace('_', ' ', $statusSekarang)) }}
                        </span>

                        <span
                            class="inline-flex shrink-0 self-start rounded-full px-3 py-1.5 text-xs font-semibold leading-4 {{ $warnaPembayaran[$pesanan->status_pembayaran] ?? 'bg-gray-100 text-gray-700' }}">
                            {{ $statusPembayaranLabel[$pesanan->status_pembayaran] ?? ucfirst(str_replace('_', ' ', $pesanan->status_pembayaran)) }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 items-start gap-5 lg:grid-cols-3">

                {{-- Kolom Kiri --}}
                <div class="space-y-5 lg:col-span-2">

                    {{-- Progres Pesanan --}}
                    <div class="rounded-xl border border-[#E9E1D8] bg-white p-4 sm:p-5">
                        <div class="mb-5">
                            <h2 class="text-base font-bold text-[#30251F]">
                                Lacak Pesanan
                            </h2>
                            <p class="mt-1 text-xs text-gray-500">
                                Pantau perkembangan pesanan kamu di sini.
                            </p>
                        </div>

                        @if ($pesananDibatalkan)
                            <div class="flex items-start gap-3 rounded-lg bg-red-50 p-3">
                                <div
                                    class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-red-100 text-red-600">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </div>

                                <div>
                                    <p class="text-sm font-semibold text-red-700">
                                        Pesanan Dibatalkan
                                    </p>
                                    <p class="mt-1 text-xs leading-5 text-red-600">
                                        Pesanan ini telah dibatalkan sehingga proses pesanan tidak dilanjutkan.
                                    </p>
                                </div>
                            </div>
                        @else
                            <div class="space-y-0">
                                @foreach ($tahapanTampilan as $tahapTampilan)
                                    @php
                                        $index = 4 - $loop->index;
                                        $tahap = $tahapanProgres[$index];

                                        $tercapai = $index <= $progresAktif;
                                        $sedangBerjalan = $index === $progresAktif;
                                        $tahapTerakhir = $loop->last;
                                    @endphp

                                    <div class="flex gap-3">
                                        {{-- Ikon dan Garis --}}
                                        <div class="flex w-7 shrink-0 flex-col items-center">
                                            <div
                                                class="flex h-7 w-7 items-center justify-center rounded-full
                                                {{ $tercapai ? 'bg-green-100 text-green-600' : 'bg-gray-100 text-gray-400' }}
                                                {{ $sedangBerjalan ? 'ring-2 ring-green-200' : '' }}">

                                                @if ($tercapai)
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M5 13l4 4L19 7" />
                                                    </svg>
                                                @elseif ($tahap['ikon'] === 'check-circle')
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M9 12l2 2 4-4m5 0a8 8 0 11-16 0 8 8 0 0116 0z" />
                                                    </svg>
                                                @elseif ($tahap['ikon'] === 'truck')
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M9 17h6m-9 0H4V6h11v11m0 0h2m-2 0V9h4l3 4v4h-2m-9 0a2 2 0 104 0m5 0a2 2 0 104 0" />
                                                    </svg>
                                                @elseif ($tahap['ikon'] === 'store')
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M3 10l2-6h14l2 6M5 10v10h14V10M3 10a3 3 0 006 0 3 3 0 006 0 3 3 0 006 0" />
                                                    </svg>
                                                @elseif ($tahap['ikon'] === 'clipboard-check')
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 14l2 2 4-4" />
                                                    </svg>
                                                @elseif ($tahap['ikon'] === 'package')
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M12 3l9 5-9 5-9-5 9-5zm-9 5v8l9 5 9-5V8M12 13v8" />
                                                    </svg>
                                                @else
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z" />
                                                    </svg>
                                                @endif
                                            </div>

                                            @if (!$tahapTerakhir)
                                                <div
                                                    class="my-1 min-h-[30px] w-px flex-1 {{ $index - 1 < $progresAktif ? 'bg-green-400' : 'bg-gray-200' }}">
                                                </div>
                                            @endif
                                        </div>

                                        {{-- Informasi Tahapan --}}
                                        <div class="min-w-0 flex-1 pb-5">
                                            <p
                                                class="text-[13px] font-semibold {{ $tercapai ? 'text-[#30251F]' : 'text-gray-400' }}">
                                                {{ $tahap['judul'] }}

                                                @if ($sedangBerjalan)
                                                    <span
                                                        class="ml-1 rounded-full bg-green-50 px-2 py-0.5 text-[10px] font-medium text-green-700">
                                                        Proses saat ini
                                                    </span>
                                                @endif
                                            </p>

                                            <p
                                                class="mt-1 text-xs leading-5 {{ $tercapai ? 'text-gray-500' : 'text-gray-400' }}">
                                                {{ $tahap['deskripsi'] }}
                                            </p>

                                            {{-- Tombol Bukti Penyerahan --}}
                                            @if ($index === 2 && $buktiPenyerahanUrl)
                                                <button type="button"
                                                    onclick="bukaPopupBukti(@js($buktiPenyerahanUrl), 'Bukti Penyerahan')"
                                                    class="mt-2 inline-flex items-center gap-1.5 rounded-lg border border-[#D8C7B8] px-3 py-2 text-xs font-semibold text-[#6B4632] transition hover:bg-[#FAF3EC]">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4"
                                                        fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                        stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M2.25 12s3.5-7 9.75-7 9.75 7 9.75 7-3.5 7-9.75 7-9.75-7-9.75-7z" />
                                                        <circle cx="12" cy="12" r="3" />
                                                    </svg>
                                                    Lihat Bukti Penyerahan
                                                </button>
                                            @endif

                                            {{-- Konfirmasi Penerimaan: Modal Custom --}}
                                            @if ($index === 3 && $statusSekarang === 'menunggu_konfirmasi_pelanggan' && $buktiPenyerahanUrl)
                                                <form id="formKonfirmasiDiterima"
                                                    action="{{ route('pelanggan.pesanan.konfirmasiDiterima', $pesanan->id) }}"
                                                    method="POST" class="mt-3">
                                                    @csrf

                                                    <button type="button" onclick="bukaModalKonfirmasiDiterima()"
                                                        class="inline-flex items-center gap-1.5 rounded-lg bg-[#30251F] px-3 py-2 text-xs font-semibold text-white transition hover:bg-[#49382F]">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4"
                                                            fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                            stroke-width="2">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="M5 13l4 4L19 7" />
                                                        </svg>
                                                        Konfirmasi Pesanan Diterima
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    {{-- Daftar Produk --}}
                    <div class="rounded-xl border border-[#E9E1D8] bg-white p-4 sm:p-5">
                        <h2 class="mb-4 text-base font-bold text-[#30251F]">
                            Produk yang Dipesan
                        </h2>

                        <div class="divide-y divide-gray-100">
                            @foreach ($pesanan->detailPesanan as $detail)
                                <div class="flex items-start justify-between gap-3 py-3 first:pt-0 last:pb-0">
                                    <div class="flex min-w-0 flex-1 items-start gap-3">
                                        <div
                                            class="h-10 w-15 shrink-0 overflow-hidden rounded-xl border border-[#E9E1D8] bg-[#FAF8F4]">
                                            @if ($detail->produk && $detail->produk->gambar)
                                                <img src="{{ asset('storage/' . $detail->produk->gambar) }}"
                                                    alt="{{ $detail->nama_produk }}" class="h-full w-full object-cover">
                                            @else
                                                <div
                                                    class="flex h-full w-full items-center justify-center text-xs text-gray-400">
                                                    Tidak ada gambar
                                                </div>
                                            @endif
                                        </div>

                                        <div class="min-w-0 flex-1">
                                            <p class="text-sm font-semibold text-[#30251F]">
                                                {{ $detail->nama_produk }}
                                            </p>
                                            <p class="mt-1 text-xs text-gray-500">
                                                {{ $detail->jumlah }} × Rp{{ number_format($detail->harga, 0, ',', '.') }}
                                            </p>
                                        </div>
                                    </div>

                                    <p class="shrink-0 text-sm font-semibold text-[#30251F]">
                                        Rp{{ number_format($detail->subtotal, 0, ',', '.') }}
                                    </p>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Informasi Penerima --}}
                    <div class="mb-6 rounded-xl border border-[#E9E1D8] bg-white p-4 sm:p-5">
                        <h2 class="mb-4 text-base font-bold text-[#30251F]">
                            Informasi Penerima
                        </h2>

                        <div class="space-y-3 text-sm">
                            <div>
                                <p class="text-xs text-gray-500">Nama Penerima</p>
                                <p class="mt-1 font-medium text-gray-800">{{ $pesanan->nama_penerima }}</p>
                            </div>

                            <div>
                                <p class="text-xs text-gray-500">Nomor WhatsApp</p>
                                <p class="mt-1 font-medium text-gray-800">{{ $pesanan->no_whatsapp }}</p>
                            </div>

                            <div>
                                <p class="text-xs text-gray-500">Metode Pengambilan</p>
                                <p class="mt-1 font-medium text-gray-800">{{ $metodePengambilanLabel }}</p>
                            </div>

                            @if ($pesanan->metode_pengambilan !== 'diambil')
                                <div>
                                    <p class="text-xs text-gray-500">Alamat Pengiriman</p>
                                    <p class="mt-1 whitespace-pre-line leading-6 text-gray-800">{{ $pesanan->alamat }}</p>
                                </div>
                            @endif

                            @if ($pesanan->catatan)
                                <div>
                                    <p class="text-xs text-gray-500">Catatan</p>
                                    <p class="mt-1 whitespace-pre-line leading-6 text-gray-800">{{ $pesanan->catatan }}
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Kolom Kanan: Ringkasan --}}
                <div class="space-y-5 lg:sticky lg:top-5">

                    {{-- Ringkasan Pembayaran --}}
                    <div class="rounded-xl border border-[#E9E1D8] bg-white p-4 sm:p-5">
                        <h2 class="mb-4 text-base font-bold text-[#30251F]">
                            Ringkasan Pembayaran
                        </h2>

                        <div class="space-y-3 text-sm">
                            <div class="flex items-center justify-between gap-3">
                                <span class="text-gray-500">Subtotal Produk</span>
                                <span class="font-medium text-gray-800">
                                    Rp{{ number_format($pesanan->subtotal, 0, ',', '.') }}
                                </span>
                            </div>

                            <div class="flex items-center justify-between gap-3">
                                <span class="text-gray-500">Ongkos Kirim</span>
                                <span class="font-medium text-gray-800">
                                    Rp{{ number_format($pesanan->ongkir, 0, ',', '.') }}
                                </span>
                            </div>

                            <div class="border-t border-dashed border-gray-200 pt-3">
                                <div class="flex items-center justify-between gap-3">
                                    <span class="font-semibold text-[#30251F]">Total</span>
                                    <span class="text-base font-bold text-[#30251F]">
                                        Rp{{ number_format($pesanan->total, 0, ',', '.') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Informasi Pembayaran --}}
                    <div class="rounded-xl border border-[#E9E1D8] bg-white p-4 sm:p-5">
                        <h2 class="mb-4 text-base font-bold text-[#30251F]">
                            Informasi Pembayaran
                        </h2>

                        <div class="space-y-3">
                            <div class="flex items-start justify-between gap-3">
                                <span class="text-sm text-gray-500">Metode Pembayaran</span>
                                <span class="text-right text-sm font-semibold text-[#30251F]">
                                    {{ $metodePembayaranLabel[$pesanan->metode_pembayaran] ?? ucfirst($pesanan->metode_pembayaran) }}
                                </span>
                            </div>

                            @php
                                $statusPembayaranLabel = [
                                    'belum_bayar' => 'Belum Bayar',
                                    'menunggu_pembayaran' => 'Menunggu Pembayaran',
                                    'menunggu_verifikasi' => 'Menunggu Verifikasi',
                                    'lunas' => 'Lunas',
                                    'ditolak' => 'Pembayaran Ditolak',
                                ];

                                $statusPembayaranWarna = [
                                    'belum_bayar' => 'bg-amber-100 text-amber-700',
                                    'menunggu_pembayaran' => 'bg-amber-100 text-amber-700',
                                    'menunggu_verifikasi' => 'bg-blue-100 text-blue-700',
                                    'lunas' => 'bg-green-100 text-green-700',
                                    'ditolak' => 'bg-red-100 text-red-700',
                                ];
                            @endphp

                            <div class="flex items-start justify-between gap-3">
                                <span class="text-sm text-gray-500">Status Pembayaran</span>
                                <span
                                    class="rounded-full px-2.5 py-1 text-right text-xs font-semibold {{ $statusPembayaranWarna[$pesanan->status_pembayaran] ?? 'bg-gray-100 text-gray-600' }}">
                                    {{ $statusPembayaranLabel[$pesanan->status_pembayaran] ?? ucfirst(str_replace('_', ' ', $pesanan->status_pembayaran)) }}
                                </span>
                            </div>

                            @if ($buktiPembayaranUrl)
                                <div class="border-t border-gray-100 pt-3">
                                    <p class="mb-2 text-sm text-gray-500">Bukti Pembayaran</p>

                                    <button type="button"
                                        onclick="bukaPopupBukti(@js($buktiPembayaranUrl), 'Bukti Pembayaran')"
                                        class="inline-flex w-full items-center justify-center rounded-lg border border-[#E9E1D8] px-4 py-2.5 text-sm font-semibold text-[#59372B] transition hover:bg-[#F8F4EF]">
                                        Lihat Bukti Pembayaran
                                    </button>
                                </div>
                            @endif

                            {{-- Tombol Lanjutkan Pembayaran QRIS --}}
                            @if (
                                $pesanan->metode_pembayaran === 'qris' &&
                                    in_array($pesanan->status_pembayaran, ['menunggu_pembayaran', 'belum_bayar', 'ditolak']) &&
                                    !in_array($pesanan->status, ['dibatalkan', 'selesai']))
                                <div class="border-t border-gray-100 pt-3">
                                    @if ($pesanan->status_pembayaran === 'ditolak')
                                        <p class="mb-3 text-xs leading-5 text-red-600">
                                            Bukti pembayaran sebelumnya ditolak.
                                            Silakan lakukan pembayaran kembali dan unggah bukti pembayaran yang baru.
                                        </p>
                                    @else
                                        <p class="mb-3 text-xs leading-5 text-gray-500">
                                            Pesanan kamu belum menyelesaikan pembayaran QRIS.
                                            Lanjutkan pembayaran agar pesanan dapat diproses oleh admin.
                                        </p>
                                    @endif

                                    <a href="{{ route('checkout.payment', $pesanan->kode_pesanan) }}"
                                        class="inline-flex w-full items-center justify-center gap-2 rounded-lg bg-[#59372B] px-4 py-3 text-sm font-semibold text-white transition hover:bg-[#744735]">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2">
                                            <rect x="3" y="3" width="7" height="7" rx="1" />
                                            <rect x="14" y="3" width="7" height="7" rx="1" />
                                            <rect x="3" y="14" width="7" height="7" rx="1" />
                                            <path d="M14 14h3v3h-3zM20 14v2M20 20h-3M14 20v-1" />
                                        </svg>
                                        Lanjutkan Pembayaran
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Popup Bukti --}}
    <div id="popupBukti" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 p-4"
        onclick="if (event.target === this) tutupPopupBukti()">

        <div class="relative w-full max-w-2xl overflow-hidden rounded-xl bg-white shadow-xl">
            <div class="flex items-center justify-between border-b border-gray-100 px-4 py-3">
                <h3 id="judulPopupBukti" class="text-sm font-bold text-[#30251F]">
                    Bukti Pesanan
                </h3>

                <button type="button" onclick="tutupPopupBukti()"
                    class="flex h-8 w-8 items-center justify-center rounded-lg text-gray-500 transition hover:bg-gray-100 hover:text-gray-800"
                    aria-label="Tutup popup">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="max-h-[75vh] overflow-auto bg-gray-50 p-3">
                <img id="gambarPopupBukti" src="" alt="Bukti pesanan"
                    class="mx-auto max-h-[75vh] max-w-full rounded-lg object-contain">
            </div>
        </div>
    </div>

    {{-- Modal Konfirmasi Penerimaan Barang --}}
    <div id="modalKonfirmasiDiterima"
        class="fixed inset-0 z-[100] hidden items-center justify-center overflow-y-auto bg-black/50 p-4 backdrop-blur-sm">

        <div id="modalKonfirmasiDiterimaContent"
            class="my-auto w-full max-w-md scale-95 rounded-2xl bg-white p-6 opacity-0 shadow-2xl transition-all duration-200">

            {{-- Ikon --}}
            <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-green-100 text-green-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                </svg>
            </div>

            {{-- Judul --}}
            <div class="mt-4 text-center">
                <h3 class="text-lg font-bold text-[#30251F]">
                    Konfirmasi Penerimaan Pesanan
                </h3>

                <p class="mt-2 text-sm leading-6 text-gray-500">
                    Apakah kamu yakin pesanan
                    <span class="font-semibold text-[#30251F]">
                        {{ $pesanan->kode_pesanan }}
                    </span>
                    sudah diterima?
                </p>
            </div>

            {{-- Informasi --}}
            <div class="mt-5 rounded-xl border border-green-200 bg-green-50 p-3">
                <div class="flex items-start gap-2.5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="mt-0.5 h-5 w-5 shrink-0 text-green-600" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M13 16h-1v-4h-1m1-4h.01M12 21a9 9 0 110-18 9 9 0 010 18z" />
                    </svg>

                    <p class="text-xs leading-5 text-green-800">
                        Pastikan kamu sudah menerima barang dan memeriksa kondisinya.
                        Setelah dikonfirmasi, pesanan akan ditandai selesai.
                    </p>
                </div>
            </div>

            {{-- Tombol Modal --}}
            <div class="mt-6 flex flex-col-reverse gap-2 sm:flex-row sm:justify-end">
                <button type="button" onclick="tutupModalKonfirmasiDiterima()"
                    class="rounded-lg border border-[#E9E1D8] px-4 py-2.5 text-sm font-semibold text-gray-700 transition hover:bg-[#FAF8F4]">
                    Belum, Kembali
                </button>

                <button type="button" id="tombolYaKonfirmasiDiterima" onclick="kirimKonfirmasiDiterima()"
                    class="inline-flex items-center justify-center gap-2 rounded-lg bg-[#30251F] px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-[#49382F] disabled:cursor-not-allowed disabled:opacity-60">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                    Ya, Pesanan Diterima
                </button>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Popup untuk melihat bukti pembayaran atau penyerahan.
        function bukaPopupBukti(url, judul = 'Bukti Pesanan') {
            const popup = document.getElementById('popupBukti');
            const gambar = document.getElementById('gambarPopupBukti');
            const judulPopup = document.getElementById('judulPopupBukti');

            if (!popup || !gambar || !judulPopup) return;

            gambar.src = url;
            judulPopup.textContent = judul;

            popup.classList.remove('hidden');
            popup.classList.add('flex');
            document.body.classList.add('overflow-hidden');
        }

        function tutupPopupBukti() {
            const popup = document.getElementById('popupBukti');
            const gambar = document.getElementById('gambarPopupBukti');

            if (!popup || !gambar) return;

            popup.classList.add('hidden');
            popup.classList.remove('flex');
            gambar.src = '';

            // Jangan membuka scroll jika modal lain masih aktif.
            const modalKonfirmasi = document.getElementById('modalKonfirmasiDiterima');

            if (!modalKonfirmasi || modalKonfirmasi.classList.contains('hidden')) {
                document.body.classList.remove('overflow-hidden');
            }
        }

        // Membuka modal konfirmasi penerimaan.
        function bukaModalKonfirmasiDiterima() {
            const form = document.getElementById('formKonfirmasiDiterima');
            const modal = document.getElementById('modalKonfirmasiDiterima');
            const content = document.getElementById('modalKonfirmasiDiterimaContent');

            if (!form || !modal || !content) return;

            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.classList.add('overflow-hidden');

            requestAnimationFrame(() => {
                content.classList.remove('scale-95', 'opacity-0');
                content.classList.add('scale-100', 'opacity-100');
            });
        }

        // Menutup modal konfirmasi penerimaan.
        function tutupModalKonfirmasiDiterima() {
            const modal = document.getElementById('modalKonfirmasiDiterima');
            const content = document.getElementById('modalKonfirmasiDiterimaContent');

            if (!modal || !content) return;

            content.classList.remove('scale-100', 'opacity-100');
            content.classList.add('scale-95', 'opacity-0');

            setTimeout(() => {
                modal.classList.add('hidden');
                modal.classList.remove('flex');

                const popupBukti = document.getElementById('popupBukti');

                if (!popupBukti || popupBukti.classList.contains('hidden')) {
                    document.body.classList.remove('overflow-hidden');
                }
            }, 200);
        }

        // Mengirim konfirmasi ke route Laravel yang sudah ada.
        function kirimKonfirmasiDiterima() {
            const form = document.getElementById('formKonfirmasiDiterima');
            const tombol = document.getElementById('tombolYaKonfirmasiDiterima');

            if (!form || !tombol) return;

            // Mencegah klik ganda.
            tombol.disabled = true;
            tombol.innerHTML = `
                <svg class="h-4 w-4 animate-spin"
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10"
                        stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor"
                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                </svg>
                Memproses...
            `;

            form.requestSubmit();
        }

        // Klik area gelap untuk menutup modal bukti.
        document.getElementById('popupBukti')?.addEventListener('click', function(event) {
            if (event.target === this) {
                tutupPopupBukti();
            }
        });

        // Klik area gelap untuk menutup modal konfirmasi.
        document.getElementById('modalKonfirmasiDiterima')?.addEventListener('click', function(event) {
            if (event.target === this) {
                tutupModalKonfirmasiDiterima();
            }
        });

        // Escape menutup modal yang sedang terbuka.
        document.addEventListener('keydown', function(event) {
            if (event.key !== 'Escape') return;

            const modalKonfirmasi = document.getElementById('modalKonfirmasiDiterima');
            const popupBukti = document.getElementById('popupBukti');

            if (modalKonfirmasi && !modalKonfirmasi.classList.contains('hidden')) {
                tutupModalKonfirmasiDiterima();
            } else if (popupBukti && !popupBukti.classList.contains('hidden')) {
                tutupPopupBukti();
            }
        });
    </script>
@endpush
