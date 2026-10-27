@extends('layouts.main')

@section('content')
    <div class="min-h-screen bg-stone-50 py-8">
        <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">

            {{-- Header --}}
            <div class="mb-6">
                <a href="{{ route('produk.index') }}"
                    class="inline-flex items-center gap-2 rounded-xl border border-stone-300 bg-white px-4 py-2.5 text-sm font-semibold text-[#30251F] shadow-sm transition hover:border-amber-600 hover:bg-amber-50 hover:text-amber-800">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                    </svg>
                    Lanjut Belanja
                </a>

                <h1 class="mt-5 text-2xl font-bold text-[#30251F]">
                    Keranjang Belanja
                </h1>

                <p class="mt-1 text-sm text-stone-500">
                    Periksa produk dan jumlah belanjaan kamu.
                </p>
            </div>

            {{-- Notifikasi sukses --}}
            @if (session('success'))
                <div class="mb-5 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Notifikasi error --}}
            @if ($errors->any())
                <div class="mb-5 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            @if (count($items) > 0)
                <div class="grid grid-cols-1 items-start gap-6 lg:grid-cols-3">

                    {{-- Daftar produk --}}
                    <div class="space-y-4 lg:col-span-2">
                        @foreach ($items as $item)
                            <div class="rounded-2xl border border-stone-200 bg-white p-4 sm:p-5">
                                <div class="flex gap-4">

                                    {{-- Gambar produk --}}
                                    <div class="h-24 w-24 shrink-0 overflow-hidden rounded-xl bg-stone-100 sm:h-28 sm:w-28">
                                        @if ($item['produk']->gambar)
                                            <img src="{{ asset('storage/' . $item['produk']->gambar) }}"
                                                alt="{{ $item['produk']->nama_produk }}" class="h-full w-full object-cover">
                                        @else
                                            <div class="flex h-full items-center justify-center text-xs text-stone-400">
                                                Tidak ada gambar
                                            </div>
                                        @endif
                                    </div>

                                    {{-- Informasi produk --}}
                                    <div class="flex min-w-0 flex-1 flex-col justify-between">
                                        <div>
                                            <h2 class="font-semibold text-[#30251F]">
                                                {{ $item['produk']->nama_produk }}
                                            </h2>

                                            <p class="mt-1 text-sm font-semibold text-amber-800">
                                                Rp {{ number_format($item['produk']->harga, 0, ',', '.') }}
                                            </p>

                                            <p class="mt-1 text-xs text-stone-500">
                                                Stok tersedia: {{ $item['produk']->stok }}
                                            </p>
                                        </div>

                                        <div class="mt-4 flex flex-wrap items-center justify-between gap-3">

                                            {{-- Tombol jumlah --}}
                                            <form action="{{ route('keranjang.update', $item['produk']->id) }}"
                                                method="POST" class="flex items-center gap-3">
                                                @csrf
                                                @method('PATCH')

                                                <button type="submit" name="jumlah" value="{{ $item['jumlah'] - 1 }}"
                                                    {{ $item['jumlah'] <= 1 ? 'disabled' : '' }}
                                                    aria-label="Kurangi jumlah"
                                                    class="flex h-9 w-9 items-center justify-center rounded-lg border border-stone-300 text-xl text-[#30251F] transition hover:bg-stone-100 disabled:cursor-not-allowed disabled:opacity-40">
                                                    −
                                                </button>

                                                <span class="min-w-5 text-center font-semibold text-[#30251F]">
                                                    {{ $item['jumlah'] }}
                                                </span>

                                                <button type="submit" name="jumlah" value="{{ $item['jumlah'] + 1 }}"
                                                    {{ $item['jumlah'] >= $item['produk']->stok ? 'disabled' : '' }}
                                                    aria-label="Tambah jumlah"
                                                    class="flex h-9 w-9 items-center justify-center rounded-lg border border-stone-300 text-xl text-[#30251F] transition hover:bg-stone-100 disabled:cursor-not-allowed disabled:opacity-40">
                                                    +
                                                </button>
                                            </form>

                                            {{-- Hapus produk --}}
                                            <form action="{{ route('keranjang.destroy', $item['produk']->id) }}"
                                                method="POST" class="form-hapus-produk">
                                                @csrf
                                                @method('DELETE')

                                                <button type="button" onclick="bukaModalHapus(this)"
                                                    data-nama="{{ $item['produk']->nama_produk }}"
                                                    class="text-sm font-medium text-red-600 transition hover:text-red-800">
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                {{-- Subtotal produk --}}
                                <div class="mt-4 flex justify-between border-t border-stone-100 pt-3 text-sm">
                                    <span class="text-stone-500">Subtotal</span>
                                    <span class="font-bold text-[#30251F]">
                                        Rp {{ number_format($item['subtotal'], 0, ',', '.') }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Ringkasan belanja --}}
                    <div class="rounded-2xl border border-stone-200 bg-white p-5">
                        <h2 class="text-lg font-bold text-[#30251F]">
                            Ringkasan Belanja
                        </h2>

                        <div class="mt-5 flex justify-between text-sm text-stone-600">
                            <span>Total produk</span>
                            <span>{{ array_sum(array_column($items, 'jumlah')) }} item</span>
                        </div>

                        <div class="mt-4 flex justify-between gap-3 border-t border-stone-200 pt-4">
                            <span class="font-semibold text-[#30251F]">Total</span>
                            <span class="text-right text-lg font-bold text-amber-800">
                                Rp {{ number_format($total, 0, ',', '.') }}
                            </span>
                        </div>

                        <a href="{{ route('checkout.index') }}"
                            class="mt-6 block w-full rounded-xl bg-[#30251F] px-4 py-3 text-center text-sm font-semibold text-white transition hover:bg-[#49372D]">
                            Lanjut ke Checkout
                        </a>

                        <p class="mt-3 text-center text-xs text-stone-500">
                            Stok akan diperiksa kembali saat pesanan dibuat.
                        </p>
                    </div>
                </div>
            @else
                {{-- Keranjang kosong --}}
                <div class="rounded-2xl border border-stone-200 bg-white px-5 py-14 text-center">
                    <div class="text-4xl">🛒</div>

                    <h2 class="mt-4 text-lg font-bold text-[#30251F]">
                        Keranjang masih kosong
                    </h2>

                    <p class="mt-2 text-sm text-stone-500">
                        Yuk, temukan produk yang kamu inginkan.
                    </p>

                    <a href="{{ route('produk.index') }}"
                        class="mt-5 inline-block rounded-xl bg-[#30251F] px-5 py-3 text-sm font-semibold text-white transition hover:bg-[#49372D]">
                        Lihat Produk
                    </a>
                </div>
            @endif
        </div>
    </div>

    {{-- Modal konfirmasi hapus --}}
    <div id="modalHapus" class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/50 p-4" role="dialog"
        aria-modal="true" aria-labelledby="judulModalHapus">
        <div class="w-full max-w-sm rounded-2xl bg-white p-6 shadow-xl" onclick="event.stopPropagation()">
            {{-- <div class="flex h-12 w-12 items-center justify-center rounded-full bg-red-50">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M6 7h12m-10 0V5a1 1 0 011-1h4a1 1 0 011 1v2m2 0l-.7 12a1 1 0 01-1 .9H9.7a1 1 0 01-1-.9L8 7m2.5 3v6m3-6v6" />
                </svg>
            </div> --}}

            <h2 id="judulModalHapus" class="mt-4 text-lg font-bold text-[#30251F]">
                Hapus Produk?
            </h2>

            <p class="mt-2 text-sm leading-6 text-stone-600">
                Apakah kamu yakin ingin menghapus
                <span id="namaProdukHapus" class="font-semibold text-[#30251F]"></span>
                dari keranjang?
            </p>

            <div class="mt-6 flex justify-end gap-3">
                <button type="button" onclick="tutupModalHapus()"
                    class="rounded-lg border border-stone-300 px-4 py-2.5 text-sm font-semibold text-stone-700 transition hover:bg-stone-100">
                    Batal
                </button>

                <button id="tombolKonfirmasiHapus" type="button" onclick="konfirmasiHapus()"
                    class="rounded-lg bg-red-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-red-700">
                    Ya, Hapus
                </button>
            </div>
        </div>
    </div>

    <script>
        let formHapusAktif = null;

        function bukaModalHapus(button) {
            formHapusAktif = button.closest('form');

            document.getElementById('namaProdukHapus').textContent =
                button.dataset.nama;

            const modal = document.getElementById('modalHapus');

            modal.classList.remove('hidden');
            modal.classList.add('flex');

            document.body.classList.add('overflow-hidden');

            document.getElementById('tombolKonfirmasiHapus').focus();
        }

        function tutupModalHapus() {
            const modal = document.getElementById('modalHapus');

            modal.classList.add('hidden');
            modal.classList.remove('flex');

            document.body.classList.remove('overflow-hidden');

            formHapusAktif = null;
        }

        function konfirmasiHapus() {
            if (formHapusAktif) {
                formHapusAktif.submit();
            }
        }

        document.getElementById('modalHapus').addEventListener('click', function(event) {
            if (event.target === this) {
                tutupModalHapus();
            }
        });

        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                const modal = document.getElementById('modalHapus');

                if (!modal.classList.contains('hidden')) {
                    tutupModalHapus();
                }
            }
        });
    </script>
@endsection
