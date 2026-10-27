@extends('layouts.main')

@section('content')
    @php
        $batasPembayaran = $pesanan->batas_pembayaran;
        $waktuHabis = $batasPembayaran && $batasPembayaran->isPast();

        $pesananDibatalkan = $pesanan->status === 'dibatalkan';
        $menungguVerifikasi = $pesanan->status_pembayaran === 'menunggu_verifikasi';
        $pembayaranLunas = $pesanan->status_pembayaran === 'lunas';

        $pembayaranDapatDiunggah =
            $pesanan->status_pembayaran === 'menunggu_pembayaran' && !$pesananDibatalkan && !$waktuHabis;
    @endphp

    <div class="min-h-screen bg-[#F8F7F4] px-3 py-5 sm:px-5">
        <div class="mx-auto max-w-4xl">

            {{-- HEADER --}}
            <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
                <div>
                    {{-- <a href="{{ route('pelanggan.pesanan.index') }}"
                        class="mb-1.5 inline-flex items-center gap-1.5 text-xs text-gray-500 transition hover:text-amber-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                        Kembali ke pesanan
                    </a> --}}

                    <h1 class="text-xl font-bold tracking-tight text-[#30251F] sm:text-2xl">
                        Pembayaran QRIS
                    </h1>
                    <p class="mt-0.5 text-xs text-gray-500 sm:text-sm">
                        Selesaikan pembayaran pesanan anda.
                    </p>
                </div>

                <span
                    class="rounded-full border border-amber-200 bg-amber-50 px-3 py-1.5 text-[11px] font-semibold text-amber-800">
                    {{ $pesanan->kode_pesanan }}
                </span>
            </div>

            {{-- NOTIFIKASI --}}
            @if (session('success'))
                <div class="mb-3 rounded-lg border border-green-200 bg-green-50 p-3 text-sm text-green-700">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-3 rounded-lg border border-red-200 bg-red-50 p-3 text-sm text-red-700">
                    <ul class="list-inside list-disc space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- GRID UTAMA --}}
            <div class="grid grid-cols-1 items-start gap-4 md:grid-cols-2">

                {{-- KOLOM KIRI --}}
                <div class="min-w-0 space-y-4">

                    {{-- RINGKASAN PESANAN --}}
                    <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
                        <div class="flex items-center gap-2.5">
                            <div
                                class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-amber-50 text-amber-700">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2M9 12h6m-6 4h6" />
                                </svg>
                            </div>

                            <div>
                                <h2 class="text-sm font-bold text-[#30251F]">
                                    Ringkasan Pesanan
                                </h2>
                                <p class="text-[11px] text-gray-500">
                                    Informasi transaksi
                                </p>
                            </div>
                        </div>

                        <div class="mt-3 space-y-2.5 border-t border-gray-100 pt-3">
                            <div class="flex justify-between gap-3 text-xs sm:text-sm">
                                <span class="text-gray-500">Kode pesanan</span>
                                <span class="text-right font-semibold text-gray-800">
                                    {{ $pesanan->kode_pesanan }}
                                </span>
                            </div>

                            <div class="flex justify-between gap-3 text-xs sm:text-sm">
                                <span class="text-gray-500">Pengambilan</span>
                                <span class="text-right font-medium text-gray-800">
                                    {{ $pesanan->metode_pengambilan === 'jemput_sendiri' ? 'Ambil sendiri' : 'Diantar' }}
                                </span>
                            </div>

                            <div class="flex justify-between gap-3 text-xs sm:text-sm">
                                <span class="text-gray-500">Metode pembayaran</span>
                                <span class="font-medium text-gray-800">QRIS</span>
                            </div>
                        </div>

                        <div class="mt-3 rounded-lg bg-[#30251F] p-3.5 text-white">
                            <p class="text-xs text-white/70">Total pembayaran</p>
                            <p class="mt-1 text-xl font-bold">
                                Rp{{ number_format($pesanan->total, 0, ',', '.') }}
                            </p>
                        </div>
                    </div>

                    {{-- KODE QRIS --}}
                    <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
                        <div>
                            <h2 class="text-sm font-bold text-[#30251F]">
                                Scan kode QRIS
                            </h2>
                            <p class="mt-1 text-xs leading-5 text-gray-500">
                                Gunakan aplikasi pembayaran yang mendukung QRIS.
                            </p>
                        </div>

                        <div class="mt-3 flex justify-center rounded-lg bg-gray-50 p-3 sm:p-4">
                            <img src="{{ asset('images/qris.jpeg') }}" alt="Kode QRIS Khas Minang"
                                class="max-h-[360px] w-full max-w-[340px] object-contain">
                        </div>

                        <div class="mt-3 flex gap-2 rounded-lg bg-blue-50 p-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="mt-0.5 h-4 w-4 shrink-0 text-blue-600"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M12 22a10 10 0 110-20 10 10 0 010 20z" />
                            </svg>

                            <p class="text-xs leading-5 text-blue-800">
                                Setelah membayar, simpan bukti transaksi untuk diunggah pada kolom konfirmasi.
                            </p>
                        </div>
                    </div>
                </div>

                {{-- KOLOM KANAN --}}
                <div class="min-w-0 space-y-4 md:sticky md:top-4">

                    {{-- COUNTDOWN --}}
                    <div id="panel-countdown" class="rounded-xl border border-amber-200 bg-amber-50 p-4 shadow-sm">

                        <div class="flex items-center gap-2.5">
                            <div
                                class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-white text-amber-700 shadow-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <circle cx="12" cy="13" r="8" stroke-width="1.8" />
                                    <path stroke-linecap="round" stroke-width="1.8" d="M12 9v4l3 2M9 2h6M12 2v3" />
                                </svg>
                            </div>

                            <div>
                                <h2 class="text-sm font-bold text-amber-950">
                                    Batas Pembayaran
                                </h2>
                                <p class="text-[11px] text-amber-800">
                                    Selesaikan sebelum waktu habis
                                </p>
                            </div>
                        </div>

                        <div class="mt-3 rounded-lg bg-white px-3 py-4 text-center">
                            <p class="text-[10px] font-semibold uppercase tracking-widest text-gray-500">
                                Sisa waktu
                            </p>

                            <p id="countdown" class="my-1 text-4xl font-bold tabular-nums tracking-tight text-amber-700">
                                Memuat...
                            </p>

                            <p id="pesan-countdown" class="text-[11px] text-gray-500">
                                Menunggu waktu pembayaran...
                            </p>
                        </div>
                    </div>

                    {{-- KONFIRMASI PEMBAYARAN --}}
                    <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
                        <div class="flex items-center gap-2.5">
                            <div
                                class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-amber-50 text-amber-700">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                        d="M12 16V4m0 0L7 9m5-5l5 5M5 14v5a2 2 0 002 2h10a2 2 0 002-2v-5" />
                                </svg>
                            </div>

                            <div>
                                <h2 class="text-sm font-bold text-[#30251F]">
                                    Konfirmasi Pembayaran
                                </h2>
                                <p class="text-[11px] text-gray-500">
                                    Unggah bukti transaksi
                                </p>
                            </div>
                        </div>

                        @if ($pesananDibatalkan)
                            <div class="mt-3 rounded-lg border border-red-200 bg-red-50 p-3">
                                <p class="text-sm font-semibold text-red-800">
                                    Pesanan dibatalkan
                                </p>
                                <p class="mt-1 text-xs leading-5 text-red-700">
                                    Bukti pembayaran tidak dapat diunggah untuk pesanan ini.
                                </p>
                            </div>
                        @elseif ($waktuHabis && $pesanan->status_pembayaran === 'menunggu_pembayaran')
                            <div class="mt-3 rounded-lg border border-red-200 bg-red-50 p-3">
                                <p class="text-sm font-semibold text-red-800">
                                    Waktu pembayaran habis
                                </p>
                                <p class="mt-1 text-xs leading-5 text-red-700">
                                    Silakan periksa status pesanan kamu.
                                </p>
                            </div>
                        @elseif ($menungguVerifikasi)
                            <div class="mt-3 rounded-lg border border-blue-200 bg-blue-50 p-3">
                                <p class="text-sm font-semibold text-blue-800">
                                    Menunggu verifikasi admin
                                </p>
                                <p class="mt-1 text-xs leading-5 text-blue-700">
                                    Bukti pembayaran sudah dikirim dan sedang diperiksa.
                                </p>
                            </div>
                        @elseif ($pembayaranLunas)
                            <div class="mt-3 rounded-lg border border-green-200 bg-green-50 p-3">
                                <p class="text-sm font-semibold text-green-800">
                                    Pembayaran terkonfirmasi
                                </p>
                                <p class="mt-1 text-xs leading-5 text-green-700">
                                    Pembayaran pesanan ini sudah lunas.
                                </p>
                            </div>
                        @elseif ($pembayaranDapatDiunggah)
                            <form id="form-pembayaran"
                                action="{{ route('checkout.payment.upload', $pesanan->kode_pesanan) }}" method="POST"
                                enctype="multipart/form-data" class="mt-3 space-y-3">

                                @csrf

                                <div>
                                    <label for="bukti_pembayaran"
                                        class="mb-1.5 block text-xs font-semibold text-gray-700">
                                        Foto bukti pembayaran
                                    </label>

                                    <input type="file" name="bukti_pembayaran" id="bukti_pembayaran" accept="image/*"
                                        required
                                        class="block w-full rounded-lg border border-gray-300 bg-white text-xs text-gray-700
                                        file:mr-2 file:border-0 file:bg-amber-50 file:px-3 file:py-2.5
                                        file:font-semibold file:text-amber-800 hover:file:bg-amber-100
                                        focus:border-amber-500 focus:outline-none focus:ring-2 focus:ring-amber-200">

                                    <p class="mt-1.5 text-[11px] leading-4 text-gray-500">
                                        Pastikan nominal dan bukti transaksi terlihat jelas.
                                    </p>
                                </div>

                                <button type="submit" id="tombol-upload"
                                    class="w-full rounded-lg bg-amber-600 px-4 py-2.5 text-xs font-semibold text-white
                                    shadow-sm transition hover:bg-amber-700 focus:outline-none focus:ring-2
                                    focus:ring-amber-500 focus:ring-offset-2">
                                    Kirim Bukti Pembayaran
                                </button>
                            </form>
                        @else
                            <div class="mt-3 rounded-lg bg-gray-50 p-3 text-xs text-gray-600">
                                Status pembayaran:
                                <span class="font-semibold">
                                    {{ str_replace('_', ' ', ucfirst($pesanan->status_pembayaran)) }}
                                </span>
                            </div>
                        @endif

                        <a href="{{ route('pelanggan.pesanan.index') }}"
                            class="mt-2.5 block rounded-lg border border-gray-200 px-4 py-2.5 text-center text-xs font-semibold text-gray-600 transition hover:bg-gray-50">
                            Lihat Pesanan Saya
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const batasPembayaran = @json($pesanan->batas_pembayaran ? $pesanan->batas_pembayaran->timestamp * 1000 : null);

            const countdown = document.getElementById('countdown');
            const pesanCountdown = document.getElementById('pesan-countdown');
            const panelCountdown = document.getElementById('panel-countdown');
            const formPembayaran = document.getElementById('form-pembayaran');

            if (!countdown) return;

            let intervalCountdown;

            function updateCountdown() {
                if (!batasPembayaran) {
                    countdown.textContent = '--:--';
                    pesanCountdown.textContent = 'Batas waktu pembayaran tidak tersedia.';
                    clearInterval(intervalCountdown);
                    return;
                }

                const sisaWaktu = batasPembayaran - Date.now();

                if (sisaWaktu <= 0) {
                    countdown.textContent = '00:00';
                    pesanCountdown.textContent = 'Waktu pembayaran sudah habis.';

                    panelCountdown.classList.remove('border-amber-200', 'bg-amber-50');
                    panelCountdown.classList.add('border-red-200', 'bg-red-50');

                    countdown.classList.remove('text-amber-700');
                    countdown.classList.add('text-red-700');

                    if (formPembayaran) {
                        formPembayaran.querySelectorAll('input, button').forEach(function(el) {
                            el.disabled = true;
                        });

                        formPembayaran.style.opacity = '0.5';
                    }

                    clearInterval(intervalCountdown);
                    return;
                }

                const totalDetik = Math.floor(sisaWaktu / 1000);
                const menit = Math.floor(totalDetik / 60);
                const detik = totalDetik % 60;

                countdown.textContent =
                    String(menit).padStart(2, '0') + ':' +
                    String(detik).padStart(2, '0');

                if (sisaWaktu <= 5 * 60 * 1000) {
                    panelCountdown.classList.remove('border-amber-200', 'bg-amber-50');
                    panelCountdown.classList.add('border-orange-200', 'bg-orange-50');
                }
            }

            updateCountdown();
            intervalCountdown = setInterval(updateCountdown, 1000);
        });
    </script>
@endsection
