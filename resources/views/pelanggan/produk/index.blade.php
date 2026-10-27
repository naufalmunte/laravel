
@extends('layouts.main')

@section('content')
<div class="min-h-screen bg-[#FAF8F4]">
    {{-- Header katalog --}}
    <section class="bg-[#30251F] px-5 py-12 text-white sm:py-16">
        <div class="mx-auto max-w-7xl">
            <p class="mb-3 text-sm font-semibold uppercase tracking-[0.25em] text-amber-400">
                Khas Minang
            </p>

            <h1 class="text-3xl font-bold sm:text-4xl">
                Jelajahi Produk Kami
            </h1>

            <p class="mt-3 max-w-2xl text-sm leading-6 text-stone-300 sm:text-base">
                Temukan aneka produk pilihan dengan cita rasa dan cerita khas Minang.
            </p>
        </div>
    </section>

    <section class="mx-auto max-w-7xl px-5 py-10 lg:px-8">
        {{-- Pencarian dan filter --}}
        <form action="{{ route('produk.index') }}" method="GET"
            class="mb-8 grid grid-cols-1 gap-3 sm:grid-cols-[1fr_240px_auto]">

            <div class="relative">
                <svg class="absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-stone-400"
                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <circle cx="11" cy="11" r="7" stroke-width="1.8"/>
                    <path d="m16 16 4 4" stroke-width="1.8"
                        stroke-linecap="round"/>
                </svg>

                <input
                    type="text"
                    name="cari"
                    value="{{ request('cari') }}"
                    placeholder="Cari nama produk..."
                    class="w-full rounded-xl border border-stone-200 bg-white py-3 pl-12 pr-4 text-sm outline-none transition focus:border-amber-600 focus:ring-2 focus:ring-amber-100"
                >
            </div>

            <select name="kategori"
                class="w-full rounded-xl border border-stone-200 bg-white px-4 py-3 text-sm outline-none focus:border-amber-600 focus:ring-2 focus:ring-amber-100">

                <option value="">Semua kategori</option>

                @foreach ($kategoris as $kategori)
                    <option value="{{ $kategori->slug }}"
                        @selected(request('kategori') === $kategori->slug)>
                        {{ $kategori->nama_kategori }}
                    </option>
                @endforeach
            </select>

            <button type="submit"
                class="rounded-xl bg-[#59372B] px-6 py-3 text-sm font-semibold text-white transition hover:bg-[#744735]">
                Cari Produk
            </button>
        </form>

        {{-- Informasi hasil --}}
        <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
            <div>
                <h2 class="text-xl font-bold text-[#30251F]">
                    Koleksi Produk
                </h2>

                <p class="mt-1 text-sm text-stone-500">
                    {{ $produks->total() }} produk ditemukan
                </p>
            </div>

            @if (request()->filled('cari') || request()->filled('kategori'))
                <a href="{{ route('produk.index') }}"
                    class="text-sm font-semibold text-[#59372B] hover:underline">
                    Reset filter
                </a>
            @endif
        </div>

        {{-- Daftar produk --}}
        @if ($produks->count())
            <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-4 lg:gap-6">
                @foreach ($produks as $produk)
                    <article
                        class="group overflow-hidden rounded-2xl border border-stone-200/80 bg-white transition duration-300 hover:-translate-y-1 hover:shadow-lg">

                        <a href="{{ route('produk.show', $produk->slug) }}"
                            class="block">

                            <div class="relative aspect-square overflow-hidden bg-stone-100">
                                @if ($produk->gambar)
                                    <img
                                        src="{{ asset('storage/' . $produk->gambar) }}"
                                        alt="{{ $produk->nama_produk }}"
                                        class="h-full w-full object-cover transition duration-500 group-hover:scale-105"
                                        loading="lazy"
                                    >
                                @else
                                    <div class="flex h-full flex-col items-center justify-center gap-2 text-stone-400">
                                        <svg class="h-12 w-12" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <rect x="3" y="3" width="18" height="18"
                                                rx="2" stroke-width="1.5"/>
                                            <circle cx="8.5" cy="8.5" r="1.5"
                                                stroke-width="1.5"/>
                                            <path d="m21 15-5-5L5 21"
                                                stroke-width="1.5"/>
                                        </svg>
                                        <span class="text-xs">Foto belum tersedia</span>
                                    </div>
                                @endif

                                @if ($produk->stok < 1)
                                    <span class="absolute left-3 top-3 rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-700">
                                        Stok Habis
                                    </span>
                                @endif
                            </div>

                            <div class="p-3 sm:p-4">
                                <p class="mb-2 text-xs font-medium text-amber-700">
                                    {{ $produk->kategori?->nama_kategori ?? 'Tanpa kategori' }}
                                </p>

                                <h3 class="line-clamp-2 min-h-10 text-sm font-semibold leading-5 text-[#30251F] transition group-hover:text-amber-700 sm:text-base">
                                    {{ $produk->nama_produk }}
                                </h3>

                                <p class="mt-3 text-base font-bold text-[#59372B] sm:text-lg">
                                    Rp {{ number_format((float) $produk->harga, 0, ',', '.') }}
                                </p>

                                <p class="mt-1 text-xs text-stone-500">
                                    Stok: {{ $produk->stok }}
                                </p>
                            </div>
                        </a>

                        <div class="px-3 pb-4 sm:px-4">
                            <a href="{{ route('produk.show', $produk->slug) }}"
                                class="block rounded-lg border border-[#59372B] px-3 py-2.5 text-center text-xs font-semibold text-[#59372B] transition hover:bg-[#59372B] hover:text-white sm:text-sm">
                                Lihat Detail
                            </a>
                        </div>
                    </article>
                @endforeach
            </div>

            {{-- Pagination --}}
            <div class="mt-10">
                {{ $produks->links() }}
            </div>
        @else
            <div class="rounded-2xl border border-dashed border-stone-300 bg-white px-5 py-16 text-center">
                <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-stone-100">
                    <svg class="h-8 w-8 text-stone-400" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <circle cx="11" cy="11" r="7" stroke-width="1.5"/>
                        <path d="m16 16 4 4" stroke-width="1.5"
                            stroke-linecap="round"/>
                    </svg>
                </div>

                <h3 class="mt-4 text-lg font-bold text-[#30251F]">
                    Produk tidak ditemukan
                </h3>

                <p class="mt-2 text-sm text-stone-500">
                    Coba gunakan kata kunci atau kategori lain.
                </p>

                <a href="{{ route('produk.index') }}"
                    class="mt-5 inline-flex rounded-xl bg-[#59372B] px-5 py-3 text-sm font-semibold text-white hover:bg-[#744735]">
                    Lihat Semua Produk
                </a>
            </div>
        @endif
    </section>
</div>
@endsection
