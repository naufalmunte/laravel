@extends('layouts.main')

@section('content')
    <div class="min-h-[calc(100vh-64px)] bg-stone-50 py-4 lg:flex lg:items-center lg:py-5">
        <div class="mx-auto w-full max-w-6xl px-4 sm:px-6">

            {{-- Breadcrumb --}}
            <nav class="mb-4 flex flex-wrap items-center gap-2 text-xs text-stone-500">
                <a href="{{ route('home') }}" class="transition hover:text-amber-700">
                    Beranda
                </a>

                <span>/</span>

                <a href="{{ route('produk.index') }}" class="transition hover:text-amber-700">
                    Produk
                </a>

                <span>/</span>

                <span class="font-medium text-[#30251F]">
                    {{ $produk->nama_produk }}
                </span>
            </nav>

            {{-- Notifikasi Berhasil --}}
            @if (session('success'))
                <div class="mb-4 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Notifikasi Error --}}
            @if ($errors->has('keranjang'))
                <div class="mb-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                    {{ $errors->first('keranjang') }}
                </div>
            @endif

            {{-- Detail Produk --}}
            <div class="overflow-hidden rounded-2xl border border-stone-200 bg-white shadow-sm">
                <div class="grid grid-cols-1 items-center gap-5 p-5 sm:p-6 lg:grid-cols-2 lg:gap-10 lg:p-8">

                    {{-- Gambar Produk --}}
                    <div class="flex items-center justify-center">
                        <div class="w-full max-w-[280px] overflow-hidden rounded-2xl bg-stone-100 sm:max-w-xs lg:max-w-sm">
                            @if ($produk->gambar)
                                <img src="{{ asset('storage/' . $produk->gambar) }}" alt="{{ $produk->nama_produk }}"
                                    class="aspect-square w-full object-contain p-4">
                            @else
                                <div class="flex aspect-square flex-col items-center justify-center gap-2 text-stone-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M3 16.5V6.75A2.25 2.25 0 015.25 4.5h13.5A2.25 2.25 0 0121 6.75v10.5a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 17.25" />
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M3 16.5l5.25-5.25a2.25 2.25 0 013.182 0L15 14.813l1.568-1.568a2.25 2.25 0 013.182 0L21 14.5" />
                                    </svg>

                                    <span class="text-sm">Gambar belum tersedia</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Informasi Produk --}}
                    <div class="flex flex-col">

                        {{-- Kategori --}}
                        <div>
                            <a href="{{ route('produk.index', ['kategori' => $produk->kategori->slug]) }}"
                                class="inline-flex rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-800 transition hover:bg-amber-200">
                                {{ $produk->kategori->nama_kategori }}
                            </a>
                        </div>

                        {{-- Nama Produk --}}
                        <h1 class="mt-3 text-2xl font-bold leading-tight text-[#30251F] sm:text-3xl">
                            {{ $produk->nama_produk }}
                        </h1>

                        {{-- Harga --}}
                        <div class="mt-3">
                            <p class="text-xs text-stone-500">
                                Harga produk
                            </p>

                            <p class="mt-1 text-2xl font-bold text-amber-700 sm:text-3xl">
                                Rp{{ number_format($produk->harga, 0, ',', '.') }}
                            </p>
                        </div>

                        {{-- Stok --}}
                        <div class="mt-3">
                            @if ($produk->stok > 0)
                                <span
                                    class="inline-flex items-center gap-2 rounded-full bg-green-50 px-3 py-1.5 text-xs font-medium text-green-700">
                                    <span class="h-2 w-2 rounded-full bg-green-500"></span>
                                    Stok tersedia: {{ $produk->stok }}
                                </span>
                            @else
                                <span
                                    class="inline-flex items-center gap-2 rounded-full bg-red-50 px-3 py-1.5 text-xs font-medium text-red-700">
                                    <span class="h-2 w-2 rounded-full bg-red-500"></span>
                                    Stok habis
                                </span>
                            @endif
                        </div>

                        {{-- Deskripsi --}}
                        <div class="mt-4 border-t border-stone-200 pt-4">
                            <h2 class="text-sm font-bold text-[#30251F]">
                                Deskripsi Produk
                            </h2>

                            <p class="mt-2 line-clamp-3 whitespace-pre-line text-sm leading-6 text-stone-600">
                                {{ $produk->deskripsi ?: 'Belum ada deskripsi untuk produk ini.' }}
                            </p>
                        </div>

                        {{-- Form Tambah ke Keranjang --}}
                        @if ($produk->stok > 0)
                            <form action="{{ route('keranjang.store', $produk->slug) }}" method="POST" class="mt-4">
                                @csrf

                                <div class="mb-3">
                                    <label for="jumlah" class="mb-2 block text-sm font-semibold text-[#30251F]">
                                        Jumlah pembelian
                                    </label>

                                    <input type="number" id="jumlah" name="jumlah" value="{{ old('jumlah', 1) }}"
                                        min="1" max="{{ $produk->stok }}" required
                                        class="w-24 rounded-lg border border-stone-300 bg-white px-3 py-2 text-sm outline-none transition focus:border-amber-600 focus:ring-2 focus:ring-amber-100">

                                    @error('jumlah')
                                        <p class="mt-1 text-xs text-red-600">
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                <button type="submit"
                                    class="flex w-full items-center justify-center gap-2 rounded-xl bg-[#59372B] px-5 py-3 text-sm font-semibold text-white transition hover:bg-[#744735] focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M3 3h2l2.4 11.2a2 2 0 002 1.6h8.9a2 2 0 002-1.6L22 7H6" />
                                        <circle cx="10" cy="20" r="1" />
                                        <circle cx="18" cy="20" r="1" />
                                    </svg>

                                    Tambah ke Keranjang
                                </button>
                            </form>
                        @else
                            <button type="button" disabled
                                class="mt-4 w-full cursor-not-allowed rounded-xl bg-stone-200 px-5 py-3 text-sm font-semibold text-stone-500">
                                Stok Habis
                            </button>
                        @endif

                        {{-- Kembali ke Katalog --}}
                        <a href="{{ route('produk.index') }}"
                            class="mt-3 text-center text-sm font-medium text-stone-500 transition hover:text-amber-700">
                            ← Kembali ke daftar produk
                        </a>

                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

