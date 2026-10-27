@extends('layouts.main')

@section('title', config('mitra.nama'))

@section('description',
    'Temukan produk ikan bilih dari ' .
    config('mitra.nama') .
    '. Lihat produk dan informasi
    pemesanan dengan mudah.')

@section('content')

    {{-- HERO --}}
    <section class="relative flex min-h-[calc(100svh-73px)] items-center overflow-hidden bg-[#30251F] text-white">
        <div class="absolute -right-20 -top-20 h-80 w-80 rounded-full border-[50px] border-amber-500/10"></div>
        <div class="absolute -bottom-32 -left-20 h-96 w-96 rounded-full border-[50px] border-white/5"></div>

        <div
            class="relative mx-auto grid w-full max-w-7xl items-center gap-8 px-5 py-8 sm:px-8 lg:grid-cols-2 lg:gap-12 lg:py-6">

            {{-- TEKS HERO --}}
            <div>
                <div
                    class="mb-4 inline-flex items-center gap-2 rounded-full border border-amber-400/30 bg-amber-400/10 px-3 py-1.5 text-sm font-medium text-amber-300">
                    <i class="fa-solid fa-fish"></i>
                    {{ config('mitra.nama') }}
                </div>

                <h1 class="text-3xl font-extrabold leading-tight sm:text-4xl lg:text-5xl">
                    Nikmati Kelezatan
                    <span class="text-amber-400">Ikan Bilih</span>
                    Pilihan.
                </h1>

                <p class="mt-4 max-w-xl text-base leading-7 text-white/70 sm:text-lg">
                    Temukan produk ikan bilih dari {{ config('mitra.nama') }}.
                    Kenali pilihan produk kami dan dapatkan informasi pemesanan dengan mudah.
                </p>

                <div class="mt-6 flex flex-wrap gap-3">
                    <a href="#produk"
                        class="inline-flex items-center gap-2 rounded-lg bg-amber-500 px-5 py-3 font-bold text-[#30251F] transition hover:bg-amber-400">
                        Jelajahi Produk
                        <i class="fa-solid fa-arrow-right"></i>
                    </a>

                    <a href="#tentang"
                        class="inline-flex items-center gap-2 rounded-lg border border-white/20 px-5 py-3 font-semibold text-white transition hover:bg-white/10">
                        Tentang Kami
                    </a>
                </div>

                <div class="mt-7 flex flex-wrap gap-x-6 gap-y-3 border-t border-white/10 pt-5">
                    <div>
                        <p class="text-lg font-bold text-amber-400">Ikan Bilih</p>
                        <p class="mt-1 text-xs text-white/60 sm:text-sm">Produk utama gerai</p>
                    </div>
                    <div>
                        <p class="text-lg font-bold text-amber-400">Informasi</p>
                        <p class="mt-1 text-xs text-white/60 sm:text-sm">Kenali produk kami</p>
                    </div>
                    <div>
                        <p class="text-lg font-bold text-amber-400">Praktis</p>
                        <p class="mt-1 text-xs text-white/60 sm:text-sm">Hubungi gerai dengan mudah</p>
                    </div>
                </div>
            </div>

            {{-- CAROUSEL GAMBAR PRODUK --}}
            <div class="relative mx-auto w-full max-w-lg" x-data="{
                index: 0,
                total: {{ isset($produks) ? $produks->count() : 0 }},
                timer: null,
            
                start() {
                    this.stop();
                    if (this.total > 1) {
                        this.timer = setInterval(() => {
                            this.index = (this.index + 1) % this.total;
                        }, 2000);
                    }
                },
            
                stop() {
                    if (this.timer !== null) {
                        clearInterval(this.timer);
                        this.timer = null;
                    }
                },
            
                move(step) {
                    if (this.total > 1) {
                        this.index = (this.index + step + this.total) % this.total;
                        this.start();
                    }
                }
            }" x-init="start()"
                @mouseenter="stop()" @mouseleave="start()" @touchstart.passive="stop()" @touchend.passive="start()"
                @keydown.left.prevent="move(-1)" @keydown.right.prevent="move(1)" tabindex="0"
                aria-label="Galeri gambar produk ikan bilih">
                <div class="absolute -inset-3 rotate-3 rounded-3xl bg-amber-500/20"></div>

                <div class="relative overflow-hidden rounded-3xl border border-white/10 bg-[#49372D] p-2.5 shadow-2xl">

                    @if (isset($produks) && $produks->count() > 0)

                        {{-- Semua gambar berada pada posisi yang sama --}}
                        <div class="relative h-[260px] overflow-hidden rounded-2xl sm:h-[340px] lg:h-[min(58vh,440px)]">

                            @foreach ($produks as $index => $produk)
                                <div x-cloak x-show="index === {{ $index }}"
                                    x-transition:enter="transition-opacity duration-500"
                                    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                                    class="absolute inset-0 h-full w-full">
                                    <img src="{{ asset('storage/' . $produk->gambar) }}"
                                        alt="Produk ikan bilih {{ $produk->nama_produk }}"
                                        class="h-full w-full object-cover" loading="{{ $index === 0 ? 'eager' : 'lazy' }}">
                                </div>
                            @endforeach

                            {{-- PANAH KIRI --}}
                            <button type="button" @click="move(-1)" x-show="total > 1"
                                class="absolute left-3 top-1/2 z-20 flex h-10 w-10 -translate-y-1/2 items-center justify-center rounded-full bg-black/50 text-white transition hover:bg-amber-500"
                                aria-label="Gambar sebelumnya">
                                <i class="fa-solid fa-chevron-left"></i>
                            </button>

                            {{-- PANAH KANAN --}}
                            <button type="button" @click="move(1)" x-show="total > 1"
                                class="absolute right-3 top-1/2 z-20 flex h-10 w-10 -translate-y-1/2 items-center justify-center rounded-full bg-black/50 text-white transition hover:bg-amber-500"
                                aria-label="Gambar berikutnya">
                                <i class="fa-solid fa-chevron-right"></i>
                            </button>
                        </div>

                        {{-- INDIKATOR --}}
                        <div class="flex justify-center gap-2 py-3" x-show="total > 1">
                            @foreach ($produks as $index => $produk)
                                <button type="button" @click="index = {{ $index }}; start()"
                                    class="h-2 rounded-full transition-all duration-300"
                                    :class="index === {{ $index }} ? 'w-6 bg-amber-400' : 'w-2 bg-white/40'"
                                    aria-label="Tampilkan gambar {{ $index + 1 }}"></button>
                            @endforeach
                        </div>
                    @else
                        <div
                            class="flex h-[260px] flex-col items-center justify-center rounded-2xl text-center text-white sm:h-[340px] lg:h-[min(58vh,440px)]">
                            <i class="fa-solid fa-images text-4xl text-amber-400"></i>
                            <p class="mt-4 font-semibold">Belum ada gambar produk</p>
                            <p class="mt-2 px-4 text-sm text-white/60">
                                Tambahkan produk beserta gambarnya melalui panel admin.
                            </p>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </section>

    {{-- KEUNGGULAN --}}
    <section class="bg-white py-8 sm:py-10">
        <div class="mx-auto grid max-w-7xl gap-4 px-5 sm:grid-cols-3 sm:px-8">

            <div class="flex items-start gap-4 rounded-xl p-4 transition hover:bg-amber-50">
                <div
                    class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-amber-100 text-lg text-amber-700">
                    <i class="fa-solid fa-fish"></i>
                </div>
                <div>
                    <h3 class="font-bold text-[#30251F]">Produk Ikan Bilih</h3>
                    <p class="mt-2 text-sm leading-6 text-gray-500">Kenali produk ikan bilih yang tersedia di gerai kami.
                    </p>
                </div>
            </div>

            <div class="flex items-start gap-4 rounded-xl p-4 transition hover:bg-amber-50">
                <div
                    class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-amber-100 text-lg text-amber-700">
                    <i class="fa-solid fa-circle-info"></i>
                </div>
                <div>
                    <h3 class="font-bold text-[#30251F]">Informasi Produk</h3>
                    <p class="mt-2 text-sm leading-6 text-gray-500">Temukan informasi produk untuk membantu menentukan
                        pilihan.</p>
                </div>
            </div>

            <div class="flex items-start gap-4 rounded-xl p-4 transition hover:bg-amber-50">
                <div
                    class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-amber-100 text-lg text-amber-700">
                    <i class="fa-solid fa-headset"></i>
                </div>
                <div>
                    <h3 class="font-bold text-[#30251F]">Mudah Dihubungi</h3>
                    <p class="mt-2 text-sm leading-6 text-gray-500">Hubungi gerai melalui WhatsApp untuk bertanya mengenai
                        produk.</p>
                </div>
            </div>

        </div>
    </section>

    {{-- PRODUK --}}
    <section id="produk" class="scroll-mt-24 bg-[#FAF7F2] py-14 sm:py-16">
        <div class="mx-auto max-w-7xl px-5 sm:px-8">

            <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
                <div>
                    <p class="text-sm font-bold uppercase tracking-[0.2em] text-amber-700">Produk Pilihan</p>
                    <h2 class="mt-2 text-2xl font-extrabold text-[#30251F] sm:text-3xl">Produk Ikan Bilih</h2>
                    <p class="mt-2 max-w-xl text-sm leading-7 text-gray-500 sm:text-base">
                        Jelajahi pilihan produk dari {{ config('mitra.nama') }}.
                    </p>
                </div>

                <a href="{{ url('/produk') }}"
                    class="inline-flex items-center gap-2 text-sm font-semibold text-amber-800 transition hover:text-amber-600">
                    Lihat Semua Produk
                    <i class="fa-solid fa-arrow-right"></i>
                </a>
            </div>

            @if (isset($produks) && $produks->count() > 0)
                <div class="mt-7 grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-4">
                    @foreach ($produks->take(8) as $produk)
                        <article
                            class="overflow-hidden rounded-xl border border-gray-100 bg-white shadow-sm transition hover:-translate-y-1 hover:shadow-md">
                            <div class="overflow-hidden bg-gray-100">
                                <img src="{{ asset('storage/' . $produk->gambar) }}" alt="{{ $produk->nama_produk }}"
                                    class="h-36 w-full object-cover transition duration-300 hover:scale-105 sm:h-48"
                                    loading="lazy">
                            </div>

                            <div class="p-3 sm:p-4">
                                <h3 class="line-clamp-2 text-sm font-bold text-[#30251F] sm:text-base">
                                    {{ $produk->nama_produk }}
                                </h3>
                                <p class="mt-2 text-sm font-bold text-amber-700 sm:text-base">
                                    Rp {{ number_format($produk->harga, 0, ',', '.') }}
                                </p>
                                <a href="{{ url('/produk') }}"
                                    class="mt-3 inline-flex items-center gap-2 text-xs font-semibold text-[#30251F] transition hover:text-amber-700 sm:text-sm">
                                    Lihat Produk
                                    <i class="fa-solid fa-arrow-right"></i>
                                </a>
                            </div>
                        </article>
                    @endforeach
                </div>
            @else
                <div
                    class="mt-7 rounded-2xl border border-dashed border-amber-300 bg-white/80 px-6 py-9 text-center sm:py-10">
                    <div
                        class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-amber-100 text-xl text-amber-700">
                        <i class="fa-solid fa-store"></i>
                    </div>
                    <h3 class="mt-4 text-lg font-bold text-[#30251F]">Produk Belum Tersedia</h3>
                    <p class="mx-auto mt-2 max-w-md text-sm leading-6 text-gray-500">
                        Produk akan ditampilkan di sini setelah tersedia di katalog.
                    </p>
                </div>
            @endif

        </div>
    </section>

    {{-- TENTANG KAMI --}}
    <section id="tentang" class="scroll-mt-24 bg-white py-14 sm:py-16">
        <div class="mx-auto grid max-w-7xl items-center gap-8 px-5 sm:px-8 lg:grid-cols-2 lg:gap-12">
            {{-- GAMBAR PRODUK CAROUSEL --}}
            <div class="relative">
                {{-- Aksen belakang --}}
                <div class="absolute -bottom-3 -left-3 h-full w-full rounded-2xl bg-amber-100"></div>

                {{-- Carousel Foto Produk dari Database --}}
                <div x-data="{
                    activeSlide: 0,
                    totalSlides: {{ $produks->whereNotNull('gambar')->where('gambar', '!=', '')->count() }},
                    next() {
                        if (this.totalSlides > 0) {
                            this.activeSlide = (this.activeSlide + 1) % this.totalSlides;
                        }
                    },
                    prev() {
                        if (this.totalSlides > 0) {
                            this.activeSlide = (this.activeSlide - 1 + this.totalSlides) % this.totalSlides;
                        }
                    }
                }" x-init="if (totalSlides > 1) setInterval(() => next(), 3000)"
                    class="relative overflow-hidden rounded-2xl bg-[#30251F] p-3 text-white shadow-lg sm:p-4">
                    {{-- Area Gambar --}}
                    <div class="relative h-[250px] overflow-hidden rounded-xl bg-[#30251F] sm:h-[320px] lg:h-[350px]">

                        @php
                            $fotoProduk = $produks->filter(fn($produk) => !empty($produk->gambar))->values();
                        @endphp

                        @forelse ($fotoProduk as $index => $produk)
                            <img src="{{ asset('storage/' . $produk->gambar) }}" alt="{{ $produk->nama_produk }}"
                                x-show="activeSlide === {{ $index }}"
                                x-transition:enter="transition ease-out duration-500"
                                x-transition:enter-start="opacity-0 scale-105"
                                x-transition:enter-end="opacity-100 scale-100"
                                class="absolute inset-0 h-full w-full object-cover"
                                loading="{{ $index === 0 ? 'eager' : 'lazy' }}">
                        @empty
                            <div class="flex h-full flex-col items-center justify-center text-center text-white">
                                <i class="fa-solid fa-fish text-6xl text-amber-400"></i>
                                <p class="mt-4 px-4 text-lg font-bold">
                                    {{ config('mitra.nama') }}
                                </p>
                                <p class="mt-2 text-sm text-white/60">
                                    Foto produk belum tersedia
                                </p>
                            </div>
                        @endforelse

                        @if ($fotoProduk->isNotEmpty())
                            {{-- Tombol Sebelumnya --}}
                            <button type="button" @click="prev()" aria-label="Foto sebelumnya"
                                class="absolute left-3 top-1/2 flex h-10 w-10 -translate-y-1/2 items-center justify-center rounded-full bg-[#30251F]/85 text-white shadow-md transition hover:bg-amber-400 hover:text-[#30251F] sm:h-12 sm:w-12">
                                <i class="fa-solid fa-chevron-left"></i>
                            </button>

                            {{-- Tombol Berikutnya --}}
                            <button type="button" @click="next()" aria-label="Foto berikutnya"
                                class="absolute right-3 top-1/2 flex h-10 w-10 -translate-y-1/2 items-center justify-center rounded-full bg-[#30251F]/85 text-white shadow-md transition hover:bg-amber-400 hover:text-[#30251F] sm:h-12 sm:w-12">
                                <i class="fa-solid fa-chevron-right"></i>
                            </button>
                        @endif
                    </div>

                    {{-- Indikator Carousel --}}
                    @if ($fotoProduk->isNotEmpty())
                        <div class="flex items-center justify-center gap-3 py-4">
                            @foreach ($fotoProduk as $index => $produk)
                                <button type="button" @click="activeSlide = {{ $index }}"
                                    aria-label="Tampilkan foto {{ $index + 1 }}"
                                    :class="activeSlide === {{ $index }} ?
                                        'h-3 w-7 bg-amber-400' :
                                        'h-3 w-3 bg-white/60 hover:bg-white'"
                                    class="rounded-full transition-all duration-300"></button>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            {{-- INFORMASI TENTANG KAMI --}}
            <div>
                <p class="text-sm font-bold uppercase tracking-[0.2em] text-amber-700">
                    Mengenal Usaha Kami
                </p>

                <h2 class="mt-2 text-2xl font-extrabold leading-tight text-[#30251F] sm:text-3xl">
                    {{ config('toko.nama') }}
                </h2>

                <p class="mt-4 text-sm leading-7 text-gray-600 sm:text-base">
                    {{ config('toko.nama') }} merupakan usaha yang berlokasi di
                    {{ config('toko.alamat') }}.
                    Melalui website ini, pelanggan dapat mengenal produk, melihat
                    informasi yang tersedia, serta menghubungi gerai untuk kebutuhan pemesanan.
                </p>

                <p class="mt-3 text-sm leading-7 text-gray-600 sm:text-base">
                    Website ini dirancang untuk memudahkan pelanggan memperoleh informasi
                    produk ikan bilih dan menghubungi gerai.
                </p>

                {{-- Informasi Kontak --}}
                <div class="mt-5 space-y-3">

                    {{-- Alamat --}}
                    <div class="flex items-start gap-3">
                        <i class="fa-solid fa-location-dot mt-1 text-amber-700"></i>
                        <p class="text-sm leading-6 text-gray-600">
                            {{ config('toko.alamat') }}
                        </p>
                    </div>

                    {{-- Email --}}
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-envelope text-amber-700"></i>
                        <a href="mailto:{{ config('mitra.email') }}"
                            class="break-all text-sm text-gray-600 transition hover:text-amber-700">
                            {{ config('toko.email') }}
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </section>
@endsection
