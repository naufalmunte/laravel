@extends('layouts.main')

@section('content')
    @php
        $pesanWa = 'Halo Admin ' . config('toko.nama') . ", saya ingin mengonfirmasi pesanan.\n\n";
        $pesanWa .= 'Kode Pesanan: ' . $pesanan->kode_pesanan . "\n";
        $pesanWa .= 'Nama: ' . ($pesanan->nama_penerima ?? '-') . "\n";
        $pesanWa .= 'WhatsApp: ' . ($pesanan->no_whatsapp ?? '-') . "\n\n";
        $pesanWa .= "DETAIL PRODUK:\n";

        foreach ($pesanan->detailPesanan as $detail) {
            $pesanWa .= '- ' . $detail->nama_produk;
            $pesanWa .= ' (' . $detail->jumlah . ' x Rp ';
            $pesanWa .= number_format($detail->harga, 0, ',', '.') . ') = Rp ';
            $pesanWa .= number_format($detail->subtotal, 0, ',', '.') . "\n";
        }

        $pesanWa .= "\nSubtotal: Rp " . number_format($pesanan->subtotal, 0, ',', '.');
        $pesanWa .= "\nOngkir: Rp " . number_format($pesanan->ongkir, 0, ',', '.');
        $pesanWa .= "\nTOTAL: Rp " . number_format($pesanan->total, 0, ',', '.');

        $pesanWa .= "\nPengambilan: ";
        $pesanWa .= $pesanan->metode_pengambilan === 'jemput_sendiri' ? 'Jemput Sendiri' : 'Diantar';

        if ($pesanan->metode_pengambilan === 'diantar') {
            $pesanWa .= "\nAlamat: " . $pesanan->alamat;
        }

        $pesanWa .= "\nPembayaran: ";
        $pesanWa .= $pesanan->metode_pembayaran === 'cash' ? 'Cash di Toko' : 'QRIS';

        $pesanWa .= "\nStatus Pesanan: Menunggu Konfirmasi";

        $pesanWa .= "\nStatus Pembayaran: ";
        $pesanWa .= match ($pesanan->status_pembayaran) {
            'menunggu_verifikasi' => 'Menunggu Verifikasi',
            'lunas' => 'Lunas',
            default => 'Belum Bayar',
        };

        if ($pesanan->catatan) {
            $pesanWa .= "\nCatatan: " . $pesanan->catatan;
        }

        $pesanWa .= "\n\nMohon periksa dan konfirmasi pesanan saya. Terima kasih.";

        $statusPembayaran = match ($pesanan->status_pembayaran) {
            'menunggu_verifikasi' => 'Menunggu Verifikasi',
            'lunas' => 'Lunas',
            default => 'Belum Bayar',
        };

        $nomorWhatsapp = preg_replace('/\D/', '', config('toko.whatsapp', ''));
        $nomorWhatsapp = preg_replace('/^0/', '62', $nomorWhatsapp);
        $nomorWhatsapp = preg_replace('/^62/', '', $nomorWhatsapp);
    @endphp

    <div class="min-h-[calc(100vh-4rem)] bg-stone-50 px-4 py-4 sm:px-6 lg:flex lg:items-center">
        <div class="mx-auto w-full max-w-5xl">

            {{-- Header --}}
            <div class="mb-4 text-center">
                <div
                    class="mx-auto flex h-10 w-10 items-center justify-center rounded-full bg-green-100 text-lg font-semibold text-green-700">
                    ✓
                </div>

                <h1 class="mt-2 text-xl font-bold tracking-tight text-[#30251F] sm:text-2xl">
                    Pesanan Berhasil Dibuat
                </h1>

                <p class="mt-1 text-xs text-stone-500 sm:text-sm">
                    Pesananmu telah tercatat. Periksa detailnya dan konfirmasikan kepada admin.
                </p>
            </div>

            <div class="overflow-hidden rounded-2xl border border-stone-200 bg-white shadow-sm">

                {{-- Kode Pesanan --}}
                <div class="flex items-center justify-between gap-3 border-b border-stone-200 bg-stone-50 px-4 py-3 sm:px-5">
                    <div>
                        <p class="text-[11px] text-stone-500">Kode Pesanan</p>
                        <p class="mt-0.5 text-base font-bold tracking-wide text-amber-800">
                            {{ $pesanan->kode_pesanan }}
                        </p>
                    </div>

                    <span class="shrink-0 rounded-full bg-amber-100 px-3 py-1.5 text-[11px] font-semibold text-amber-800">
                        Menunggu Konfirmasi
                    </span>
                </div>

                {{-- Konten Utama --}}
                <div class="grid gap-5 p-4 sm:p-5 lg:grid-cols-2 lg:gap-7">

                    {{-- Kolom Kiri --}}
                    <div class="space-y-4">
                        <section>
                            <h2 class="mb-3 text-sm font-bold text-[#30251F]">
                                Informasi Pesanan
                            </h2>

                            <div class="space-y-2.5 text-xs sm:text-sm">
                                <div class="flex items-start justify-between gap-4">
                                    <span class="text-stone-500">Pengambilan</span>
                                    <span class="text-right font-medium text-[#30251F]">
                                        {{ $pesanan->metode_pengambilan === 'jemput_sendiri' ? 'Jemput Sendiri' : 'Diantar' }}
                                    </span>
                                </div>

                                <div class="flex items-start justify-between gap-4">
                                    <span class="text-stone-500">Metode Pembayaran</span>
                                    <span class="text-right font-medium text-[#30251F]">
                                        {{ $pesanan->metode_pembayaran === 'cash' ? 'Cash di Toko' : 'QRIS' }}
                                    </span>
                                </div>

                                <div class="flex items-start justify-between gap-4">
                                    <span class="text-stone-500">Status Pembayaran</span>
                                    <span class="text-right font-medium text-amber-800">
                                        {{ $statusPembayaran }}
                                    </span>
                                </div>
                            </div>
                        </section>

                        {{-- Informasi Penerima --}}
                        @if ($pesanan->metode_pengambilan === 'diantar')
                            <section class="border-t border-stone-100 pt-3">
                                <h2 class="mb-2 text-sm font-bold text-[#30251F]">
                                    Informasi Pengiriman
                                </h2>

                                <div class="grid grid-cols-2 gap-3 text-xs sm:text-sm">
                                    <div>
                                        <p class="text-stone-500">Nama Penerima</p>
                                        <p class="mt-1 font-medium text-[#30251F]">
                                            {{ $pesanan->nama_penerima }}
                                        </p>
                                    </div>

                                    <div>
                                        <p class="text-stone-500">Nomor WhatsApp</p>
                                        <p class="mt-1 break-words font-medium text-[#30251F]">
                                            {{ $pesanan->no_whatsapp }}
                                        </p>
                                    </div>

                                    <div class="col-span-2">
                                        <p class="text-stone-500">Alamat</p>
                                        <p class="mt-1 leading-5 text-[#30251F]">
                                            {{ $pesanan->alamat }}
                                        </p>
                                    </div>
                                </div>
                            </section>
                        @endif

                        @if ($pesanan->catatan)
                            <section class="border-t border-stone-100 pt-3">
                                <p class="text-xs text-stone-500">Catatan Pesanan</p>
                                <p class="mt-1 text-sm leading-5 text-[#30251F]">
                                    {{ $pesanan->catatan }}
                                </p>
                            </section>
                        @endif

                        {{-- Status Pembayaran --}}
                        <section
                            class="rounded-lg border px-3 py-2.5 text-xs leading-5
                            {{ $pesanan->metode_pembayaran === 'qris'
                                ? 'border-amber-200 bg-amber-50 text-amber-900'
                                : 'border-stone-200 bg-stone-50 text-stone-700' }}">

                            @if ($pesanan->metode_pembayaran === 'qris')
                                <p class="font-semibold">Pembayaran QRIS</p>

                                @if ($pesanan->status_pembayaran === 'menunggu_verifikasi')
                                    <p class="mt-0.5">
                                        Bukti pembayaran telah diunggah dan menunggu pemeriksaan admin.
                                    </p>
                                @else
                                    <p class="mt-0.5">
                                        Bukti pembayaran belum terverifikasi. Pastikan kamu telah mengunggah bukti
                                        pembayaran.
                                    </p>
                                @endif
                            @else
                                <p class="font-semibold">Pembayaran Tunai</p>
                                <p class="mt-0.5">
                                    Pembayaran dilakukan langsung di toko saat pengambilan pesanan.
                                </p>
                            @endif
                        </section>
                    </div>

                    {{-- Kolom Kanan --}}
                    <div class="min-w-0">
                        <h2 class="mb-3 text-sm font-bold text-[#30251F]">
                            Ringkasan Produk
                        </h2>

                        <div class="max-h-40 divide-y divide-stone-100 overflow-y-auto pr-1 sm:max-h-48">
                            @foreach ($pesanan->detailPesanan as $detail)
                                <div class="flex items-start justify-between gap-3 py-2.5 first:pt-0">
                                    <div class="min-w-0">
                                        <p class="break-words text-xs font-medium text-[#30251F] sm:text-sm">
                                            {{ $detail->nama_produk }}
                                        </p>

                                        <p class="mt-1 text-[11px] text-stone-500">
                                            {{ $detail->jumlah }} ×
                                            Rp {{ number_format($detail->harga, 0, ',', '.') }}
                                        </p>
                                    </div>

                                    <p class="shrink-0 text-xs font-semibold text-[#30251F] sm:text-sm">
                                        Rp {{ number_format($detail->subtotal, 0, ',', '.') }}
                                    </p>
                                </div>
                            @endforeach
                        </div>

                        {{-- Rincian Harga --}}
                        <div class="mt-3 space-y-2 border-t border-stone-200 pt-3 text-xs sm:text-sm">
                            <div class="flex justify-between gap-3">
                                <span class="text-stone-500">Subtotal</span>
                                <span class="text-right text-[#30251F]">
                                    Rp {{ number_format($pesanan->subtotal, 0, ',', '.') }}
                                </span>
                            </div>

                            <div class="flex justify-between gap-3">
                                <span class="text-stone-500">Ongkir</span>
                                <span class="text-right text-[#30251F]">
                                    Rp {{ number_format($pesanan->ongkir, 0, ',', '.') }}
                                </span>
                            </div>

                            <div class="flex items-center justify-between gap-3 border-t border-stone-100 pt-3">
                                <span class="font-bold text-[#30251F]">Total Pembayaran</span>
                                <span class="text-right text-base font-bold text-amber-800">
                                    Rp {{ number_format($pesanan->total, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Tombol Aksi --}}
                <div class="flex flex-col gap-2.5 border-t border-stone-200 p-4 sm:flex-row sm:p-5">
                    <a href="https://api.whatsapp.com/send?phone=62{{ $nomorWhatsapp }}&text={{ rawurlencode($pesanWa) }}"
                        target="_blank" rel="noopener noreferrer"
                        class="flex flex-1 items-center justify-center rounded-lg bg-green-600 px-4 py-2.5 text-center text-xs font-semibold text-white transition hover:bg-green-700 sm:text-sm">
                        Konfirmasi Admin via WhatsApp
                    </a>

                    <a href="{{ route('produk.index') }}"
                        class="flex flex-1 items-center justify-center rounded-lg border border-stone-300 px-4 py-2.5 text-center text-xs font-semibold text-[#30251F] transition hover:bg-stone-50 sm:text-sm">
                        Kembali Belanja
                    </a>
                </div>
            </div>

            <p class="mt-3 text-center text-[11px] text-stone-500">
                Pesanan diproses setelah admin melakukan konfirmasi.
            </p>
        </div>
    </div>
@endsection
