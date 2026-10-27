@extends('layouts.main')

@section('content')
    <div class="min-h-screen bg-stone-50 py-6 sm:py-8">
        <div class="mx-auto max-w-5xl px-4 sm:px-5 lg:px-6">

            {{-- Header --}}
            <div class="mb-6">
                <a href="{{ route('keranjang.index') }}"
                    class="mb-3 inline-flex items-center gap-2 text-xs font-medium text-stone-500 transition hover:text-amber-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Kembali ke keranjang
                </a>

                <h1 class="text-xl font-bold tracking-tight text-stone-800 sm:text-2xl">
                    Checkout
                </h1>
                <p class="mt-1 text-sm text-stone-500">
                    Lengkapi informasi pesanan sebelum melanjutkan.
                </p>
            </div>

            {{-- Error Validation --}}
            @if ($errors->any())
                <div class="mb-5 rounded-lg border border-red-200 bg-red-50 p-3 text-sm text-red-700">
                    <p class="mb-1 font-semibold">Periksa kembali data kamu:</p>
                    <ul class="list-inside list-disc space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('checkout.store') }}" method="POST">
                @csrf

                <div class="grid grid-cols-1 items-start gap-5 lg:grid-cols-3">

                    {{-- Form Checkout --}}
                    <div class="space-y-4 lg:col-span-2">

                        {{-- Metode Pengambilan --}}
                        <section class="rounded-xl border border-stone-200 bg-white p-4 shadow-sm sm:p-5">
                            <div class="mb-4 flex items-center gap-3">
                                <div
                                    class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-amber-100 text-amber-800">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 10h18M5 10V6a2 2 0 012-2h10a2 2 0 012 2v4M5 10v10h14V10M9 14h6" />
                                    </svg>
                                </div>
                                <div>
                                    <h2 class="text-sm font-semibold text-stone-800">Metode Pengambilan</h2>
                                    <p class="text-xs text-stone-500">Pilih cara mendapatkan pesanan kamu.</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                                <label class="cursor-pointer">
                                    <input type="radio" name="metode_pengambilan" value="jemput_sendiri"
                                        class="peer sr-only"
                                        {{ old('metode_pengambilan', 'jemput_sendiri') === 'jemput_sendiri' ? 'checked' : '' }}>

                                    <div
                                        class="rounded-lg border border-stone-200 p-3 transition
                                        peer-checked:border-amber-600 peer-checked:bg-amber-50
                                        peer-focus-visible:ring-2 peer-focus-visible:ring-amber-500">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-stone-100 text-stone-700">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M5 12h14M12 5l7 7-7 7" />
                                                </svg>
                                            </div>
                                            <div>
                                                <p class="text-sm font-semibold text-stone-800">Jemput Sendiri</p>
                                                <p class="mt-0.5 text-xs text-stone-500">Ambil langsung di gerai.</p>
                                            </div>
                                        </div>
                                    </div>
                                </label>

                                <label class="cursor-pointer">
                                    <input type="radio" name="metode_pengambilan" value="diantar" class="peer sr-only"
                                        {{ old('metode_pengambilan') === 'diantar' ? 'checked' : '' }}>

                                    <div
                                        class="rounded-lg border border-stone-200 p-3 transition
                                        peer-checked:border-amber-600 peer-checked:bg-amber-50
                                        peer-focus-visible:ring-2 peer-focus-visible:ring-amber-500">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-stone-100 text-stone-700">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M3 7h11v10H3zM14 10h4l3 3v4h-7zM7 20a2 2 0 100-4 2 2 0 000 4zM18 20a2 2 0 100-4 2 2 0 000 4z" />
                                                </svg>
                                            </div>
                                            <div>
                                                <p class="text-sm font-semibold text-stone-800">Diantar</p>
                                                <p class="mt-0.5 text-xs text-stone-500">Dikirim ke alamat kamu.</p>
                                            </div>
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </section>

                        {{-- Informasi Penerima --}}
                        <section id="bagianPenerima"
                            class="hidden rounded-xl border border-stone-200 bg-white p-4 shadow-sm sm:p-5">

                            <div class="mb-4 flex items-center gap-3">
                                <div
                                    class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-amber-100 text-amber-800">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM4 21a8 8 0 0116 0" />
                                    </svg>
                                </div>
                                <div>
                                    <h2 class="text-sm font-semibold text-stone-800">Informasi Penerima</h2>
                                    <p class="text-xs text-stone-500">Isi informasi untuk pengiriman pesanan.</p>
                                </div>
                            </div>

                            <div class="space-y-3">
                                <div>
                                    <label for="nama_penerima" class="mb-1.5 block text-xs font-medium text-stone-700">
                                        Nama Penerima
                                    </label>
                                    <input type="text" id="nama_penerima" name="nama_penerima"
                                        value="{{ old('nama_penerima', auth()->user()->name ?? '') }}"
                                        placeholder="Masukkan nama penerima"
                                        class="w-full rounded-lg border border-stone-300 px-3 py-2.5 text-sm outline-none transition focus:border-amber-600 focus:ring-2 focus:ring-amber-100">
                                </div>

                                <div>
                                    <label for="no_whatsapp" class="mb-1.5 block text-xs font-medium text-stone-700">
                                        Nomor WhatsApp
                                    </label>
                                    <input type="tel" id="no_whatsapp" name="no_whatsapp"
                                        value="{{ old('no_whatsapp') }}" placeholder="Contoh: 081234567890"
                                        class="w-full rounded-lg border border-stone-300 px-3 py-2.5 text-sm outline-none transition focus:border-amber-600 focus:ring-2 focus:ring-amber-100">
                                </div>

                                <div>
                                    <label for="alamat" class="mb-1.5 block text-xs font-medium text-stone-700">
                                        Alamat Lengkap
                                    </label>
                                    <textarea id="alamat" name="alamat" rows="3" placeholder="Nama jalan, nomor rumah, kelurahan, dan patokan"
                                        class="w-full rounded-lg border border-stone-300 px-3 py-2.5 text-sm outline-none transition focus:border-amber-600 focus:ring-2 focus:ring-amber-100">{{ old('alamat') }}</textarea>
                                </div>
                            </div>
                        </section>

                        {{-- Metode Pembayaran --}}
                        <section class="rounded-xl border border-stone-200 bg-white p-4 shadow-sm sm:p-5">
                            <div class="mb-4 flex items-center gap-3">
                                <div
                                    class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-amber-100 text-amber-800">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 10h18M5 6h14a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2z" />
                                    </svg>
                                </div>
                                <div>
                                    <h2 class="text-sm font-semibold text-stone-800">Metode Pembayaran</h2>
                                    <p class="text-xs text-stone-500">Pilih metode pembayaran pesanan.</p>
                                </div>
                            </div>

                            <div class="space-y-2.5">
                                <label id="pilihanCash" class="block cursor-pointer">
                                    <input type="radio" name="metode_pembayaran" id="pembayaranCash" value="cash"
                                        class="peer sr-only"
                                        {{ old('metode_pembayaran', 'cash') === 'cash' ? 'checked' : '' }}>

                                    <div
                                        class="flex items-center justify-between rounded-lg border border-stone-200 p-3 transition
                                        peer-checked:border-amber-600 peer-checked:bg-amber-50
                                        peer-focus-visible:ring-2 peer-focus-visible:ring-amber-500">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-stone-100 text-stone-700">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8V6m0 2v8m0 0v2m0-2c-1.657 0-3-.895-3-2" />
                                                </svg>
                                            </div>
                                            <div>
                                                <p class="text-sm font-semibold text-stone-800">Cash</p>
                                                <p class="mt-0.5 text-xs text-stone-500">Bayar tunai langsung di gerai.</p>
                                            </div>
                                        </div>
                                        <span class="text-xs font-medium text-stone-500">Tunai</span>
                                    </div>
                                </label>

                                <label class="block cursor-pointer">
                                    <input type="radio" name="metode_pembayaran" id="pembayaranQris" value="qris"
                                        class="peer sr-only"
                                        {{ old('metode_pembayaran') === 'qris' || old('metode_pengambilan') === 'diantar' ? 'checked' : '' }}>

                                    <div
                                        class="flex items-center justify-between rounded-lg border border-stone-200 p-3 transition
                                        peer-checked:border-amber-600 peer-checked:bg-amber-50
                                        peer-focus-visible:ring-2 peer-focus-visible:ring-amber-500">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-stone-100 text-stone-700">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M4 4h6v6H4zM14 4h6v6h-6zM4 14h6v6H4zM14 14h2v2h-2zM18 14h2v2h-2zM14 18h6v2h-6z" />
                                                </svg>
                                            </div>
                                            <div>
                                                <p class="text-sm font-semibold text-stone-800">QRIS</p>
                                                <p class="mt-0.5 text-xs text-stone-500">Bayar dengan memindai kode QR.</p>
                                            </div>
                                        </div>
                                        <span
                                            class="rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-semibold text-emerald-700">
                                            Digital
                                        </span>
                                    </div>
                                </label>
                            </div>

                            <div id="infoQris" class="mt-3 hidden rounded-lg border border-amber-200 bg-amber-50 p-3">
                                <p class="text-sm font-semibold text-amber-900">Pembayaran QRIS</p>
                                <p class="mt-1 text-xs leading-relaxed text-amber-800">
                                    Setelah pesanan dibuat, kamu akan diarahkan ke halaman pembayaran
                                    untuk melihat kode QRIS dan mengunggah bukti pembayaran.
                                </p>
                            </div>

                            <p id="infoCash"
                                class="mt-3 rounded-lg bg-stone-50 p-3 text-xs leading-relaxed text-stone-600">
                                Pembayaran tunai dilakukan langsung di gerai saat mengambil pesanan.
                            </p>
                        </section>

                        {{-- Catatan Pesanan --}}
                        <section class="rounded-xl border border-stone-200 bg-white p-4 shadow-sm sm:p-5">
                            <div class="mb-3">
                                <h2 class="text-sm font-semibold text-stone-800">
                                    Catatan Pesanan
                                    <span class="font-normal text-stone-400">(Opsional)</span>
                                </h2>
                                <p class="mt-1 text-xs text-stone-500">
                                    Tambahkan catatan khusus jika diperlukan.
                                </p>
                            </div>

                            <textarea name="catatan" id="catatan" rows="2" placeholder="Contoh: Pesanan akan diambil sore hari."
                                class="w-full rounded-lg border border-stone-300 px-3 py-2.5 text-sm outline-none transition focus:border-amber-600 focus:ring-2 focus:ring-amber-100">{{ old('catatan') }}</textarea>
                        </section>
                    </div>

                    {{-- Ringkasan Pesanan --}}
                    <aside class="lg:sticky lg:top-5">
                        <div class="rounded-xl border border-stone-200 bg-white p-4 shadow-sm sm:p-5">
                            <h2 class="text-base font-bold text-stone-800">
                                Ringkasan Pesanan
                            </h2>

                            <div class="mt-4 space-y-3">
                                @foreach ($items as $item)
                                    @php
                                        $produk = $item['produk'];
                                        $jumlah = $item['jumlah'];
                                        $subtotalItem = $item['subtotal'];
                                    @endphp

                                    <div class="flex gap-3">
                                        <div class="h-14 w-14 shrink-0 overflow-hidden rounded-lg bg-stone-100">
                                            @if (!empty($produk->gambar))
                                                <img src="{{ asset('storage/' . $produk->gambar) }}"
                                                    alt="{{ $produk->nama_produk }}" class="h-full w-full object-cover"
                                                    onerror="this.onerror=null;this.src='{{ asset('images/placeholder.png') }}';">
                                            @else
                                                <div class="flex h-full w-full items-center justify-center text-stone-400">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6"
                                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="1.5" d="M4 7h16v13H4zM8 7V5h8v2M8 13h8" />
                                                    </svg>
                                                </div>
                                            @endif
                                        </div>

                                        <div class="min-w-0 flex-1">
                                            <p class="line-clamp-2 text-sm font-medium text-stone-800">
                                                {{ $produk->nama_produk }}
                                            </p>
                                            <p class="mt-0.5 text-xs text-stone-500">
                                                {{ $jumlah }} × Rp{{ number_format($produk->harga, 0, ',', '.') }}
                                            </p>
                                            <p class="mt-1 text-sm font-semibold text-stone-800">
                                                Rp{{ number_format($subtotalItem, 0, ',', '.') }}
                                            </p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="my-4 border-t border-dashed border-stone-200"></div>

                            <div class="space-y-3 text-sm">
                                <div class="flex items-center justify-between gap-3 text-stone-600">
                                    <span>Subtotal Produk</span>
                                    <span class="font-medium text-stone-800">
                                        Rp{{ number_format($subtotal, 0, ',', '.') }}
                                    </span>
                                </div>

                                <div class="flex items-start justify-between gap-3 text-stone-600">
                                    <span>Ongkos Kirim</span>
                                    <span id="nilaiOngkir" class="text-right text-xs font-medium text-stone-800">
                                        Tidak ada
                                    </span>
                                </div>
                            </div>

                            <div class="my-4 border-t border-stone-200"></div>

                            <div class="flex items-center justify-between gap-3">
                                <span class="text-sm font-semibold text-stone-800">Total Pesanan</span>
                                <span class="text-lg font-bold text-amber-800">
                                    Rp{{ number_format($total, 0, ',', '.') }}
                                </span>
                            </div>

                            <p class="mt-3 text-xs leading-relaxed text-stone-500">
                                Total belum termasuk ongkos kirim. Biaya pengiriman akan dibicarakan
                                dengan admin melalui WhatsApp.
                            </p>

                            <button type="submit"
                                class="mt-5 flex w-full items-center justify-center gap-2 rounded-lg bg-amber-700 px-4 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-amber-800 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2">
                                Buat Pesanan
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 12h14m-7-7l7 7-7 7" />
                                </svg>
                            </button>

                            <div class="mt-3 flex items-start gap-2 text-xs leading-relaxed text-stone-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="mt-0.5 h-4 w-4 shrink-0 text-emerald-600"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                                <p>Pastikan data pesanan sudah benar sebelum melanjutkan.</p>
                            </div>
                        </div>
                    </aside>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const metodePengambilan = document.querySelectorAll(
                'input[name="metode_pengambilan"]'
            );

            const bagianPenerima = document.getElementById('bagianPenerima');
            const namaPenerima = document.getElementById('nama_penerima');
            const noWhatsapp = document.getElementById('no_whatsapp');
            const alamat = document.getElementById('alamat');

            const pembayaranCash = document.getElementById('pembayaranCash');
            const pembayaranQris = document.getElementById('pembayaranQris');
            const pilihanCash = document.getElementById('pilihanCash');

            const infoQris = document.getElementById('infoQris');
            const infoCash = document.getElementById('infoCash');
            const nilaiOngkir = document.getElementById('nilaiOngkir');

            let metodeSebelumnya = document.querySelector(
                'input[name="metode_pengambilan"]:checked'
            )?.value;

            function toggleCheckout() {
                const metode = document.querySelector(
                    'input[name="metode_pengambilan"]:checked'
                )?.value;

                const perluDiantar = metode === 'diantar';

                bagianPenerima.classList.toggle('hidden', !perluDiantar);

                [namaPenerima, noWhatsapp, alamat].forEach(function(input) {
                    input.required = perluDiantar;
                    input.disabled = !perluDiantar;
                });

                if (perluDiantar) {
                    pembayaranCash.checked = false;
                    pembayaranCash.disabled = true;
                    pilihanCash.classList.add('opacity-50', 'cursor-not-allowed');

                    pembayaranQris.checked = true;
                    nilaiOngkir.textContent = 'Dibicarakan dengan admin';
                } else {
                    pembayaranCash.disabled = false;
                    pilihanCash.classList.remove('opacity-50', 'cursor-not-allowed');

                    nilaiOngkir.textContent = 'Tidak ada';

                    if (metodeSebelumnya === 'diantar') {
                        pembayaranCash.checked = true;
                    }
                }

                infoQris.classList.toggle('hidden', !pembayaranQris.checked);
                infoCash.classList.toggle('hidden', !pembayaranCash.checked);

                metodeSebelumnya = metode;
            }

            metodePengambilan.forEach(function(radio) {
                radio.addEventListener('change', toggleCheckout);
            });

            pembayaranCash.addEventListener('change', toggleCheckout);
            pembayaranQris.addEventListener('change', toggleCheckout);

            toggleCheckout();
        });
    </script>
@endsection
